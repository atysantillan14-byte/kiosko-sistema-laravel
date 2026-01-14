<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\User;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tz = config('app.timezone', 'America/Argentina/Buenos_Aires');

        // =========================
        // 1) Filtros base (GET)
        // =========================
        $userId = $request->filled('user_id') ? (int) $request->input('user_id') : null;

        // mes tiene prioridad sobre desde/hasta
        $desde = null;
        $hasta = null;

        if ($request->filled('mes')) {
            $m = Carbon::createFromFormat('Y-m', $request->input('mes'), $tz)->startOfMonth();
            $desde = $m->copy()->startOfMonth();
            $hasta = $m->copy()->endOfMonth()->endOfDay();
        } else {
            if ($request->filled('desde')) $desde = Carbon::parse($request->input('desde'), $tz)->startOfDay();
            if ($request->filled('hasta')) $hasta = Carbon::parse($request->input('hasta'), $tz)->endOfDay();

            // default: últimos 7 días
            if (!$desde && !$hasta) {
                $hasta = now($tz)->endOfDay();
                $desde = now($tz)->subDays(6)->startOfDay();
            } elseif ($desde && !$hasta) {
                $hasta = $desde->copy()->endOfDay();
            } elseif (!$desde && $hasta) {
                $desde = $hasta->copy()->startOfDay();
            }
        }

        // =========================
        // 2) Turnos / Horas (sin gráfico por hora)
        // =========================
        $turno = $request->input('turno'); // manana | tarde | noche | null
        $horaDesde = $request->input('hora_desde'); // HH:MM
        $horaHasta = $request->input('hora_hasta'); // HH:MM

        // Si viene turno, setea horas automáticamente
        if ($turno) {
            if ($turno === 'manana') { $horaDesde = '06:00'; $horaHasta = '13:59'; }
            if ($turno === 'tarde')  { $horaDesde = '14:00'; $horaHasta = '21:59'; }
            if ($turno === 'noche')  { $horaDesde = '22:00'; $horaHasta = '23:59'; }
        }

        // =========================
        // 3) Query base de ventas (aplica filtros)
        // =========================
        $ventasBase = Venta::query()
            ->where('estado', '!=', 'anulada')
            ->whereBetween('created_at', [$desde, $hasta]);

        if ($userId) {
            $ventasBase->where('user_id', $userId);
        }

        if ($horaDesde && $horaHasta) {
            // TIME(created_at) BETWEEN 'HH:MM:SS' AND 'HH:MM:SS'
            $ventasBase->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde . ':00', $horaHasta . ':59']);
        }

        // =========================
        // 4) KPIs principales (lo primero que se ve)
        // =========================
        $ventasFiltradas = (clone $ventasBase)->count();
        $totalFiltrado   = (clone $ventasBase)->sum('total');

        // KPIs "Hoy" y "Mes" (en modo profesional: reflejan el filtro actual)
        // Si querés "Hoy" siempre hoy real, lo cambiamos luego.
        $hoyInicio = now($tz)->startOfDay();
        $hoyFin = now($tz)->endOfDay();

        $ventasHoyBase = Venta::query()
            ->where('estado', '!=', 'anulada')
            ->whereBetween('created_at', [$hoyInicio, $hoyFin]);

        if ($userId) {
            $ventasHoyBase->where('user_id', $userId);
        }

        if ($horaDesde && $horaHasta) {
            $ventasHoyBase->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde . ':00', $horaHasta . ':59']);
        }

        $ventasHoy   = (clone $ventasHoyBase)->count();
        $ingresosHoy = (clone $ventasHoyBase)->sum('total');
        $ventasMes   = $ventasFiltradas;
        $ingresosMes = $totalFiltrado;

        // =========================
        // 5) Gráficos
        // =========================
        $ventasPorDia = (clone $ventasBase)
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(total) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $ventasPorMetodo = (clone $ventasBase)
            ->selectRaw('metodo_pago, COUNT(*) as cantidad')
            ->groupBy('metodo_pago')
            ->orderByDesc('cantidad')
            ->get();

        // =========================
        // 6) Stock bajo + Top productos (por período filtrado)
        // =========================
        $stockBajo = Producto::with('categoria')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $topProductos = DB::table('detalle_ventas as dv')
            ->join('ventas as v', 'v.id', '=', 'dv.venta_id')
            ->leftJoin('productos as p', 'p.id', '=', 'dv.producto_id')
            ->where('v.estado', '!=', 'anulada')
            ->whereBetween('v.created_at', [$desde, $hasta])
            ->when($userId, fn($q) => $q->where('v.user_id', $userId))
            ->when(($horaDesde && $horaHasta), fn($q) => $q->whereRaw("TIME(v.created_at) BETWEEN ? AND ?", [$horaDesde . ':00', $horaHasta . ':59']))
            ->selectRaw('p.nombre as producto_nombre, SUM(dv.cantidad) as total_vendido')
            ->groupBy('p.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // =========================
        // 7) Ranking de usuarios (solo admin)
        // =========================
        $esAdmin = (Auth::user()->role ?? null) === 'admin';

        $rankingUsuarios = collect();
        if ($esAdmin) {
            $rankingUsuarios = Venta::query()
                ->where('estado', '!=', 'anulada')
                ->whereBetween('created_at', [$desde, $hasta])
                ->when(($horaDesde && $horaHasta), fn($q) => $q->whereRaw("TIME(created_at) BETWEEN ? AND ?", [$horaDesde . ':00', $horaHasta . ':59']))
                ->selectRaw('user_id, COUNT(*) as cantidad, SUM(total) as total')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->with('usuario:id,name')
                ->get();
        }

        // Usuarios para el filtro
        $usuarios = User::orderBy('name')->get(['id', 'name']);

        $primeraVentaAt = Venta::where('estado', '!=', 'anulada')->min('created_at');
        $ultimaVentaAt = Venta::where('estado', '!=', 'anulada')->max('created_at');

        $primeraVenta = $primeraVentaAt ? Carbon::parse($primeraVentaAt, $tz) : null;
        $ultimaVenta = $ultimaVentaAt ? Carbon::parse($ultimaVentaAt, $tz) : null;

        return view('dashboard.index', compact(
            'usuarios',
            'esAdmin',
            'rankingUsuarios',
            'desde',
            'hasta',
            'turno',
            'horaDesde',
            'horaHasta',
            'userId',
            'ventasFiltradas',
            'totalFiltrado',
            'ventasHoy',
            'ingresosHoy',
            'ventasMes',
            'ingresosMes',
            'ventasPorDia',
            'ventasPorMetodo',
            'stockBajo',
            'topProductos',
            'primeraVenta',
            'ultimaVenta',
        ));
    }
}






<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        $usuarios = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        return view('usuarios.index', compact('usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,empleado'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('usuarios.index')->with('error', 'No podés eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('usuarios.index')->with('error', 'No podés cambiar tu propio rol.');
        }

        $data = $request->validate([
            'role' => ['required', 'in:admin,empleado'],
        ]);

        $user->update([
            'role' => $data['role'],
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Rol actualizado.');
    }
}

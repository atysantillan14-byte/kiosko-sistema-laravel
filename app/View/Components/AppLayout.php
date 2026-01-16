<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    public bool $hideNav;
    public bool $fullBleed;
    public string $bodyClass;
    public string $shellClass;
    public ?string $title;

    public function __construct(
        bool $hideNav = false,
        bool $fullBleed = false,
        string $bodyClass = '',
        string $shellClass = 'min-h-screen bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-slate-50 to-slate-100',
        ?string $title = null
    ) {
        $this->hideNav = $hideNav;
        $this->fullBleed = $fullBleed;
        $this->bodyClass = $bodyClass;
        $this->shellClass = $shellClass;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    public string $title;
    public string $description;

    public function __construct(
        string $title = 'Beranda',
        string $description = 'Sistem Informasi Verifikasi dan Monitoring Industri Kecil Menengah Kota Gorontalo'
    ) {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}

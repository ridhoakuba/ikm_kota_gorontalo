@props([
    'title' => 'Beranda',
    'description' => 'Sistem Informasi Verifikasi dan Monitoring Industri Kecil Menengah Kota Gorontalo'
])

<x-guest-layout :title="$title" :description="$description">
    {{ $slot }}
</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <span class="text-xl font-semibold text-indigo-600">{{ config('app.name') }}</span>
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-slate-600 hover:text-slate-900 text-sm font-medium">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">Daftar</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="flex-1 flex items-center justify-center px-4 py-16 sm:py-24">
            <div class="max-w-3xl w-full text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-slate-900 tracking-tight">
                    Pemetaan Lahan & Data Geografis
                </h1>
                <p class="mt-4 text-lg text-slate-600">
                    Kelola area lahan, impor data koordinat dari CSV/Excel, dan jalankan survey lapangan dengan lokasi—semua dalam satu peta.
                </p>
                @guest
                    <div class="mt-10 flex flex-wrap justify-center gap-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-700">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 font-semibold rounded-lg hover:bg-indigo-50">Daftar Akun</a>
                        @endif
                    </div>
                @endguest
                @auth
                    <div class="mt-10">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm hover:bg-indigo-700">Buka Dashboard</a>
                    </div>
                @endauth

                <ul class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                    <li class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <span class="text-2xl font-semibold text-indigo-600">Lahan</span>
                        <p class="mt-2 text-sm text-slate-600">Gambar polygon di peta, simpan batas area dan atribut (nama, kode, luas).</p>
                    </li>
                    <li class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <span class="text-2xl font-semibold text-indigo-600">Import</span>
                        <p class="mt-2 text-sm text-slate-600">Upload CSV/Excel, pilih kolom lat/lng, tampilkan sebagai titik di peta.</p>
                    </li>
                    <li class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                        <span class="text-2xl font-semibold text-indigo-600">Survey</span>
                        <p class="mt-2 text-sm text-slate-600">Buat form survey lapangan dengan field custom dan pilih lokasi di peta.</p>
                    </li>
                </ul>
            </div>
        </main>

        <footer class="border-t border-slate-200 py-4 text-center text-sm text-slate-500">
            {{ config('app.name') }} — Laravel {{ app()->version() }}
        </footer>
    </div>
</body>
</html>

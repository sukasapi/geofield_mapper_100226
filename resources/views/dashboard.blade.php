<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <p class="text-gray-600">{{ __("Selamat datang di Geo Field Mapper. Pilih menu di bawah untuk mulai.") }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('map.index') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 border border-gray-200">
                    <h3 class="font-semibold text-gray-800">Peta</h3>
                    <p class="text-sm text-gray-600 mt-1">Lihat peta dengan layer lahan, data terimport, dan response survey.</p>
                </a>
                <a href="{{ route('areas.index') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 border border-gray-200">
                    <h3 class="font-semibold text-gray-800">Lahan</h3>
                    <p class="text-sm text-gray-600 mt-1">Kelola area/lahan dengan gambar polygon dan atribut.</p>
                </a>
                <a href="{{ route('imports.index') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 border border-gray-200">
                    <h3 class="font-semibold text-gray-800">Import Data</h3>
                    <p class="text-sm text-gray-600 mt-1">Upload CSV/Excel dan petakan kolom ke koordinat.</p>
                </a>
                <a href="{{ route('surveys.index') }}" class="block p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 border border-gray-200">
                    <h3 class="font-semibold text-gray-800">Survey</h3>
                    <p class="text-sm text-gray-600 mt-1">Buat dan isi survey lapangan dengan lokasi.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

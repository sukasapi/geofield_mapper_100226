<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Import Data ke Peta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-4">Upload file yang memiliki kolom latitude dan longitude (atau alamat). Setelah upload, Anda akan memilih kolom mana yang dipetakan.</p>
                <p class="text-sm text-gray-500 mb-4"><strong>Format yang diterima:</strong> CSV, XLSX (maks. 10 MB)</p>
                <form action="{{ route('imports.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Pilih file</label>
                            <input type="file" name="file" id="file" accept=".csv,.xlsx" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Hanya file dengan ekstensi .csv atau .xlsx</p>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Upload &amp; Pilih Kolom</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pilih Kolom untuk Koordinat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('imports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="file_path" value="{{ $file_path }}">

                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama mapping *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="lat_column" class="block text-sm font-medium text-gray-700">Kolom Latitude *</label>
                            <select name="lat_column" id="lat_column" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih kolom --</option>
                                @foreach($headers as $h)
                                    <option value="{{ $h }}" @selected(old('lat_column') === $h)>{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('lat_column')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="lng_column" class="block text-sm font-medium text-gray-700">Kolom Longitude *</label>
                            <select name="lng_column" id="lng_column" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Pilih kolom --</option>
                                @foreach($headers as $h)
                                    <option value="{{ $h }}" @selected(old('lng_column') === $h)>{{ $h }}</option>
                                @endforeach
                            </select>
                            @error('lng_column')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kolom atribut (untuk popup di peta)</label>
                        <div class="flex flex-wrap gap-4">
                            @foreach($headers as $h)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="attribute_columns[]" value="{{ $h }}" @checked(in_array($h, old('attribute_columns', []))) class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700">{{ $h }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6 overflow-x-auto">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview data (10 baris pertama)</p>
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    @foreach($headers as $h)
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ $h }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($preview_rows as $row)
                                    <tr>
                                        @foreach($headers as $i => $h)
                                            <td class="px-3 py-2 text-gray-700">{{ $row[$i] ?? '' }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('imports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan &amp; Tampilkan di Peta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

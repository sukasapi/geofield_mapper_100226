<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Isi Survey') }}: {{ $survey->name }}
        </h2>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
        <form action="{{ route('surveys.responses.store', $survey) }}" method="POST" id="form-survey-response">
            @csrf
            <input type="hidden" name="lat" id="input-lat">
            <input type="hidden" name="lng" id="input-lng">
            <input type="hidden" name="boundary" id="input-boundary">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 shadow p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Jawaban</h3>
                    <div class="space-y-4">
                        @foreach($survey->fields ?? [] as $idx => $field)
                            @php
                                $fname = $field['name'] ?? ('field_'.$idx);
                                $label = $field['label'] ?? $fname;
                                $required = !empty($field['required']);
                                $type = $field['type'] ?? 'text';
                                $options = $field['options'] ?? [];
                            @endphp
                            <div>
                                <label for="ans-{{ $fname }}" class="block text-sm font-medium text-gray-700">{{ $label }} @if($required)*@endif</label>
                                @if($type === 'textarea')
                                    <textarea name="answers[{{ $fname }}]" id="ans-{{ $fname }}" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if($required) required @endif></textarea>
                                @elseif($type === 'number')
                                    <input type="number" name="answers[{{ $fname }}]" id="ans-{{ $fname }}" step="any" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if($required) required @endif>
                                @elseif($type === 'select')
                                    <select name="answers[{{ $fname }}]" id="ans-{{ $fname }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if($required) required @endif>
                                        <option value="">-- Pilih --</option>
                                        @foreach($options as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="answers[{{ $fname }}]" id="ans-{{ $fname }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" @if($required) required @endif>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700 mb-2">Lokasi *</p>
                        <p class="text-sm text-gray-600 mb-2">Klik peta di bawah untuk menandai titik lokasi, atau biarkan kosong jika tidak perlu.</p>
                        <button type="button" id="btn-pick-location" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">Klik peta untuk pilih lokasi</button>
                        <span id="location-status" class="ml-2 text-sm text-gray-500">Belum dipilih</span>
                    </div>

                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('surveys.show', $survey) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">Batal</a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Simpan Response</button>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 shadow overflow-hidden">
                    <div id="map-survey" class="w-full h-[400px]"></div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map-survey').setView([-6.2, 106.8], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = null;
            const inputLat = document.getElementById('input-lat');
            const inputLng = document.getElementById('input-lng');
            const locationStatus = document.getElementById('location-status');
            const btnPick = document.getElementById('btn-pick-location');

            function setLocation(lat, lng) {
                inputLat.value = lat;
                inputLng.value = lng;
                locationStatus.textContent = lat.toFixed(5) + ', ' + lng.toFixed(5);
                if (marker) map.removeLayer(marker);
                marker = L.marker([lat, lng]).addTo(map);
            }

            let pickMode = false;
            btnPick.addEventListener('click', function() {
                pickMode = true;
                locationStatus.textContent = 'Klik di peta untuk memilih lokasi...';
            });

            map.on('click', function(e) {
                if (pickMode) {
                    setLocation(e.latlng.lat, e.latlng.lng);
                    pickMode = false;
                }
            });

            document.getElementById('form-survey-response').addEventListener('submit', function() {
                if (!inputLat.value || !inputLng.value) {
                    if (!confirm('Lokasi belum dipilih. Lanjutkan tanpa lokasi?')) {
                        return false;
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

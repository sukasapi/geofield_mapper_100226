<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta') }}
        </h2>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
        <div class="h-[calc(100vh-8rem)] rounded-lg overflow-hidden border border-gray-200 bg-white shadow">
            <div id="map" class="w-full h-full"></div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2" x-data="{ layers: { areas: true, imported: true, surveys: true } }">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="layers.areas" @change="window.toggleLayer && window.toggleLayer('areas', $event.target.checked)">
                <span class="text-sm text-gray-700">Lahan (Area)</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="layers.imported" @change="window.toggleLayer && window.toggleLayer('imported', $event.target.checked)">
                <span class="text-sm text-gray-700">Data terimport</span>
            </label>
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" x-model="layers.surveys" @change="window.toggleLayer && window.toggleLayer('surveys', $event.target.checked)">
                <span class="text-sm text-gray-700">Response survey</span>
            </label>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([-6.2, 106.8], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            const layerGroups = {
                areas: L.layerGroup().addTo(map),
                imported: L.layerGroup().addTo(map),
                surveys: L.layerGroup().addTo(map)
            };
            window.toggleLayer = function(name, on) {
                if (on) map.addLayer(layerGroups[name]);
                else map.removeLayer(layerGroups[name]);
            };

            fetch('{{ route("areas.index") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.features && data.features.length) {
                    const geoJson = L.geoJSON(data, {
                        style: { color: '#3388ff', weight: 2, fillOpacity: 0.2 },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties || {};
                            layer.bindPopup(
                                '<strong>' + (p.name || '') + '</strong>' +
                                (p.code ? '<br>Kode: ' + p.code : '') +
                                (p.area_ha != null ? '<br>Luas: ' + p.area_ha + ' ha' : '')
                            );
                        }
                    });
                    geoJson.addTo(layerGroups.areas);
                }
            })
            .catch(() => {});

            fetch('{{ route("map.imported-records") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.features && data.features.length) {
                    const geoJson = L.geoJSON(data, {
                        pointToLayer: function(feature, latlng) {
                            return L.marker(latlng);
                        },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties || {};
                            let html = p._mapping_name ? '<strong>' + p._mapping_name + '</strong><br>' : '';
                            Object.keys(p).filter(k => k !== '_mapping_name').forEach(k => {
                                html += k + ': ' + p[k] + '<br>';
                            });
                            layer.bindPopup(html || 'Data terimport');
                        }
                    });
                    geoJson.addTo(layerGroups.imported);
                }
            })
            .catch(() => {});

            fetch('{{ route("map.survey-responses") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.features && data.features.length) {
                    const geoJson = L.geoJSON(data, {
                        pointToLayer: function(feature, latlng) {
                            return L.marker(latlng, { icon: L.divIcon({ className: 'survey-marker', html: '<span style="background:#22c55e;width:12px;height:12px;border-radius:50%;display:inline-block;"></span>' }) });
                        },
                        style: { color: '#22c55e', weight: 2, fillOpacity: 0.2 },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties || {};
                            let html = p._survey ? '<strong>' + p._survey + '</strong><br>' : '';
                            Object.keys(p).filter(k => k !== '_survey').forEach(k => {
                                html += k + ': ' + p[k] + '<br>';
                            });
                            layer.bindPopup(html || 'Survey response');
                        }
                    });
                    geoJson.addTo(layerGroups.surveys);
                }
            })
            .catch(() => {});

            window.mapInstance = map;
            window.mapLayerGroups = layerGroups;
        });
    </script>
    @endpush
</x-app-layout>

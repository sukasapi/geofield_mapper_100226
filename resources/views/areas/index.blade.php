<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Lahan / Areas') }}
            </h2>
            <button type="button" id="btn-draw-polygon" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Tambah Area (Gambar Polygon)
            </button>
        </div>
    </x-slot>

    <div class="py-4 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4 text-red-800">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col lg:flex-row gap-4">
            <div class="lg:w-2/3 h-[500px] rounded-lg overflow-hidden border border-gray-200 bg-white shadow">
                <div id="map-areas" class="w-full h-full"></div>
            </div>
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg border border-gray-200 shadow p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Daftar Area</h3>
                    <ul id="areas-list" class="space-y-2 divide-y divide-gray-100">
                        @forelse($areas as $area)
                            <li class="py-2 flex justify-between items-center" data-id="{{ $area->id }}">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $area->name }}</span>
                                    @if($area->code)<span class="text-gray-500 text-sm"> ({{ $area->code }})</span>@endif
                                    @if($area->area_ha !== null)<span class="block text-sm text-gray-600">{{ $area->area_ha }} ha</span>@endif
                                </div>
                                <div class="flex flex-wrap gap-1 items-center">
                                    <button type="button" class="lihat-area text-gray-600 hover:text-gray-900 text-sm" data-area='@json($area)'>Lihat</button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" class="edit-area text-indigo-600 hover:text-indigo-800 text-sm" data-area='@json($area)'>Edit</button>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('areas.destroy', $area) }}" method="POST" class="inline" onsubmit="return confirm('Hapus area ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Hapus</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="text-gray-500 py-4">Belum ada area. Klik "Tambah Area" dan gambar polygon di peta.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form area: tampilan dikontrol via JS (display none/flex) -->
    <div id="modal-area" class="fixed inset-0 overflow-y-auto bg-black/30 flex items-center justify-center p-4" style="display: none; z-index: 9999;" aria-modal="true" role="dialog" aria-labelledby="modal-area-title">
        <div class="relative w-full max-w-md">
            <div class="fixed inset-0 bg-black/50 -z-10" id="modal-area-backdrop"></div>
            <div class="relative bg-white rounded-lg shadow-xl w-full p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4" id="modal-area-title">Tambah Area</h3>
                <form id="form-area" method="POST" action="{{ route('areas.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="boundary" id="form-boundary">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama *</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700">Kode</label>
                            <input type="text" name="code" id="code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="area_ha" class="block text-sm font-medium text-gray-700">Luas (ha)</label>
                            <input type="number" name="area_ha" id="area_ha" step="0.0001" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="attributes_color" class="block text-sm font-medium text-gray-700">Warna poligon</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="attributes[color]" id="attributes_color" value="#3388ff" class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                                <input type="text" id="attributes_color_hex" value="#3388ff" maxlength="7" class="block w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="#3388ff">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Pilih warna untuk menandai area di peta.</p>
                        </div>
                        <div>
                            <label for="attributes_notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                            <textarea name="attributes[notes]" id="attributes_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" id="modal-area-cancel" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map-areas').setView([-6.2, 106.8], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);

            const drawControl = new L.Control.Draw({
                draw: {
                    polygon: {
                        allowIntersection: false,
                        shapeOptions: { color: '#3388ff', weight: 2 },
                        guidelines: true
                    },
                    polyline: false,
                    circle: false,
                    rectangle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                }
            });
            var defaultColors = ['#3388ff', '#22c55e', '#eab308', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'];
            var defaultColorIndex = 0;
            var currentNewLayer = null;

            function nextDefaultColor() {
                var c = defaultColors[defaultColorIndex % defaultColors.length];
                defaultColorIndex++;
                return c;
            }

            function geoJsonFromLayer(layer) {
                var geo = layer.toGeoJSON();
                return { type: 'Polygon', coordinates: geo.geometry.coordinates };
            }

            function applyColorToLayer(layer, hex) {
                if (!layer || !layer.setStyle) return;
                var color = hex || '#3388ff';
                layer.setStyle({ color: color, fillColor: color, weight: 2, fillOpacity: 0.2 });
            }

            function showAreaModal(boundaryGeoJson, newLayer) {
                document.getElementById('form-boundary').value = JSON.stringify(boundaryGeoJson);
                document.getElementById('form-area').action = '{{ route("areas.store") }}';
                document.getElementById('form-method').value = 'POST';
                document.getElementById('modal-area-title').textContent = 'Tambah Area';
                document.getElementById('name').value = '';
                document.getElementById('code').value = '';
                document.getElementById('area_ha').value = '';
                document.getElementById('attributes_notes').value = '';
                currentNewLayer = newLayer || null;
                var color = nextDefaultColor();
                document.getElementById('attributes_color').value = color;
                document.getElementById('attributes_color_hex').value = color;
                if (currentNewLayer) {
                    applyColorToLayer(currentNewLayer, color);
                }
                var modal = document.getElementById('modal-area');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.style.visibility = 'visible';
                    modal.style.zIndex = '9999';
                }
            }

            function onDrawCreated(e) {
                try {
                    var layer = e && e.layer;
                    if (!layer || !layer.toGeoJSON) return;
                    drawnItems.addLayer(layer);
                    var boundary = geoJsonFromLayer(layer);
                    setTimeout(function() {
                        showAreaModal(boundary, layer);
                    }, 150);
                } catch (err) {
                    if (e && e.layer) {
                        drawnItems.addLayer(e.layer);
                        try {
                            var b = geoJsonFromLayer(e.layer);
                            setTimeout(function() { showAreaModal(b, e.layer); }, 150);
                        } catch (_) {}
                    }
                }
            }

            map.on('draw:created', onDrawCreated);
            if (typeof L.Draw !== 'undefined' && L.Draw.Event && L.Draw.Event.CREATED && L.Draw.Event.CREATED !== 'draw:created') {
                map.on(L.Draw.Event.CREATED, onDrawCreated);
            }

            map.addControl(drawControl);

            document.getElementById('btn-draw-polygon').addEventListener('click', function() {
                new L.Draw.Polygon(map, drawControl.options.draw.polygon).enable();
            });

            fetch('{{ route("areas.index") }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.features && data.features.length) {
                    var geoJson = L.geoJSON(data, {
                        style: function(feature) {
                            var c = (feature.properties && feature.properties.attributes && feature.properties.attributes.color) || '#3388ff';
                            return { color: c, fillColor: c, weight: 2, fillOpacity: 0.2 };
                        },
                        onEachFeature: function(feature, layer) {
                            const p = feature.properties || {};
                            layer.feature = feature;
                            layer.bindPopup(
                                '<strong>' + (p.name || '') + '</strong>' +
                                (p.code ? '<br>Kode: ' + p.code : '') +
                                (p.area_ha != null ? '<br>Luas: ' + p.area_ha + ' ha' : '') +
                                '<br><button type="button" class="edit-area-link mt-2 text-indigo-600 text-sm" data-id="' + feature.id + '">Edit</button>'
                            );
                            drawnItems.addLayer(layer);
                        }
                    });
                }
            })
            .catch(() => {});

            function hideAreaModal() {
                var modal = document.getElementById('modal-area');
                if (modal) {
                    modal.style.display = 'none';
                }
            }

            document.getElementById('modal-area').addEventListener('click', function(ev) {
                if (ev.target.id === 'modal-area-backdrop' || ev.target.id === 'modal-area-cancel') {
                    currentNewLayer = null;
                    hideAreaModal();
                }
            });

            var colorInput = document.getElementById('attributes_color');
            var colorHex = document.getElementById('attributes_color_hex');
            if (colorInput && colorHex) {
                colorInput.addEventListener('input', function() {
                    var v = this.value;
                    colorHex.value = v;
                    if (currentNewLayer) applyColorToLayer(currentNewLayer, v);
                });
                colorHex.addEventListener('input', function() {
                    var v = this.value;
                    if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
                        colorInput.value = v;
                        if (currentNewLayer) applyColorToLayer(currentNewLayer, v);
                    }
                });
            }

            document.getElementById('form-area').addEventListener('submit', function(e) {
                if (!document.getElementById('form-boundary').value && document.getElementById('form-method').value === 'POST') {
                    e.preventDefault();
                    alert('Gambar polygon terlebih dahulu.');
                    return false;
                }
                currentNewLayer = null;
            });

            document.body.addEventListener('click', function(e) {
                var lihatBtn = e.target.closest('.lihat-area');
                var editBtn = e.target.closest('.edit-area');
                var editLink = e.target.closest('.edit-area-link');
                if (lihatBtn && lihatBtn.dataset.area) {
                    e.preventDefault();
                    var area = JSON.parse(lihatBtn.dataset.area);
                    showAreaOnMap(area);
                }
                if (editBtn && editBtn.dataset.area) {
                    e.preventDefault();
                    var area = JSON.parse(editBtn.dataset.area);
                    openEditModal(area);
                }
                if (editLink && editLink.dataset.id) {
                    e.preventDefault();
                    fetch('{{ url("/areas") }}/' + editLink.dataset.id, { headers: { 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(feature => {
                            const area = {
                                id: feature.id,
                                name: feature.properties.name,
                                code: feature.properties.code,
                                area_ha: feature.properties.area_ha,
                                boundary: feature.geometry,
                                attributes: feature.properties.attributes || {}
                            };
                            openEditModal(area);
                        });
                }
            });

            function showAreaOnMap(area) {
                if (!area || !area.boundary) return;
                var geojson = { type: 'Feature', geometry: area.boundary };
                var layer = L.geoJSON(geojson);
                var bounds = layer.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [40, 40], maxZoom: 16 });
                }
            }

            function openEditModal(area) {
                currentNewLayer = null;
                document.getElementById('form-area').action = '{{ url("/areas") }}/' + area.id;
                document.getElementById('form-method').value = 'PUT';
                document.getElementById('modal-area-title').textContent = 'Edit Area';
                document.getElementById('form-boundary').value = JSON.stringify(area.boundary);
                document.getElementById('name').value = area.name || '';
                document.getElementById('code').value = area.code || '';
                document.getElementById('area_ha').value = area.area_ha ?? '';
                var color = (area.attributes && area.attributes.color) || '#3388ff';
                document.getElementById('attributes_color').value = color;
                document.getElementById('attributes_color_hex').value = color;
                document.getElementById('attributes_notes').value = (area.attributes && area.attributes.notes) || '';
                var modal = document.getElementById('modal-area');
                if (modal) {
                    modal.style.display = 'flex';
                    modal.style.zIndex = '9999';
                }
            }
        });
    </script>
    @endpush
</x-app-layout>

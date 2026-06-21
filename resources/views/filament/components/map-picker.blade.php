@php
    $lat = $getLat();
    $lng = $getLng();
    $latField = $getLatField();
    $lngField = $getLngField();
@endphp

<div x-data="mapPicker('{{ $lat }}', '{{ $lng }}')" class="w-full">

    {{-- زر تحديد الموقع من الخريطة --}}
    <div class="mb-3 flex items-center gap-3">
        <button
            type="button"
            @click="toggleMap()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold text-white transition"
            style="background-color: #1e3a5f;"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <span x-text="showMap ? 'إخفاء الخريطة' : '📍 اختر الموقع من الخريطة'"></span>
        </button>

        <span x-show="lat && lng" class="text-xs text-green-600 font-medium">
            ✓ تم تحديد الموقع
            (<span x-text="parseFloat(lat).toFixed(5)"></span>,
             <span x-text="parseFloat(lng).toFixed(5)"></span>)
        </span>
    </div>

    {{-- الخريطة --}}
    <div x-show="showMap" x-transition class="rounded-xl overflow-hidden border-2 border-navy mb-3" style="height: 400px;">
        <div id="map-picker-{{ $getId() }}" style="height: 100%; width: 100%;"></div>
    </div>

    <p x-show="showMap" class="text-xs text-gray-500 mb-2">
        💡 اضغط على أي مكان في الخريطة لتحديد الموقع بدقة
    </p>

</div>

@push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function mapPicker(initLat, initLng) {
            return {
                showMap: false,
                lat: initLat || '',
                lng: initLng || '',
                map: null,
                marker: null,

                toggleMap() {
                    this.showMap = !this.showMap;
                    if (this.showMap) {
                        this.$nextTick(() => this.initMap());
                    }
                },

                initMap() {
                    if (this.map) {
                        this.map.invalidateSize();
                        return;
                    }

                    const defaultLat = this.lat || 26.5590;
                    const defaultLng = this.lng || 31.6957;

                    this.map = L.map('map-picker-{{ $getId() }}').setView([defaultLat, defaultLng], this.lat ? 15 : 7);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 19,
                    }).addTo(this.map);

                    if (this.lat && this.lng) {
                        this.addMarker(this.lat, this.lng);
                    }

                    this.map.on('click', (e) => {
                        const { lat, lng } = e.latlng;
                        this.lat = lat.toFixed(8);
                        this.lng = lng.toFixed(8);
                        this.addMarker(lat, lng);
                        this.updateFilamentFields();
                    });

                    document.addEventListener('livewire:navigated', () => {
                        if (this.map) this.map.invalidateSize();
                    });
                },

                addMarker(lat, lng) {
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    const icon = L.divIcon({
                        html: `<div style="background:#1e3a5f;width:20px;height:20px;border-radius:50%;border:3px solid white;box-shadow:0 2px 6px rgba(0,0,0,0.4);"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10],
                        className: '',
                    });

                    this.marker = L.marker([lat, lng], { icon, draggable: true }).addTo(this.map);

                    // السماح بسحب الـ marker
                    this.marker.on('dragend', (e) => {
                        const pos = e.target.getLatLng();
                        this.lat = pos.lat.toFixed(8);
                        this.lng = pos.lng.toFixed(8);
                        this.updateFilamentFields();
                    });
                },

                updateFilamentFields() {
                    const latInput = document.querySelector('input[id*="latitude"]');
                    const lngInput = document.querySelector('input[id*="longitude"]');

                    if (latInput) {
                        latInput.value = this.lat;
                        latInput.dispatchEvent(new Event('input',  { bubbles: true }));
                        latInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                    if (lngInput) {
                        lngInput.value = this.lng;
                        lngInput.dispatchEvent(new Event('input',  { bubbles: true }));
                        lngInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }

                },
            }
        }
    </script>
@endpush

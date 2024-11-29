<div>
    <div class="container mx-auto max-w-sm">
        <div class="bg-white p-6 rounded-lg mt-3 shadow-lg">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai : </strong> {{ Auth::user()->name }}</p>
                        <p><strong>Kantor : </strong>{{ $schedule->kantor->name }}</p>
                        <p><strong>Shift : </strong>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} -
                            {{ $schedule->shift->end_time }}) wib</p>
                        @if ($schedule->is_wfa)
                            <p class="text-green-500"><strong>Status : </strong>WFA</p>
                        @else
                            <p><strong>Status : </strong>WFO</p>
                        @endif
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Waktu Datang</h4>
                            <p><strong>{{ $attendance ? $attendance->start_time : '-' }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Waktu Pulang</h4>
                            <p><strong>{{ $attendance ? $attendance->end_time : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="mb-4 rounded-lg border border-gray-300" wire:ignore></div>
                    @if (session()->has('error'))
                        <div style="color: red; padding: 10px; border: 1px solid red; backgroud-color: #fdd;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form class="row g-3 mt-3" wire:submit="store" enctype="multipart/form-data">
                        <!-- Rounded switch -->
                        <div class="p-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="isWfa" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                                <span class="ms-3 text-sm font-medium">Absen Diluar Kantor</span>
                            </label>

                            @if ($isWfa)
                                <div class="mt-4">
                                    <label for="deskripsi"
                                        class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea id="deskripsi" wire:model="deskripsi" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                    <p class="mt-2 text-sm text-gray-500">Berikan penjelasan mengapa Anda absen di luar
                                        kantor.</p>
                                </div>
                            @endif
                        </div>

                        <button type="button" onclick="tagLocation()"
                            class="px-4 py-2 bg-blue-500 text-white rounded">Tag Location</button>
                        @if ($insideRadius)
                            <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded">Submit
                                Presensi</button>
                        @endif
                    </form>
                </div>

            </div>
        </div>

    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let lat;
        let lng;
        const office = [{{ $schedule->kantor->latitude }}, {{ $schedule->kantor->longitude }}];
        const radius = {{ $schedule->kantor->radius }};
        let component;
        let marker;
        document.addEventListener('livewire:initialized', function() {
            component = @this;
            map = L.map('map').setView([{{ $schedule->kantor->latitude }}, {{ $schedule->kantor->longitude }}],
                17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const circle = L.circle(office, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        })


        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);

                    //cek didalam radius dan dalam WFA atau tidak
                    if (isWithinRadius(lat, lng, office, radius)) {
                        // alert('Anda berada di dalam radius kantor');
                        component.set('insideRadius', true);
                        component.set('latitude', lat);
                        component.set('longitude', lng);
                    }
                })
            } else {
                alert('Tidak bisa get location');
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            const is_wfa = {{ $schedule->is_wfa }}
            if (is_wfa) {
                return true;
            } else {
                let distance = map.distance([lat, lng], center);
                return distance <= radius;
            }

        }
    </script>

</div>

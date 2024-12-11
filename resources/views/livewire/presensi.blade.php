<div>
    <div class="container mx-auto max-w-sm">
        <div class="bg-white p-6 rounded-lg mt-3 shadow-lg">
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p><strong>Nama Pegawai : </strong> {{ Auth::user()->name }}</p>
                        <p id="demo"><strong>Kantor : </strong>{{ $kantorName }}</p>
                        <p><strong>Shift : </strong>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} -
                            {{ $schedule->shift->end_time }}) wib</p>
                        @if ($isWfa)
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
                    <form class="row g-3 mt-3" wire:submit.prevent="store" enctype="multipart/form-data">
                        <!-- Switch Absen Diluar Kantor -->
                        <div class="p-4">
                            @if ($isWfa)
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="isWfa" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ms-3 text-sm font-medium">Absen Diluar Kantor</span>
                                </label>
                            @endif

                            <!-- Penjelasan untuk Absen di Luar Kantor -->
                            @if ($isWfa)
                                <div class="mt-4">
                                    <div class="flex flex-col space-y-2">
                                        <label for="deskripsi"
                                            class="text-sm font-medium text-gray-700">Deskripsi</label>
                                        <textarea id="deskripsi" wire:model="deskripsi" rows="4"
                                            class="text-sm font-medium p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-800"
                                            placeholder="Berikan penjelasan mengapa Anda absen di luar kantor."></textarea>
                                        @error('deskripsi')
                                            <span class="text-sm text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Tombol Tag Lokasi -->
                        <button type="button" onclick="tagLocation()"
                            class="px-4 py-2 bg-blue-500 text-white rounded">Tag Location</button>

                        <!-- Tombol Submit -->
                        @if ($insideRadius || $isWfa)
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
        const kantorData = @json($kantor);

        let component;
        let marker;
        let i = 0;
        document.addEventListener('livewire:initialized', function() {
            component = @this;
            map = L.map('map').setView([{{ $schedule->kantor->latitude }}, {{ $schedule->kantor->longitude }}],
                17);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            for (let i = 0; i < kantorData.length; i++) {
                const kantor = [kantorData[i].latitude, kantorData[i].longitude];
                const radius = kantorData[i].radius;

                const circle = L.circle(kantor, {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: radius
                }).addTo(map);
            }
        })


        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Hapus marker lama jika ada
                    if (marker) {
                        map.removeLayer(marker);
                    }

                    // Tambahkan marker di lokasi pengguna
                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 15);

                    // Cek apakah user berada di dalam salah satu circle kantor
                    let isInsideAnyCircle = false;

                    kantorData.forEach(function(kantor) {
                        const kantorLatLng = [kantor.latitude, kantor.longitude];
                        const radius = kantor.radius;

                        if (isWithinRadius(lat, lng, kantorLatLng, radius)) {
                            isInsideAnyCircle = true;
                            component.set('insideRadius', true);
                            component.set('isWfa', false); // Tidak WFA jika di dalam radius kantor
                            component.set('latitude', lat);
                            component.set('longitude', lng);
                            component.set('kantorID', kantor.id);
                            component.set('kantorName', kantor.name);
                        }
                    });

                    // Jika tidak ada dalam radius kantor
                    if (!isInsideAnyCircle) {
                        alert('Anda berada di luar radius kantor.');
                        component.set('isWfa', true);
                        component.set('insideRadius', false); // Pastikan diatur false
                        component.set('latitude', lat);
                        component.set('longitude', lng);
                        component.set('kantorID', 5); // Default untuk WFA
                        component.set('kantorName', 'WFA');
                    }
                });
            } else {
                alert('Geolocation tidak didukung di browser Anda.');
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            let distance = map.distance([lat, lng], center); // Menghitung jarak
            return distance <= radius;
        }
    </script>
</div>

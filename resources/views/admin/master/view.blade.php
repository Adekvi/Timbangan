<x-layout.home title="Upload Firmware">

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div class="judul">
            <h5 class="welcome-message">Unggah File Firmware</h5>
            <h6 class="fw-bold">ESP 32</h6>
        </div>
        <div class="text-end">
            <h6 id="current-day" class="mb-0 fw-bold"></h6>
            <small id="current-time" class="text-muted"></small>
        </div>
    </div>

    <hr>

    <div class="page-content">
        <section class="row">
            <div class="card">
                <div class="card-body">
                    <div class="title d-flex justify-content-between mb-2">
                        <h5>Riwayat File Firmware ESP</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                    <hr>
                    <form action="{{ route('admin.view-data') }}" method="GET" class="row gy-2 gx-3 align-items-end">
                        <div class="d-flex justify-content-between">
                            <div class="col-6 col-md-2">
                                <label for="entries" class="form-label fw-semibold small mb-1">Tampil</label>
                                <select name="entries" id="entries" class="form-select form-select-sm">
                                    <option value="10" {{ $entries == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $entries == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $entries == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $entries == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 position-relative">
                                <label for="search" class="form-label fw-semibold small mb-1">Cari</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" id="search" class="form-control"
                                        placeholder="Search......" value="{{ $search }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Device ID</th>
                                    <th>Name</th>
                                    <th>Firmware Version</th>
                                    <th>Device Type</th>
                                    <th>IP Address</th>
                                    <th>Last Online</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($devices->isEmpty())
                                    <tr>
                                        <td class="text-center" colspan="8">Belum ada file</td>
                                    </tr>
                                @else
                                    <tr>
                                        @foreach ($devices as $item)
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->device_id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->firmware_version }}</td>
                                            <td>{{ $item->device_type }}</td>
                                            <td>{{ $item->ip_address }}</td>
                                            <td>{{ $item->last_online_at }}</td>
                                            <td>
                                                @if ($item->status == 'upload')
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#posting{{ $item->id }}"
                                                        class="btn btn-outline-info btn-sm" data-bs-title="Post">
                                                        <i class="fa-solid fa-file-arrow-up"></i>
                                                    </button>
                                                @elseif ($item->status == 'success')
                                                    <span class="badge bg-success">Success</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    <tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex mt-2 justify-content-end">
                        {{ $devices->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('admin.master.device.create')

    @foreach ($devices as $dev)
        <div class="modal fade" id="posting{{ $dev->id }}" tabindex="-1" aria-labelledby="timbangModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
                <!-- Responsive fullscreen di HP -->
                <div class="modal-content">
                    <div class="modal-header text-dark">
                        <h5 class="modal-title" id="timbangModalLabel">Post File ke User</h5>
                        <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.firmware.post', $dev->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-md-4">
                            <div class="table-responsive">
                                <table class="table table-responsive table-sm align-middle mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Name</th>
                                            <td>{{ $latestFirmware->file_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Firmware Version</th>
                                            <td>{{ $latestFirmware->version ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Notes</th>
                                            <td>{{ $latestFirmware->notes ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="verifikasi">
                            <h5 class="text-center">Apakah Anda Yakin Ingin Mengirim File Firmware Ini ke User?</h5>
                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer justify-content-center gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fa-solid fa-circle-check"></i> Ya
                            </button>
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                <i class="fa-solid fa-circle-xmark"></i> Tidak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('css')
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function updateDateTime() {
                const now = new Date();

                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dayName = days[now.getDay()];
                const date = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                const time = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });

                document.getElementById('current-day').textContent = `${dayName}, ${date}`;
                document.getElementById('current-time').textContent = time;
            }

            // Jalankan saat halaman load dan per detik
            document.addEventListener('DOMContentLoaded', () => {
                updateDateTime();
                setInterval(updateDateTime, 1000);
            });
        </script>
    @endpush

</x-layout.home>

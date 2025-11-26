<x-layout.home title="Timbangan Ordersheet">

    <div class="page-heading d-flex justify-content-between align-items-center">
        <h5 class="welcome-message">Sistem Timbangan Ordersheet</h5>

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
                    <div class="tambah mb-2">
                        {{-- <a href="{{ url('user/ordersheet-view/create') }}" class="btn btn-info">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a> --}}
                    </div>
                    <hr>


                    {{-- PENCARIAN --}}
                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Cari</label>
                            <input type="text" id="search" class="form-control" placeholder="Masukkan data">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-3 col-sm-6 d-grid">
                            <button type="button" id="searchBtn" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="text-center my-3">
                        <div class="spinner-border text-primary" id="loadingSpinner" style="display:none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Tabel Hasil -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle text-center" id="resultTable">
                            <thead class="table-info">
                                <tr>
                                    <th>No</th>
                                    <th>Buyer</th>
                                    <th>PO Number</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                    <th>FOB</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-muted text-center py-4">
                                        Silakan cari data untuk memulai timbangan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav id="pagination" class="d-flex justify-content-center mt-3"></nav>
                </div>
            </div>

            <div class="card report">
                <div class="card-body">
                    <!-- Carton Weight Report -->
                    <div class="judul">
                        <h5 class="fw-bold text-center mb-3">Carton Weight Report - <span>Laporan Timbangan
                                Karton</span>
                        </h5>
                        <div class="d-flex justify-content-center">
                            <a href="{{ route('order.print') }}" target="_blank" class="btn btn-primary">
                                <i class="fa-solid fa-print"></i> Print Laporan
                            </a>
                        </div>
                    </div>
                    <hr>

                    <div class="cetak">
                        @if ($groupedOrders->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="fas fa-file"></i> Belum Ada Data
                            </div>
                        @else
                            @foreach ($groupedOrders as $groupKey => $orders)
                                @php
                                    [$date, $buyer] = explode('|', $groupKey);
                                @endphp

                                <!-- Kartu Terpisah per Buyer -->
                                <div class="card shadow-sm mb-5 border-0">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div class="text-center">
                                                <h5 class="mb-0 fw-bold">Current Weight Report
                                                </h5>
                                                <h6>
                                                    Laporan Timbangan Karton
                                                </h6>
                                                <small>
                                                    Buyer: <strong>{{ $buyer }}</strong> |
                                                    {{ $date }}
                                                </small>
                                                <a href="{{ route('order.print.buyer', $buyer) }}" target="_blank"
                                                    class="btn btn-outline-info btn-sm text-end">
                                                    <i class="bi bi-printer"></i> Print
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-bordered text-center align-middle mb-0"
                                                style="white-space: nowrap; table-layout: fixed; border-collapse: collapse;">
                                                <thead class="table-light sticky-top" style="z-index: 1;">
                                                    <tr>
                                                        <th rowspan="2"
                                                            style="vertical-align: middle; width: 100px; position: sticky; left: 0; background: #f8f9fa;">
                                                            Date</th>
                                                        @for ($i = 0; $i < 10; $i++)
                                                            <th style="width: 85px;">Ctn. No</th>
                                                        @endfor
                                                        <th rowspan="2" style="vertical-align: middle; width: 90px;">
                                                            Total</th>
                                                        <th rowspan="2"
                                                            style="vertical-align: middle; width: 100px;">
                                                            Remark</th>
                                                    </tr>
                                                    <tr>
                                                        @for ($i = 0; $i < 10; $i++)
                                                            <th style="width: 85px;">Weight</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $item)
                                                        @php
                                                            $timbangans = $item->timbangans->take(10);
                                                            $noBoxes = $timbangans->pluck('no_box')->toArray();
                                                            $weights = $timbangans->pluck('berat')->toArray();
                                                            $noBoxes = array_pad($noBoxes, 10, '-');
                                                            $weights = array_pad($weights, 10, '-');
                                                            $totalWeight = array_sum(
                                                                array_filter($weights, 'is_numeric'),
                                                            );
                                                        @endphp

                                                        <!-- Baris No. Box -->
                                                        <tr>
                                                            <td rowspan="2"
                                                                style="vertical-align: middle; position: sticky; left: 0; background: #fff; z-index: 1;">
                                                                {{ $date }}
                                                            </td>
                                                            @foreach ($noBoxes as $no)
                                                                <td>{{ $no }}</td>
                                                            @endforeach
                                                            <td rowspan="2" class="fw-bold text-primary">
                                                                {{ number_format($totalWeight, 2) }}
                                                            </td>
                                                            <td rowspan="2"></td>
                                                        </tr>

                                                        <!-- Baris Weight -->
                                                        <tr class="table-secondary">
                                                            @foreach ($weights as $berat)
                                                                <td class="text-muted">{{ $berat }}</td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="timbangModal" tabindex="-1" aria-labelledby="timbangModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable modal-fullscreen-sm-down">
            <!-- Responsive fullscreen di HP -->
            <div class="modal-content">
                <div class="modal-header text-dark">
                    <h5 class="modal-title" id="timbangModalLabel">Detail Ordersheet & Laporan</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-3 p-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 p-md-4">
                            <form id="formOrdersheet" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="info_id" name="id">
                                <input type="hidden" name="berat" id="hiddenBerat" value="0">

                                <!-- ==== BAGIAN INFORMASI ORDERSHEET ==== -->
                                <h5 class="fw-bold mb-3 text-primary">Informasi Ordersheet</h5>
                                <hr class="my-3">

                                <div class="row g-3">
                                    <!-- KOLOM KIRI -->
                                    <div class="col-12 col-lg-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%" class="bg-light">BUYER</th>
                                                        <td><input type="text" id="info_buyer" name="Buyer"
                                                                class="form-control form-control-sm"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Order No.</th>
                                                        <td><input type="text" id="info_order_code"
                                                                name="Order_code" class="form-control form-control-sm"
                                                                readonly></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">PO#</th>
                                                        <td><input type="text" id="info_purchaseordernumber"
                                                                name="PO" class="form-control form-control-sm">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Style</th>
                                                        <td><input type="text" id="info_style" name="Style"
                                                                class="form-control form-control-sm"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Qty Order</th>
                                                        <td><input type="number" id="info_qty_order"
                                                                name="Qty_order" class="form-control form-control-sm"
                                                                placeholder="0"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light"></th>
                                                        <td>
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Pcs</span>
                                                                        <input type="number" id="info_pcs"
                                                                            name="PCS" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Ctn</span>
                                                                        <input type="number" id="info_ctn"
                                                                            name="Ctn" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light"></th>
                                                        <td>
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Less Ctn</span>
                                                                        <input type="number" id="info_less_ctn"
                                                                            name="Less_Ctn" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Pcs Less</span>
                                                                        <input type="number" id="info_pcs_less_ctn"
                                                                            name="Pcs_Less_Ctn" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Carton Weight Std.</th>
                                                        <td><input type="text" id="info_carton_weight"
                                                                name="Carton_weight_std"
                                                                class="form-control form-control-sm"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Pcs Weight Std.</th>
                                                        <td><input type="text" id="info_pcs_weight"
                                                                name="Pcs_weight_std"
                                                                class="form-control form-control-sm"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- KOLOM KANAN -->
                                    <div class="col-12 col-lg-6">
                                        <div class="table-responsive mb-3">
                                            <table class="table table-bordered table-sm align-middle mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th width="40%" class="bg-light">GAC Date</th>
                                                        <td><input type="date" class="form-control form-control-sm"
                                                                id="info_GAC" name="Gac_date"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Destination</th>
                                                        <td><input type="text" class="form-control form-control-sm"
                                                                id="info_FinalDestination" name="Destination"></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="bg-light">Inspector</th>
                                                        <td><input type="text" class="form-control form-control-sm"
                                                                id="info_inspector" name="Inspector"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- TANDA TANGAN -->
                                        <hr class="d-block d-lg-none my-3">
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center align-middle"
                                                style="font-size: 13px;">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="p-2">OPT QC<br><small>TIMBANGAN</small></th>
                                                        <th class="p-2">SPV QC</th>
                                                        <th class="p-2">CHIEF FINISH<br><small>GOOD</small></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr style="height: 100px;">
                                                        <td class="p-1 position-relative align-bottom">
                                                            <input type="text"
                                                                class="form-control-plaintext text-center fw-semibold user-name"
                                                                value="{{ Auth::user()->username ?? '-' }}"
                                                                name="OPT_QC_TIMBANGAN" id="OPT_QC_TIMBANGAN">
                                                        </td>
                                                        <td class="p-1 position-relative align-bottom">
                                                            <input type="text"
                                                                class="form-control-plaintext text-center fw-semibold user-name"
                                                                name="SPV_QC" id="SPV_QC" placeholder="Nama">
                                                        </td>
                                                        <td class="p-1 position-relative align-bottom">
                                                            <input type="text"
                                                                class="form-control-plaintext text-center fw-semibold user-name"
                                                                name="CHIEF_FINISH_GOOD" id="CHIEF_FINISH_GOOD"
                                                                placeholder="Nama">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- ==== BAGIAN TIMBANGAN ==== -->
                                <hr class="my-4">
                                <h5 class="fw-bold mb-3 text-primary">Berat Barang & No. Carton</h5>
                                <hr class="mt-2 mb-3">

                                <div class="row g-3 g-md-4">
                                    <!-- KOLOM KIRI: INPUT DATA -->
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-3">
                                                <!-- No. Carton + Tombol Scan Barcode -->
                                                <div class="mb-3">
                                                    <label for="no_box"
                                                        class="form-label fw-semibold small text-muted">
                                                        No. Carton <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="no_box" id="no_box" placeholder="A001"
                                                            required>
                                                        <button class="btn btn-outline-warning btn-sm" type="button"
                                                            id="btnScanBarcode">
                                                            <i class="fa-solid fa-barcode"></i>
                                                            <span class="d-none d-sm-inline"> Scan</span>
                                                        </button>
                                                    </div>
                                                    <small class="text-muted">Tekan tombol scan atau ketik
                                                        manual</small>
                                                </div>

                                                <div class="row g-2">
                                                    <!-- Rasio Min -->
                                                    <div class="col-6">
                                                        <label for="rasio_batas_beban_min"
                                                            class="form-label fw-semibold small text-muted">
                                                            Batas Min
                                                        </label>
                                                        <input type="number" class="form-control form-control-sm"
                                                            name="rasio_batas_beban_min" id="rasio_batas_beban_min"
                                                            placeholder="0" step="0.01" required>
                                                    </div>
                                                    <!-- Rasio Max -->
                                                    <div class="col-6">
                                                        <label for="rasio_batas_beban_max"
                                                            class="form-label fw-semibold small text-muted">
                                                            Batas Max
                                                        </label>
                                                        <input type="number" class="form-control form-control-sm"
                                                            name="rasio_batas_beban_max" id="rasio_batas_beban_max"
                                                            placeholder="0" step="0.01" required>
                                                    </div>
                                                </div>

                                                <!-- Lost Weight -->
                                                <div class="mt-3 text-center">
                                                    <label
                                                        class="form-label fw-semibold text-success small d-block">Rasio
                                                        Lost Weight</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm text-center bg-light fw-bold"
                                                        style="max-width: 180px; margin: 0 auto; font-size: 0.9rem;"
                                                        name="lost_weight" id="lost_weight" readonly
                                                        placeholder="0.00 kg">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- KOLOM KANAN: DISPLAY BERAT -->
                                    <div class="col-12 col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-3 text-center">
                                                <div class="alert alert-success py-2 mb-3 small">
                                                    <strong>Timbangan Aktif</strong>
                                                </div>

                                                <!-- Berat Real-time -->
                                                <div class="p-3 bg-gradient rounded border shadow-sm"
                                                    style="background: linear-gradient(135deg, #e3f2fd, #bbdefb);">
                                                    <h1 id="currentWeight" class="display-5 fw-bold text-primary mb-0"
                                                        style="font-size: 3.5rem;">
                                                        0.00
                                                    </h1>
                                                    <p class="text-muted small mb-1">Kg</p>
                                                    <small id="previewStatus"
                                                        class="text-warning d-block fw-bold">Menunggu data...</small>
                                                </div>

                                                <!-- Tombol Stabilisasi (Opsional) -->
                                                <div class="mt-3 d-none d-md-block">
                                                    <small class="text-muted">Pastikan timbangan stabil sebelum
                                                        simpan</small>
                                                    <hr>
                                                    <button class="btn btn-sm btn-primary" id="tare">
                                                        <i class="fa-solid fa-thumbtack"></i> Stabilkan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer justify-content-center gap-2 flex-wrap">
                    <button id="btnSimpanTimbang" class="btn btn-success px-4" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fa-solid fa-circle-xmark"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="scannerModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-dark">
                    <h6 class="modal-title fw-bold">
                        <i class="fa-solid fa-camera me-2"></i>Scan Barcode Carton
                    </h6>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4 bg-dark">
                    <div id="reader"
                        style="width: 100%; max-width: 400px; margin: 0 auto; border: 4px solid #0d6efd; border-radius: 12px; overflow: hidden;">
                    </div>

                    <p class="text-white mb-3 fw-semibold mt-3" id="scanStatus">Memuat kamera...</p>

                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-warning btn-sm px-3 d-none" id="torchToggle">
                            <i class="fa-solid fa-lightbulb me-1"></i> Nyalakan Lampu
                        </button>
                        <button type="button" class="btn btn-info btn-sm px-3 d-none" id="switchCamera">
                            <i class="fa-solid fa-camera-rotate me-1"></i> Ganti Kamera
                        </button>
                        <button type="button" class="btn btn-danger btn-sm px-3" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            .ctn-cell {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                line-height: 1.2;
            }

            .ctn-no {
                font-weight: 600;
            }

            .weight {
                font-size: 0.9em;
                color: #555;
            }

            /* Optional: buat tinggi sama seperti header */
            th,
            td {
                vertical-align: middle !important;
                padding: 6px;
            }

            th {
                font-weight: 600;
            }

            #currentWeight {
                font-size: 2.5rem;
                transition: color 0.3s;
            }

            .riwayat-item {
                animation: fadeIn 0.5s;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>
    @endpush

    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('assets/js/sweetalert2/sweetalert2.all.min.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
        <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
        <script>
            // Hari
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

            let currentId = null;
            let pollingInterval = null;
            let latestPreview = null;

            const searchBtn = document.getElementById('searchBtn');
            const spinner = document.getElementById('loadingSpinner');
            const tableBody = document.querySelector('#resultTable tbody');
            const btnSimpan = document.getElementById('btnSimpanTimbang');
            const pagination = document.getElementById('pagination');

            // === CARI DATA ===
            searchBtn.addEventListener('click', () => fetchData(1));

            async function fetchData(page = 1) {
                const search = document.getElementById('search').value.trim();
                const start = document.getElementById('start_date').value;
                const end = document.getElementById('end_date').value;

                if (!search && !start && !end) {
                    Swal.fire('Peringatan', 'Isi setidaknya satu kolom!', 'warning');
                    return;
                }

                spinner.style.display = 'inline-block';
                tableBody.innerHTML = `<tr><td colspan="9" class="text-center">Memuat...</td></tr>`;
                pagination.innerHTML = '';

                try {
                    const params = new URLSearchParams({
                        page
                    });
                    if (search) params.append('search', search);
                    if (start) params.append('start_date', start);
                    if (end) params.append('end_date', end);

                    const res = await fetch(`/api/ordersheet?${params}`);
                    const json = await res.json();

                    spinner.style.display = 'none';

                    if (json.success && json.data.length > 0) {
                        renderTable(json.data, json.current_page);
                        renderPagination(json.current_page, json.last_page);
                    } else {
                        tableBody.innerHTML =
                            `<tr><td colspan="9" class="text-warning text-center">Tidak ditemukan</td></tr>`;
                    }
                } catch (err) {
                    spinner.style.display = 'none';
                    tableBody.innerHTML = `<tr><td colspan="9" class="text-danger text-center">Terjadi kesalahan</td></tr>`;
                    console.error(err);
                }
            }

            function renderTable(data, currentPage) {
                let rows = '';
                data.forEach((item, i) => {
                    rows += `
                    <tr>
                        <td>${(i + 1) + (currentPage - 1) * 10}</td>
                        <td>${item.Buyer || '-'}</td>
                        <td>${item.PurchaseOrderNumber || '-'}</td>
                        <td>${item.ProductName || '-'}</td>
                        <td>${item.Qty || 0}</td>
                        <td>${item.ActualFOB || '-'}</td>
                        <td>${item.DocumentDate || '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary btn-timbang" data-item='${JSON.stringify(item)}'>
                                <i class="fa-solid fa-weight-scale"></i> Timbang
                            </button>
                        </td>
                    </tr>`;
                });
                tableBody.innerHTML = rows;
            }

            function renderPagination(currentPage, lastPage) {
                if (lastPage <= 1) {
                    pagination.innerHTML = '';
                    return;
                }

                let html = `<ul class="pagination">`;
                html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                    </li>`;

                for (let i = 1; i <= lastPage; i++) {
                    html += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>`;
                }

                html += `<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                </li>`;
                html += `</ul>`;

                pagination.innerHTML = html;

                pagination.querySelectorAll('a[data-page]').forEach(link => {
                    link.addEventListener('click', e => {
                        e.preventDefault();
                        const page = parseInt(e.target.dataset.page);
                        if (page > 0 && page <= lastPage) fetchData(page);
                    });
                });
            }

            document.addEventListener('click', async function(e) {
                if (e.target.closest('.btn-timbang')) {
                    const btn = e.target.closest('.btn-timbang');
                    let item;
                    try {
                        item = JSON.parse(btn.dataset.item);
                    } catch (err) {
                        return;
                    }

                    currentId = item.id;

                    // === ISI FIELD ===
                    const fields = {
                        info_buyer: 'Buyer',
                        info_order_code: 'Order_code',
                        info_purchaseordernumber: 'PurchaseOrderNumber',
                        info_style: 'ProductName',
                        info_qty_order: 'Qty',
                        info_pcs: 'Pcs',
                        info_ctn: 'Ctn',
                        info_less_ctn: 'Less_ctn',
                        info_pcs_less_ctn: 'Pcs_less_ctn',
                        info_carton_weight: 'Carton_weight_std',
                        info_pcs_weight: 'Pcs_weight_std',
                        info_GAC: 'GAC',
                        info_FinalDestination: 'FinalDestination',
                    };

                    Object.keys(fields).forEach(id => {
                        const el = document.getElementById(id);
                        const key = fields[id];
                        const value = item[key];
                        if (el) {
                            el.value = value ?? '';
                            if (id === 'info_GAC' && value) {
                                el.value = formatDateForInput(value);
                            }
                        }
                    });

                    const minEl = document.getElementById('rasio_batas_beban_min');
                    const maxEl = document.getElementById('rasio_batas_beban_max');
                    const lostEl = document.getElementById('lost_weight');
                    if (minEl) minEl.value = item.rasio_min ?? '';
                    if (maxEl) maxEl.value = item.rasio_max ?? '';
                    if (lostEl) lostEl.value = '';

                    resetPreviewUI();

                    // === Siapkan modal ===
                    const modalElement = document.getElementById('timbangModal');
                    const modal = new bootstrap.Modal(modalElement);

                    // Hentikan polling saat modal ditutup
                    modalElement.addEventListener('hidden.bs.modal', stopPolling, {
                        once: true
                    });

                    // Jalankan setelah modal benar-benar terbuka
                    modalElement.addEventListener('shown.bs.modal', async () => {
                        try {
                            // Set ID aktif di server
                            await fetch('/api/timbang/set-id', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    id: currentId
                                })
                            });
                        } catch (err) {
                            console.warn('Gagal set-id:', err);
                        }

                        // Jalankan langsung polling pertama tanpa delay
                        await loadPreview();
                        await loadRiwayat();
                        hitungLossWeight();

                        // Baru setelah itu, mulai interval polling
                        startPolling();
                    }, {
                        once: true
                    });

                    modal.show();
                }
            });

            // ==== POLLING SYSTEM ====
            function startPolling() {
                stopPolling();
                pollingInterval = setInterval(() => {
                    loadPreview();
                    loadRiwayat();
                }, 1000); // beri jeda 1,5 detik agar server tidak overload
            }

            function stopPolling() {
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // ==== RESET UI ====
            function resetPreviewUI() {
                document.getElementById('currentWeight').textContent = '0.00 kg';
                const status = document.getElementById('previewStatus');
                status.textContent = 'Menunggu timbangan...';
                status.className = 'text-warning';
                status.style.fontWeight = 'bold'; // ← Tambahan untuk teks tebal
                document.getElementById('lost_weight').value = '';
                btnSimpan.disabled = true;
                latestPreview = null;
            }

            // === FUNGSI BANTU: FORMAT TANGGAL UNTUK <input type="date"> ===
            function formatDateForInput(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                if (isNaN(date)) {
                    console.warn('Tanggal tidak valid:', dateStr);
                    return '';
                }
                return date.toISOString().split('T')[0]; // YYYY-MM-DD
            }

            // === LOAD PREVIEW TIMBANGAN ===
            async function loadPreview() {
                if (!currentId) {
                    console.warn('currentId belum ada, skip polling');
                    return;
                }

                try {
                    const url = `/api/timbang/preview/${currentId}`;
                    console.log('Polling:', url); // DEBUG

                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!res.ok) {
                        console.error('HTTP Error:', res.status);
                        return;
                    }

                    const json = await res.json();
                    console.log('Response preview:', json); // DEBUG

                    if (!json.success) {
                        console.warn('Preview gagal:', json);
                        return;
                    }

                    const berat = parseFloat(json.berat) || 0;
                    const weightEl = document.getElementById('currentWeight');
                    const newText = `${berat.toFixed(2)} kg`;

                    if (weightEl.textContent !== newText) {
                        weightEl.textContent = newText;
                        weightEl.style.transition = 'all 0.3s ease';
                        weightEl.style.transform = 'scale(1.15)';
                        weightEl.style.color = '#ff4500';
                        setTimeout(() => {
                            weightEl.style.transform = 'scale(1)';
                            weightEl.style.color = '#0d6efd';
                        }, 300);
                    }

                    const statusEl = document.getElementById('previewStatus');
                    if (berat < 0.05) {
                        statusEl.textContent = 'Timbangan kosong';
                        statusEl.className = 'text-muted';
                    } else {
                        statusEl.textContent = 'Berat diterima dari timbangan!';
                        statusEl.className = 'text-success fw-bold';
                    }

                    btnSimpanTimbang.disabled = berat < 0.05;
                    latestPreview = {
                        berat: berat.toFixed(2)
                    };
                    hitungLossWeight();

                } catch (err) {
                    console.error('Error loadPreview:', err);
                }
            }

            // === HITUNG LOSS WEIGHT (OTOMATIS) ===
            function hitungLossWeight() {
                const minInput = document.getElementById('rasio_batas_beban_min');
                const maxInput = document.getElementById('rasio_batas_beban_max');
                const lostWeightField = document.getElementById('lost_weight');
                const statusEl = document.getElementById('previewStatus');
                const currentWeightText = document.getElementById('currentWeight').textContent;
                const current = parseFloat(currentWeightText) || 0;
                const min = parseFloat(minInput?.value) || 0;
                const max = parseFloat(maxInput?.value) || 0;

                if (!min || !max || current === 0) {
                    lostWeightField.value = '';
                    return;
                }

                const lossWeight = (max - current).toFixed(2);
                const ratio = ((current - min) / (max - min)).toFixed(3);
                lostWeightField.value = `${lossWeight} kg (${ratio})`;

                if (current < min) {
                    statusEl.textContent = 'Berat di bawah batas minimal!';
                    statusEl.className = 'text-danger fw-bold';
                } else if (current > max) {
                    statusEl.textContent = 'Berat melebihi batas maksimal!';
                    statusEl.className = 'text-danger fw-bold';
                } else {
                    statusEl.textContent = 'Berat dalam batas normal';
                    statusEl.className = 'text-success fw-bold';
                }
            }

            // Jalankan hitung otomatis saat input min/max berubah
            document.addEventListener('DOMContentLoaded', () => {
                ['rasio_batas_beban_min', 'rasio_batas_beban_max'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.addEventListener('input', hitungLossWeight);
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const scanButton = document.getElementById('btnScanBarcode');
                if (!scanButton) return;

                let scannerInstance = null;

                function isMobile() {
                    return /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent);
                }

                function startScanner() {
                    const modalEl = document.getElementById('scannerModal');
                    if (!modalEl) return alert('Modal scanner tidak ditemukan!');

                    const modal = new bootstrap.Modal(modalEl, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    const statusEl = document.getElementById('scanStatus');
                    const torchBtn = document.getElementById('torchToggle');
                    const switchBtn = document.getElementById('switchCamera');

                    let currentCamera = 'environment';
                    let torchOn = false;
                    const mobile = isMobile();

                    modal.show();

                    const onSuccess = (decodedText) => {
                        const text = decodedText.trim();
                        if (!text) return;

                        const noBoxInput = document.getElementById('no_box');
                        if (noBoxInput) {
                            noBoxInput.value = text;
                            noBoxInput.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                        }

                        statusEl.innerHTML =
                            `<span class="text-success fw-bold">✓ Berhasil Scan!</span><br><small class="text-light">${text}</small>`;
                        setTimeout(() => {
                            stopScanner();
                            modal.hide();
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Scan Berhasil!',
                                    text: text,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                alert(`Scan berhasil: ${text}`);
                            }
                        }, 800);
                    };

                    const onError = () => {};

                    const stopScanner = () => {
                        if (scannerInstance) {
                            scannerInstance.stop().catch(() => {});
                            scannerInstance = null;
                        }
                        torchOn = false;
                    };

                    modalEl.addEventListener('shown.bs.modal', () => {
                        statusEl.textContent = 'Memuat kamera...';
                        torchBtn.disabled = true;
                        torchBtn.classList.add('d-none');

                        const html5QrCode = new Html5Qrcode("reader");

                        const config = {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            },
                            aspectRatio: 1,
                            disableFlip: false,
                            formatsToSupport: [
                                Html5QrcodeSupportedFormats.CODE_128,
                                Html5QrcodeSupportedFormats.CODE_39,
                                Html5QrcodeSupportedFormats.EAN_13,
                                Html5QrcodeSupportedFormats.EAN_8,
                                Html5QrcodeSupportedFormats.UPC_A
                            ],
                            useBarCodeDetectorIfSupported: false
                        };

                        html5QrCode.start({
                                facingMode: currentCamera
                            }, config, onSuccess, onError)
                            .then(() => {
                                scannerInstance = html5QrCode;
                                statusEl.innerHTML =
                                    '<span class="text-info">🎯 Arahkan kamera ke barcode...</span>';

                                if (mobile) {
                                    torchBtn.classList.remove('d-none');
                                    torchBtn.disabled = false;
                                    setupTorch();
                                }

                                Html5Qrcode.getCameras().then(cameras => {
                                    if (cameras && cameras.length > 1) {
                                        switchBtn.classList.remove('d-none');
                                    }
                                }).catch(() => {});

                                switchBtn.onclick = () => {
                                    currentCamera = currentCamera === 'environment' ? 'user' :
                                        'environment';
                                    stopScanner();
                                    setTimeout(() => {
                                        html5QrCode.start({
                                                facingMode: currentCamera
                                            }, config, onSuccess, onError)
                                            .then(() => scannerInstance = html5QrCode);
                                    }, 500);
                                };
                            })
                            .catch(err => {
                                console.error('Error start scanner:', err);
                                statusEl.innerHTML =
                                    `<span class="text-danger">❌ Gagal akses kamera:<br><small>${err.message || err}</small></span>`;
                            });
                    });

                    function setupTorch() {
                        torchBtn.onclick = () => {
                            if (!scannerInstance) return;
                            torchOn = !torchOn;
                            scannerInstance.applyVideoConstraints({
                                    advanced: [{
                                        torch: torchOn
                                    }]
                                })
                                .then(() => {
                                    torchBtn.innerHTML = torchOn ? '💡 Matikan Lampu' : '💡 Nyalakan Lampu';
                                    torchBtn.classList.toggle('btn-danger', torchOn);
                                    torchBtn.classList.toggle('btn-warning', !torchOn);
                                }).catch(() => {
                                    torchOn = false;
                                    torchBtn.innerHTML = '🚫 Lampu Tidak Didukung';
                                    torchBtn.className = 'btn btn-secondary btn-sm px-3';
                                    torchBtn.disabled = true;
                                });
                        };
                    }

                    modalEl.addEventListener('hidden.bs.modal', () => stopScanner(), {
                        once: true
                    });
                }

                scanButton.addEventListener('click', startScanner);
            });

            btnSimpan.addEventListener('click', async () => {
                if (!latestPreview || !currentId) return;

                const form = document.getElementById('formOrdersheet');
                const formData = new FormData(form);

                formData.set('berat', latestPreview.berat);
                formData.set('id', currentId);

                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

                try {
                    const res = await fetch('/api/timbang/simpan', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    });

                    const json = await res.json();

                    if (res.ok && json.success) {
                        // Tampilkan alert sukses
                        Swal.fire({
                            title: 'Sukses!',
                            text: json.message,
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        // Refresh data riwayat di tabel
                        await loadRiwayat();
                        resetPreviewUI();

                        // Tutup modal timbang
                        const modal = bootstrap.Modal.getInstance(document.getElementById('timbangModal'));
                        modal.hide();

                        // Tunggu sedikit agar modal tertutup dahulu, lalu refresh halaman
                        setTimeout(() => {
                            location.reload();
                        }, 1000);

                    } else {
                        Swal.fire('Gagal', json.message || 'Terjadi kesalahan', 'error');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    Swal.fire('Error', 'Koneksi gagal. Periksa internet atau URL API.', 'error');
                } finally {
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan';
                }
            });
        </script>
    @endpush

</x-layout.home>

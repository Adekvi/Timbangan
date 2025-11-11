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
                    {{-- <div class="tambah mb-2">
                        <a href="{{ url('user/ordersheet-view/create') }}" class="btn btn-info">
                            <i class="fas fa-plus"></i> Tambah Data
                        </a>
                    </div>
                    <hr> --}}

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
                                Belum Ada Data
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

    {{-- MODAL DETAIL & RIWAYAT TIMBANGAN --}}
    <div class="modal fade" id="timbangModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-dark">
                    <h5 class="modal-title">Detail Ordersheet & Laporan</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="formOrdersheet" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="info_id" name="id">
                                <input type="hidden" name="berat" id="hiddenBerat" value="0">
                                <h5 class="fw-bold mb-3">Informasi Ordersheet</h5>
                                <hr>
                                <div class="ordersheet">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle mb-0">
                                                    <tr>
                                                        <th width="160">BUYER</th>
                                                        <td>
                                                            <input type="text" id="info_buyer" name="Buyer"
                                                                class="form-control">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Order No.</th>
                                                        <td>
                                                            <input type="text" id="info_order_code"
                                                                name="Order_code" class="form-control" readonly>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>PO#</th>
                                                        <td>
                                                            <input type="text" id="info_purchaseordernumber"
                                                                name="PO" class="form-control">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Style</th>
                                                        <td>
                                                            <input type="text" id="info_style" name="Style"
                                                                class="form-control">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Qty Order</th>
                                                        <td>
                                                            <input type="number" id="info_qty_order"
                                                                name="Qty_order" class="form-control"
                                                                placeholder="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <td>
                                                            <div class="row g-2">
                                                                <div class="col">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Pcs</span>
                                                                        <input type="number" id="info_pcs"
                                                                            name="PCS" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="col">
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
                                                        <th></th>
                                                        <td>
                                                            <div class="row g-2">
                                                                <div class="col">
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">Less Ctn</span>
                                                                        <input type="number" id="info_less_ctn"
                                                                            name="Less_Ctn" class="form-control"
                                                                            placeholder="0">
                                                                    </div>
                                                                </div>
                                                                <div class="col">
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
                                                    <!-- BARIS 7: CARTON & PCS WEIGHT STD -->
                                                    <tr>
                                                        <th>Carton Weight Std.</th>
                                                        <td>
                                                            <input type="text" id="info_carton_weight"
                                                                name="Carton_weight_std" class="form-control">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Pcs Weight Std.</th>
                                                        <td>
                                                            <input type="text" id="info_pcs_weight"
                                                                name="Pcs_weight_std" class="form-control">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle mb-0">
                                                    <tr>
                                                        <th>GAC Date</th>
                                                        <td>
                                                            <input type="date" class="form-control" id="info_GAC"
                                                                name="Gac_date">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Destination</th>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                id="info_FinalDestination" name="Destination">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Inspector</th>
                                                        <td>
                                                            <input type="text" class="form-control"
                                                                id="info_inspector" name="Inspector">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <hr>
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered text-center align-middle"
                                                    style="font-family: Arial, sans-serif; font-size: 13px;">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="33%">OPT QC<br>TIMBANGAN</th>
                                                            <th width="33%">SPV QC</th>
                                                            <th width="34%">CHIEF FINISH<br>GOOD</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr style="height: 120px;">
                                                            <!-- OPT QC TIMBANGAN -->
                                                            <td class="position-relative align-bottom">
                                                                <div class="d-flex flex-column justify-content-end align-items-center"
                                                                    style="height:100px;">
                                                                    <!-- Input username otomatis -->
                                                                    <input type="text"
                                                                        class="form-control-plaintext text-center fw-semibold user-name"
                                                                        value="{{ Auth::user()->username ?? '-' }}"
                                                                        style="width: 100%;" name="OPT_QC_TIMBANGAN"
                                                                        id="OPT_QC_TIMBANGAN">
                                                                </div>
                                                            </td>

                                                            <!-- SPV QC -->
                                                            <td class="align-bottom">
                                                                <div class="d-flex flex-column justify-content-end align-items-center"
                                                                    style="height:100px;">
                                                                    <input type="text"
                                                                        class="form-control-plaintext text-center fw-semibold user-name"
                                                                        name="SPV_QC" id="SPV_QC"
                                                                        placeholder="Nama" style="width: 100%;">
                                                                </div>
                                                            </td>

                                                            <!-- CHIEF FINISH GOOD -->
                                                            <td class="align-bottom">
                                                                <div class="d-flex flex-column justify-content-end align-items-center"
                                                                    style="height:100px;">
                                                                    <input type="text"
                                                                        class="form-control-plaintext text-center fw-semibold user-name"
                                                                        name="CHIEF_FINISH_GOOD"
                                                                        id="CHIEF_FINISH_GOOD" placeholder="Nama"
                                                                        style="width: 100%;">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="timbangan mt-3">
                                    <h5 class="fw-bold mb-3">Berat Barang & No. Carton</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_box" class="fw-semibold">No. Carton</label>
                                                <input type="text" class="form-control mt-2 mb-2" name="no_box"
                                                    id="no_box" placeholder="No. Carton (A001)">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="rasio_batas_beban_min" class="fw-semibold">Rasio
                                                            Batas Beban Minimal</label>
                                                        <input type="number" class="form-control mt-2"
                                                            name="rasio_batas_beban_min" id="rasio_batas_beban_min"
                                                            placeholder="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="rasio_batas_beban_max" class="fw-semibold">Rasio
                                                            Batas Beban Maksimal</label>
                                                        <input type="number" class="form-control mt-2"
                                                            name="rasio_batas_beban_max" id="rasio_batas_beban_max"
                                                            placeholder="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="d-flex justify-content-center">
                                                        <div class="form-group mb-3"
                                                            style="max-width: 300px; width: 100%;">
                                                            <label for="lost_weight"
                                                                class="fw-semibold text-center d-block">
                                                                Rasio Lost Weight
                                                            </label>
                                                            <input type="text"
                                                                class="form-control mt-2 text-center bg-white"
                                                                name="lost_weight" id="lost_weight" readonly
                                                                placeholder="1.30 kg (0.448)">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-success text-center mt-3 py-2">
                                                <strong>Timbangan aktif!</strong>
                                            </div>
                                            <div class="text-center p-3 bg-light rounded border">
                                                <h4 id="currentWeight" class="text-primary fw-bold">0.00</h4>
                                                <p class="text-muted mb-0 small">Berat terakhir</p>
                                                <small class="text-warning d-block mt-1" id="previewStatus">Menunggu
                                                    data...</small>
                                            </div>
                                            <div class="mt-3 text-center">
                                                <button id="btnSimpanTimbang" class="btn btn-success btn-sm" disabled>
                                                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-circle-xmark"></i> Tutup
                    </button>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            let previewAbortController = null;

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

                    // === ISI SEMUA FIELD TERMASUK RASIO ===
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

                    // Isi field biasa
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

                    // === ISI RASIO MIN & MAX DARI DATA ===
                    const minEl = document.getElementById('rasio_batas_beban_min');
                    const maxEl = document.getElementById('rasio_batas_beban_max');
                    const lostEl = document.getElementById('lost_weight');

                    if (minEl) minEl.value = item.rasio_min ?? '';
                    if (maxEl) maxEl.value = item.rasio_max ?? '';
                    if (lostEl) lostEl.value = ''; // Kosongkan lost weight dulu

                    // Reset preview
                    resetPreviewUI();

                    // Buka modal
                    const modalElement = document.getElementById('timbangModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    // Set ID ke server + mulai polling
                    try {
                        await fetch('/api/timbang/set-id', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                id: currentId
                            })
                        });
                    } catch (err) {}

                    modalElement.addEventListener('shown.bs.modal', () => {
                        startPolling(); // hanya berat
                        loadRiwayat(); // riwayat hanya sekali
                        hitungLossWeight();
                    }, {
                        once: true
                    });
                }
            });

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
                if (!currentId) return;

                if (previewAbortController) {
                    previewAbortController.abort(); // batalkan request sebelumnya
                }
                previewAbortController = new AbortController();
                const signal = previewAbortController.signal;

                try {
                    const res = await fetch(`/api/timbang/preview/${currentId}`, {
                        signal
                    });
                    if (!res.ok) return;

                    const json = await res.json();
                    if (!json.success) return;

                    const berat = parseFloat(json.berat) || 0;
                    const weightEl = document.getElementById('currentWeight');
                    const statusEl = document.getElementById('previewStatus');
                    const newText = `${berat.toFixed(2)} kg`;

                    if (weightEl.textContent !== newText) {
                        weightEl.textContent = newText;
                    }

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
                    if (err.name !== 'AbortError') console.error('Error loadPreview:', err);
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

            // === LOAD RIWAYAT (DIBUAT BARU) ===
            async function loadRiwayat() {
                if (!currentId) return;

                try {
                    const res = await fetch(`/api/timbang/riwayat/${currentId}`);
                    const json = await res.json();

                    const tbody = document.querySelector('#cartonWeightTable tbody');
                    if (!tbody) return;

                    tbody.innerHTML = '';

                    if (json.success && json.data.length > 0) {
                        // Kelompokkan data berdasarkan tanggal (misalnya created_at)
                        const grouped = {};
                        json.data.forEach(item => {
                            const date = item.created_at ? item.created_at.split(' ')[0] : 'Unknown';
                            if (!grouped[date]) grouped[date] = [];
                            grouped[date].push(item.berat);
                        });

                        Object.entries(grouped).forEach(([date, weights]) => {
                            // Buat baris baru
                            const total = weights.reduce((a, b) => a + parseFloat(b || 0), 0);
                            const pad = Array(10).fill('-'); // isi 10 kolom
                            weights.forEach((w, i) => {
                                if (i < 10) pad[i] = w;
                            });

                            const row1 = document.createElement('tr');
                            row1.innerHTML = `
                                <td rowspan="2" style="vertical-align: middle;">${date}</td>
                                ${pad.map(() => '<td>Ctn</td>').join('')}
                                <td rowspan="2">${total.toFixed(2)}</td>
                                <td rowspan="2"></td>
                            `;

                            const row2 = document.createElement('tr');
                            row2.classList.add('weight-row');
                            row2.innerHTML = pad.map(w => `<td>${w}</td>`).join('');

                            tbody.appendChild(row1);
                            tbody.appendChild(row2);
                        });

                    } else {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="13" class="text-center text-muted py-3">
                                Belum ada data timbangan
                            </td>
                        </tr>`;
                    }
                } catch (err) {
                    console.warn('Gagal load riwayat:', err);
                }
            }

            // === RESET, POLLING, SIMPAN ===
            function resetPreviewUI() {
                document.getElementById('currentWeight').textContent = '0.00 kg';
                document.getElementById('previewStatus').textContent = 'Menunggu timbangan...';
                document.getElementById('previewStatus').className = 'text-warning';
                document.getElementById('lost_weight').value = '';
                btnSimpan.disabled = true;
                btnSimpan.innerHTML = 'Simpan';
                latestPreview = null;
            }

            function startPolling() {
                stopPolling();

                // Load pertama langsung tanpa tunggu 800ms
                loadPreview();

                // Interval lebih ringan, misal 1000 ms
                pollingInterval = setInterval(loadPreview, 1000);
            }

            function stopPolling() {
                if (pollingInterval) clearInterval(pollingInterval);
            }

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

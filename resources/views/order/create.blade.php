<x-layout.home title="Tambah Ordersheet">

    <div class="page-content">
        <section class="row">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('user/ordersheet/store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="info_id" name="id">
                        <input type="hidden" name="berat" id="hidden_berat">
                        <h5 class="fw-bold mb-3">Informasi Ordersheet</h5>
                        <div class="tambah-ordersheet">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0">
                                            <tr>
                                                <th width="105">BUYER</th>
                                                <td>
                                                    <input type="text" id="BUYER" name="Buyer"
                                                        class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Order No.</th>
                                                <td>
                                                    <input type="text" id="Order_code" name="Order_code"
                                                        class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>PO#</th>
                                                <td>
                                                    <input type="text" id="PO" name="PO"
                                                        class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Style</th>
                                                <td>
                                                    <input type="text" id="Style" name="Style"
                                                        class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Qty Order</th>
                                                <td>
                                                    <input type="number" id="Qty_order" name="Qty_order"
                                                        class="form-control" placeholder="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <td>
                                                    <div class="row g-2">
                                                        <div class="col">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Pcs</span>
                                                                <input type="number" id="PCS" name="PCS"
                                                                    class="form-control" placeholder="0">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Ctn</span>
                                                                <input type="number" id="Ctn" name="Ctn"
                                                                    class="form-control" placeholder="0">
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
                                                                <input type="number" id="Less_Ctn" name="Less_Ctn"
                                                                    class="form-control" placeholder="0">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">Pcs Less</span>
                                                                <input type="number" id="Pcs_Less_Ctn"
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
                                                    <input type="text" id="Carton_weight_std"
                                                        name="Carton_weight_std" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pcs Weight Std.</th>
                                                <td>
                                                    <input type="text" id="Pcs_weight_std" name="Pcs_weight_std"
                                                        class="form-control">
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
                                                    <input type="date" class="form-control" id="Gac_date"
                                                        name="Gac_date">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Destination</th>
                                                <td>
                                                    <input type="text" class="form-control" id="Destination"
                                                        name="Destination">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Inspector</th>
                                                <td>
                                                    <input type="text" class="form-control" id="Inspector"
                                                        name="Inspector">
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
                                                                name="SPV_QC" id="SPV_QC" placeholder="Nama"
                                                                style="width: 100%;">
                                                        </div>
                                                    </td>

                                                    <!-- CHIEF FINISH GOOD -->
                                                    <td class="align-bottom">
                                                        <div class="d-flex flex-column justify-content-end align-items-center"
                                                            style="height:100px;">
                                                            <input type="text"
                                                                class="form-control-plaintext text-center fw-semibold user-name"
                                                                name="CHIEF_FINISH_GOOD" id="CHIEF_FINISH_GOOD"
                                                                placeholder="Nama" style="width: 100%;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="timbangan mt-3">
                            <h5 class="fw-bold mb-3">Berat Barang & No. Carton</h5>
                            <hr>
                            <div class="row">
                                <!-- Kiri: Input Rasio & No Box -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="no_box" class="fw-semibold">No. Carton</label>
                                        <input type="text" class="form-control mt-2 mb-2" name="no_box"
                                            id="no_box" placeholder="No. Carton (A001)">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="rasio_batas_beban_min" class="fw-semibold">Rasio Batas
                                                Minimal</label>
                                            <input type="number" step="0.01" class="form-control mt-2"
                                                name="rasio_batas_beban_min" id="rasio_batas_beban_min"
                                                value="12.50" placeholder="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="rasio_batas_beban_max" class="fw-semibold">Rasio Batas
                                                Maksimal</label>
                                            <input type="number" step="0.01" class="form-control mt-2"
                                                name="rasio_batas_beban_max" id="rasio_batas_beban_max"
                                                value="13.80" placeholder="0">
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="d-flex justify-content-center">
                                                <div style="max-width: 300px; width: 100%;">
                                                    <label class="fw-semibold text-center d-block">Rasio Lost
                                                        Weight</label>
                                                    <input type="text"
                                                        class="form-control text-center bg-white fw-bold"
                                                        id="lost_weight" readonly value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kanan: Timbangan Real-time -->
                                <div class="col-md-6">
                                    <div class="alert alert-success text-center py-2">
                                        <strong>Timbangan ESP32 Aktif!</strong>
                                    </div>
                                    <div class="text-center p-4 bg-light rounded border shadow-sm">
                                        <h3 id="currentWeight" class="text-primary fw-bold">0.00 kg</h3>
                                        <p class="text-muted small mb-1">Berat Real-time dari ESP32</p>
                                        <small id="previewStatus" class="text-muted">Menunggu data
                                            timbangan...</small>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <button type="button" id="btnSimpanTimbang" class="btn btn-success btn-sm"
                                            disabled>
                                            Simpan Berat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-end">
                            <a href="{{ url('user/ordersheet-view') }}" class="btn btn-secondary btn-sm">Kembali</a>
                            <button type="submit" class="btn btn-primary btn-sm">Simpan Ordersheet +
                                Timbangan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    @push('css')
    @endpush

    @push('js')
        <script>
            // === HANYA JALAN DI HALAMAN CREATE ===
            if (window.location.pathname.includes('ordersheet/create') || window.location.pathname.includes(
                'ordersheet/store')) {

                let currentId = null;
                let pollingInterval = null;
                let latestBerat = 0;

                // Ambil ID dari hidden input (nanti di-set dari modal atau manual)
                const infoIdInput = document.getElementById('info_id');

                // Update tampilan berat + hitung loss weight
                function updateBerat(berat) {
                    latestBerat = berat;
                    document.getElementById('currentWeight').textContent = berat.toFixed(2) + ' kg';
                    document.getElementById('hidden_berat').value = berat.toFixed(2);
                    document.getElementById('btnSimpanTimbang').disabled = false;
                    document.getElementById('previewStatus').textContent = 'Berat diterima!';
                    document.getElementById('previewStatus').className = 'text-success fw-bold';
                    hitungLossWeight();
                }

                // Hitung Lost Weight REAL-TIME
                function hitungLossWeight() {
                    const min = parseFloat(document.getElementById('rasio_batas_beban_min').value) || 0;
                    const max = parseFloat(document.getElementById('rasio_batas_beban_max').value) || 0;
                    const current = latestBerat;
                    const lostEl = document.getElementById('lost_weight');
                    const statusEl = document.getElementById('previewStatus');

                    if (min === 0 || max === 0 || current === 0) {
                        lostEl.value = '';
                        return;
                    }

                    const loss = (max - current).toFixed(2);
                    const ratio = ((current - min) / (max - min)).toFixed(3);
                    lostEl.value = `${loss} kg (${ratio})`;

                    if (current < min) {
                        statusEl.textContent = 'Di bawah batas minimal!';
                        statusEl.className = 'text-danger fw-bold';
                    } else if (current > max) {
                        statusEl.textContent = 'Melebihi batas maksimal!';
                        statusEl.className = 'text-danger fw-bold';
                    } else {
                        statusEl.textContent = 'Berat normal';
                        statusEl.className = 'text-success fw-bold';
                    }
                }

                // Load preview dari ESP32
                async function loadPreview() {
                    if (!currentId) return;
                    try {
                        const res = await fetch(`/api/timbang/preview/${currentId}`);
                        const json = await res.json();
                        if (json.success && json.berat > 0) {
                            updateBerat(parseFloat(json.berat));
                        }
                    } catch (err) {
                        console.warn('Preview gagal:', err);
                    }
                }

                // Start polling
                function startPolling() {
                    stopPolling();
                    pollingInterval = setInterval(loadPreview, 1000);
                }

                function stopPolling() {
                    if (pollingInterval) clearInterval(pollingInterval);
                }

                // Set ID & mulai polling (dipanggil dari modal index)
                window.setTimbangId = function(id) {
                    currentId = id;
                    document.getElementById('info_id').value = id;
                    startPolling();
                    loadPreview();
                };

                // Event listener untuk rasio
                document.getElementById('rasio_batas_beban_min').addEventListener('input', hitungLossWeight);
                document.getElementById('rasio_batas_beban_max').addEventListener('input', hitungLossWeight);

                // Tombol simpan berat
                document.getElementById('btnSimpanTimbang').addEventListener('click', () => {
                    Swal.fire('Sukses!', `Berat ${latestBerat.toFixed(2)} kg telah disimpan ke form!`, 'success');
                    document.getElementById('btnSimpanTimbang').innerHTML = 'Tersimpan!';
                    document.getElementById('btnSimpanTimbang').disabled = true;
                });

                // Inisialisasi
                document.addEventListener('DOMContentLoaded', () => {
                    hitungLossWeight();
                    // Jika ID sudah ada (dari URL atau session), langsung polling
                    if (infoIdInput.value) {
                        currentId = infoIdInput.value;
                        startPolling();
                    }
                });

                // Hentikan polling saat keluar halaman
                window.addEventListener('beforeunload', stopPolling);
            }
        </script>
    @endpush

</x-layout.home>

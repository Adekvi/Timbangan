<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable modal-fullscreen-lg-down">
        <div class="modal-content overflow-hidden">
            <!-- Header -->
            <div class="modal-header text-dark">
                <h5 class="modal-title" id="tambahLabel">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Ordersheet Package
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-3 p-md-4">
                <form id="formOrdersheet" method="POST">
                    @csrf
                    <input type="hidden" name="weight" id="weight">

                    <!-- Bagian Informasi Package -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary fw-bold">Informasi Package</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Package <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Leather Type</label>
                                    <input type="text" name="leather_type" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Color</label>
                                    <input type="text" name="color" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Size</label>
                                    <input type="text" name="size" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Stitching Type</label>
                                    <input type="text" name="stitching_type" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lining Material</label>
                                    <input type="text" name="lining_material" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi</label>
                                    <textarea name="description" rows="4" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Berat Barang -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary fw-bold">Berat Barang</h5>

                            <div class="row g-4">
                                <!-- Kolom Kiri: Input Rasio & Lost Weight -->
                                <div class="col-12 col-lg-6">
                                    <div class="h-80 d-flex flex-column gap-3">
                                        <div>
                                            <label class="form-label fw-semibold small text-muted">No. Package</label>
                                            <input type="text" name="no_package" id="no_package" class="form-control"
                                                placeholder="Masukkan nomor package" required>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small text-muted">Batas Min
                                                    (g)</label>
                                                <input type="number" step="0.01" name="rasio_batas_beban_min"
                                                    id="rasio_batas_beban_min" class="form-control" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small text-muted">Batas Max
                                                    (g)</label>
                                                <input type="number" step="0.01" name="rasio_batas_beban_max"
                                                    id="rasio_batas_beban_max" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="text-center mt-auto">
                                            <label class="form-label fw-semibold text-success small d-block">Rasio Lost
                                                Weight</label>
                                            <input type="text" name="lost_weight" id="lost_weight"
                                                class="form-control form-control-sm text-center bg-light fw-bold"
                                                style="max-width: 180px; margin: 0 auto; font-size: 0.9rem;" readonly
                                                placeholder="0.00 gram (0.000)">
                                        </div>
                                    </div>
                                </div>

                                <!-- Kolom Kanan: Display Timbangan Real-time -->
                                <div class="col-12 col-lg-6">
                                    <div class="h-80 d-flex flex-column">
                                        <div class="alert alert-success py-2 small text-center">
                                            <i class="fa-solid fa-scale-balanced me-1"></i> <strong>Timbangan
                                                Aktif</strong>
                                        </div>

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                            <div class="text-center p-4 bg-gradient rounded-4 shadow-sm"
                                                style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); min-width: 260px;">
                                                <h1 id="currentWeight" class="display-4 fw-bold text-primary mb-0">
                                                    0.00
                                                </h1>
                                                <p class="text-muted mb-1">Gram</p>
                                                <small id="previewStatus" class="text-warning fw-bold d-block">
                                                    Menunggu data...
                                                </small>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <small class="text-muted d-block mt-2 mb-2">Pastikan timbangan stabil
                                                sebelum
                                                menyimpan</small>
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                id="tare">
                                                <i class="fa-solid fa-thumbtack"></i> Stabilkan (Tare)
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer justify-content-center gap-3 flex-wrap border-top pt-3">
                <button id="btnSimpanTimbang" class="btn btn-success px-5 py-2" disabled>
                    <i class="fa-solid fa-floppy-disk me-2"></i> Simpan
                </button>
                <button type="button" class="btn btn-secondary px-5 py-2" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const display = document.getElementById("currentWeight");
            const hiddenInput = document.getElementById("weight");
            const statusText = document.getElementById("previewStatus");
            const btnSimpan = document.getElementById("btnSimpanTimbang");

            const minInput = document.getElementById('rasio_batas_beban_min');
            const maxInput = document.getElementById('rasio_batas_beban_max');
            const lostWeightField = document.getElementById('lost_weight');

            let lastWeight = null;
            let stableTimer = null;

            function setTimbanganKosong() {
                display.innerText = "0.00";
                hiddenInput.value = "0.00";
                statusText.innerText = "Timbangan Kosong";
                statusText.className = "text-warning fw-bold";
                btnSimpan.disabled = true;
                hitungLossWeight(0);
            }

            function hitungLossWeight(current) {
                const min = parseFloat(minInput.value) || 0;
                const max = parseFloat(maxInput.value) || 0;

                if (!min || !max || current <= 0) {
                    lostWeightField.value = '';
                    return;
                }

                const loss = (max - current).toFixed(2);
                const ratio = ((current - min) / (max - min) * 100).toFixed(1);

                lostWeightField.value = `${loss} kg (${ratio}%)`;

                if (current < min) {
                    statusText.textContent = "Berat di bawah batas minimal!";
                    statusText.className = "text-danger fw-bold";
                } else if (current > max) {
                    statusText.textContent = "Berat melebihi batas maksimal!";
                    statusText.className = "text-danger fw-bold";
                } else {
                    statusText.textContent = "Berat dalam batas normal";
                    statusText.className = "text-success fw-bold";
                }
            }

            let lastValidWeight = 0;

            async function ambilBeratLiveLoop() {
                try {
                    const res = await fetch('/api/package/timbangan/live', {
                        credentials: 'include'
                    });

                    if (!res.ok) throw new Error(`HTTP ${res.status}`);

                    const data = await res.json();

                    // Jika API gagal → jangan reset ke 0, tetap gunakan lastValidWeight
                    if (!data.success || data.berat === null || data.berat === undefined) {
                        console.warn("Data tidak valid, mempertahankan berat terakhir:", lastValidWeight);
                        display.innerText = lastValidWeight.toFixed(2);
                        hiddenInput.value = lastValidWeight.toFixed(2);
                        return;
                    }

                    const berat = parseFloat(data.berat);

                    // Jika parsing gagal → skip
                    if (isNaN(berat)) {
                        console.warn("Berat NaN, skip frame");
                        return;
                    }

                    // Update hanya jika berat valid
                    lastValidWeight = berat;

                    display.innerText = berat.toFixed(2);
                    hiddenInput.value = berat.toFixed(2);
                    hitungLossWeight(berat);

                    // Perubahan stabil untuk tombol save
                    if (lastWeight !== berat) {
                        lastWeight = berat;
                        btnSimpan.disabled = true;

                        if (stableTimer) clearTimeout(stableTimer);
                        stableTimer = setTimeout(() => {
                            btnSimpan.disabled = false;
                            statusText.innerText = "Stabil ✓";
                            statusText.className = "text-success fw-bold";
                        }, 800);
                    }

                } catch (e) {
                    console.error("Error berat:", e);

                    // JANGAN SET 0 DI SINI
                    // cukup tampilkan lastValidWeight agar UI tidak loncat
                    display.innerText = lastValidWeight.toFixed(2);
                    hiddenInput.value = lastValidWeight.toFixed(2);

                    statusText.innerText = "Koneksi bermasalah...";
                    statusText.className = "text-warning fw-bold";
                } finally {
                    // polling lebih cepat → 150ms
                    setTimeout(ambilBeratLiveLoop, 150);
                }
            }

            // MULAI LOOP
            setTimbanganKosong();
            ambilBeratLiveLoop(); // mulai langsung

            // TOMBOL TARE
            const btnTare = document.getElementById("tare");
            if (btnTare) {
                btnTare.addEventListener("click", async function() {
                    statusText.innerText = "Mengirim perintah Tare...";
                    statusText.className = "text-warning fw-bold";

                    // LANGSUNG PAKSA TAMPILAN JADI 0 (UX lebih responsif)
                    display.innerText = "0.00";
                    hiddenInput.value = "0.00";
                    statusText.innerText = "Tare berhasil! Timbangan disetel ulang";
                    statusText.className = "text-success fw-bold";
                    btnSimpan.disabled = true;

                    try {
                        const res = await fetch('/api/package/timbangan/tare', {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                tare: true
                            })
                        });

                        if (!res.ok) throw new Error();
                    } catch (err) {
                        statusText.innerText = "Gagal mengirim ke ESP, tapi tampilan sudah direset";
                        statusText.className = "text-warning fw-bold";
                    }
                });
            }

            minInput.addEventListener("input", () => hitungLossWeight(parseFloat(display.innerText) || 0));
            maxInput.addEventListener("input", () => hitungLossWeight(parseFloat(display.innerText) || 0));

            // Simpan data
            btnSimpan.addEventListener('click', async function(e) {
                e.preventDefault();
                const payload = {
                    name: document.getElementById('name').value,
                    berat: hiddenInput.value,
                    no_package: document.getElementById('no_package').value,
                    rasio_batas_beban_min: minInput.value,
                    rasio_batas_beban_max: maxInput.value
                };

                try {
                    const res = await fetch('/api/package/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await res.json();

                    if (data.success) {
                        Swal.fire('Sukses!', data.message, 'success').then(() => {
                            window.location.href = '/user/package-view';
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan', 'error');
                    }
                } catch (err) {
                    console.error(err);
                    Swal.fire('Error', 'Tidak dapat menyimpan data', 'error');
                }
            });
        });
    </script>
@endpush

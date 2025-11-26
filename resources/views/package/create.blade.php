<div class="modal fade" id="tambah" tabindex="-1" aria-labelledby="tambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            <!-- HEADER -->
            <div class="modal-header border-bottom text-dark">
                <h5 class="modal-title fw-bold" id="tambahLabel">
                    Tambah Ordersheet Package
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="formOrdersheet" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="weight" id="weight">

                <!-- BODY — Scrollable & Responsive -->
                <div class="modal-body p-3 p-md-4" style="max-height: 75vh; overflow-y: auto;">

                    <!-- CARD: Informasi Package -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary fw-bold border-bottom pb-2">Informasi Package</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Package <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Contoh: Wallet Kulit Sapi" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Leather Type</label>
                                    <input type="text" name="leather_type" id="leather_type" class="form-control"
                                        placeholder="Kulit Sapi, Domba, dll">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Color</label>
                                    <input type="text" name="color" id="color" class="form-control"
                                        placeholder="Hitam, Coklat, dll">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Size</label>
                                    <input type="text" name="size" id="size" class="form-control"
                                        placeholder="20x10 cm">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Stitching Type</label>
                                    <input type="text" name="stitching_type" id="stitching_type" class="form-control"
                                        placeholder="Hand Stitch, Mesin">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lining Material</label>
                                    <input type="text" name="lining_material" id="lining_material"
                                        class="form-control" placeholder="Kain, Kulit Tipis">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                                    <textarea name="description" rows="3" class="form-control" placeholder="Catatan tambahan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD: Berat Barang -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary fw-bold border-bottom pb-2">Berat Barang</h5>

                            <div class="row g-4 align-items-stretch">

                                <!-- KIRI: Input Manual -->
                                <div class="col-12 col-lg-6">
                                    <div class="h-80 d-flex flex-column gap-3">

                                        <div>
                                            <label class="form-label fw-semibold small text-muted">No. Package</label>
                                            <input type="text" name="no_package" id="no_package" class="form-control"
                                                required placeholder="PKG-001">
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small text-muted">Batas Min
                                                    (g)</label>
                                                <input type="number" step="0.01" name="rasio_batas_beban_min"
                                                    id="rasio_batas_beban_min" class="form-control text-center" required
                                                    placeholder="100">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label fw-semibold small text-muted">Batas Max
                                                    (g)</label>
                                                <input type="number" step="0.01" name="rasio_batas_beban_max"
                                                    id="rasio_batas_beban_max" class="form-control text-center"
                                                    required placeholder="150">
                                            </div>
                                        </div>

                                        <div class="text-center mt-auto">
                                            <label class="form-label fw-semibold text-success small d-block mb-1">Rasio
                                                Lost Weight</label>
                                            <input type="text" name="lost_weight" id="lost_weight" readonly
                                                class="form-control form-control-lg text-center bg-light fw-bold shadow-sm"
                                                style="max-width: 220px; margin: 0 auto;" value=""
                                                placeholder="0.00 kg (0%)">
                                        </div>
                                    </div>
                                </div>

                                <!-- KANAN: Timbangan Live -->
                                <div class="col-12 col-lg-6">
                                    <div class="h-60 d-flex flex-column">

                                        <div class="alert alert-success py-2 small text-center mb-3 rounded-pill">
                                            Timbangan Aktif & Siap
                                        </div>

                                        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                                            <div class="text-center p-5 rounded-4 shadow-lg weight-box">
                                                <h1 id="currentWeight" class="display-3 fw-bold text-primary mb-0">0
                                                </h1>
                                                <p class="text-muted fs-4 mb-2">Gram</p>
                                                <div id="previewStatus"
                                                    class="badge bg-warning text-dark fw-bold px-3 py-2">
                                                    Menunggu data...
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <small class="text-muted d-block mb-3">Pastikan timbangan stabil sebelum
                                                menyimpan</small>
                                            <button type="button" id="tare"
                                                class="btn btn-outline-primary rounded-pill px-4">
                                                <i class="fa-solid fa-thumbtack"></i> Stabilkan (Tare)
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <!-- AKHIR modal-body -->

                <!-- FOOTER -->
                <div class="modal-footer justify-content-center gap-3 border-top bg-light py-4">
                    <button type="submit" id="btnSimpanTimbang" class="btn btn-success btn-lg px-5 shadow" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary btn-lg px-5" data-bs-dismiss="modal">
                        <i class="fa-solid fa-circle-xmark"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
    <style>
        /* FIX SCROLL BOCOR KE BACKGROUND */
        .modal-dialog-scrollable {
            overflow: hidden !important;
        }

        .modal-dialog-scrollable .modal-body {
            max-height: 60vh !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
            /* smooth scroll di iPhone */
        }

        /* Fullscreen di HP tanpa bug */
        @media (max-width: 768px) {
            .modal-dialog {
                margin: 0 !important;
                max-width: 100% !important;
                height: 100% !important;
            }

            .modal-content {
                height: 100% !important;
                border-radius: 0 !important;
            }

            .modal-body {
                max-height: 80vh !important;
            }
        }

        @media (max-width: 576px) {
            #currentWeight {
                font-size: 4rem !important;
            }

            .weight-box {
                padding: 2rem !important;
                min-width: 200px;
            }
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const display = document.getElementById("currentWeight");
            const hiddenInput = document.getElementById("weight");
            const statusText = document.getElementById("previewStatus");
            const btnSimpan = document.getElementById("btnSimpanTimbang");
            const form = document.getElementById("formOrdersheet");

            const minInput = document.getElementById('rasio_batas_beban_min');
            const maxInput = document.getElementById('rasio_batas_beban_max');
            const lostWeightField = document.getElementById('lost_weight');

            let lastValidWeight = 0;
            let lastWeight = null;
            let stableTimer = null;
            let isTareMode = false; // BARU: tanda bahwa tare baru saja dilakukan

            function formatBerat(value) {
                const num = parseFloat(value);
                if (isNaN(num)) return "0";
                return Number.isInteger(num) ? num.toString() : num.toFixed(2);
            }

            function setTimbanganKosong() {
                lastValidWeight = 0;
                isTareMode = false; // bukan karena tare, tapi benar-benar kosong
                display.innerText = "0";
                hiddenInput.value = "0";
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

                    // HANYA tampilkan "Timbangan Kosong" jika BUKAN karena tare
                    if (current <= 0 && !isTareMode) {
                        statusText.innerText = "Timbangan Kosong";
                        statusText.className = "text-warning fw-bold";
                    }
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

            // POLLING BERAT REAL-TIME
            async function ambilBeratLiveLoop() {
                try {
                    const res = await fetch('/api/package/timbangan/live', {
                        credentials: 'include',
                        cache: 'no-cache'
                    });

                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    const data = await res.json();

                    if (!data.success || data.berat === null || data.berat === undefined) {
                        return;
                    }

                    const berat = parseFloat(data.berat);
                    if (isNaN(berat)) return;

                    lastValidWeight = berat;

                    display.innerText = formatBerat(berat);
                    hiddenInput.value = berat;
                    hitungLossWeight(berat);

                    // Jika berat kembali ke 0 dan BUKAN karena tare → baru tampilkan "Timbangan Kosong"
                    if (berat === 0 && !isTareMode) {
                        statusText.innerText = "Timbangan Kosong";
                        statusText.className = "text-warning fw-bold";
                    }

                    // Reset tare mode jika ada barang lagi
                    if (berat > 0) {
                        isTareMode = false;
                    }

                    // Stabil detection
                    if (lastWeight !== berat) {
                        lastWeight = berat;
                        btnSimpan.disabled = true;
                        if (stableTimer) clearTimeout(stableTimer);

                        stableTimer = setTimeout(() => {
                            btnSimpan.disabled = false;
                            // Hanya tampilkan "Stabil" jika bukan tare mode
                            if (!isTareMode || berat > 0) {
                                statusText.innerText = "Stabil";
                                statusText.className = "text-success fw-bold";
                            }
                        }, 800);
                    }

                } catch (e) {
                    console.error("Polling error:", e);
                    statusText.innerText = "Koneksi bermasalah...";
                    statusText.className = "text-warning fw-bold";
                } finally {
                    setTimeout(ambilBeratLiveLoop, 150);
                }
            }

            // TOMBOL TARE — DIPERBAIKI SESUAI PERMINTAANMU
            const btnTare = document.getElementById("tare");
            if (btnTare) {
                btnTare.addEventListener("click", async function() {
                    statusText.innerText = "Mengirim perintah Tare...";
                    statusText.className = "text-warning fw-bold";

                    display.innerText = "0";
                    hiddenInput.value = "0";
                    lastValidWeight = 0;
                    isTareMode = true; // TANDA: ini karena tare, bukan kosong beneran

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

                        if (res.ok) {
                            statusText.innerText = "Tare berhasil!";
                            statusText.className = "text-success fw-bold";
                        } else {
                            throw new Error();
                        }
                    } catch (err) {
                        statusText.innerText = "Gagal kirim ke ESP (tapi UI direset)";
                        statusText.className = "text-warning fw-bold";
                    }
                });
            }

            // Update loss saat min/max berubah
            if (minInput) minInput.addEventListener("input", () => hitungLossWeight(parseFloat(hiddenInput.value) ||
                0));
            if (maxInput) maxInput.addEventListener("input", () => hitungLossWeight(parseFloat(hiddenInput.value) ||
                0));

            // SUBMIT FORM (tetap sama)
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (btnSimpan.disabled) {
                    Swal.fire('Belum Stabil', 'Tunggu timbangan stabil terlebih dahulu!', 'warning');
                    return;
                }

                const formData = new FormData(form);
                formData.set('berat', hiddenInput.value);

                btnSimpan.disabled = true;
                btnSimpan.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Menyimpan...';

                try {
                    const res = await fetch('/api/package/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: formData
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
                    Swal.fire('Error', 'Tidak dapat menyimpan data!', 'error');
                } finally {
                    btnSimpan.disabled = false;
                    btnSimpan.innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i> Simpan';
                }
            });

            // MULAI
            setTimbanganKosong();
            ambilBeratLiveLoop();
        });
    </script>
@endpush

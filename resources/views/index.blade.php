<x-layout.home title="Dashboard">

    <div class="page-heading d-flex justify-content-between align-items-center">
        <div>
            <h3>Dashboard</h3>
            <h5 class="welcome-message">Selamat Datang,
                <span class="text-warning">{{ Auth::user()->username ?? '-' }}</span>
            </h5>
        </div>

        {{-- Bagian waktu di sebelah kanan --}}
        <div class="text-end">
            <h6 id="current-day" class="mb-0 fw-bold"></h6>
            <small id="current-time" class="text-muted"></small>
        </div>
    </div>

    <hr>

    <div class="page-content">
        <section class="row">

            {{-- ===== STATISTIK CARD ===== --}}
            <div class="col-12 col-md-12 mb-4">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm text-center p-3">
                            <h6 class="text-muted">Total Ordersheet</h6>
                            <h3 class="fw-bold">{{ $totalOrdersheet }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm text-center p-3">
                            <h6 class="text-muted">Total Box Ditimbang</h6>
                            <h3 class="fw-bold">{{ $totalTimbangan }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm text-center p-3">
                            <h6 class="text-muted">Box Sukses</h6>
                            <h3 class="fw-bold text-success">{{ $totalSuccess }}</h3>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card shadow-sm text-center p-3">
                            <h6 class="text-muted">Box Rejected</h6>
                            <h3 class="fw-bold text-danger">{{ $totalRejected }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== GRAFIK ===== --}}
            <div class="col-12 col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Grafik Timbangan per Ordersheet</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTimbangan"></canvas>
                    </div>
                </div>
            </div>

            {{-- ===== TABEL RINGKAS ===== --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5>Ordersheet Terbaru</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-bordered text-center">
                            <thead class="table-secondary">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Buyer</th>
                                    <th>PO</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestOrders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->Order_code }}</td>
                                        <td>{{ $order->Buyer }}</td>
                                        <td>{{ $order->PO }}</td>
                                        <td>{{ $order->Qty_order }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
    </div>

    @push('css')
        <style>
            .card h3 {
                font-size: 1.8rem;
            }
        </style>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            const ctx = document.getElementById('chartTimbangan');
            const chartTimbangan = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Box Ditimbang',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

</x-layout.home>

@extends('admin.layout.app')
@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Karyawan</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $kpiTotalKaryawan }}</h4>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Hadir Hari Ini</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $kpiHadirHariIni }}</h4>
                </div>
                <div class="p-2 bg-green-100 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Pengeluaran (Realtime)</p>
                    {{-- Ini mengambil angka hasil hitungan absen di controller --}}
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $kpiTotalPengeluaranBulanIni }}</h4>
                </div>
                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 10v-1m-7 1h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Karyawan Terlambat</p>
                    <h4 class="text-2xl font-bold text-gray-800 mt-1">{{ $kpiTerlambatHariIni }}</h4>
                </div>
                <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border border-gray-100 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h3 class="text-lg font-bold text-gray-800">Tren Pengeluaran Gaji</h3>
            <div class="text-sm text-gray-500">Periode: <span class="font-medium">{{ $currentPeriodLabel }}</span></div>
        </div>
        <div class="relative w-full h-64 md:h-[400px]">
            <canvas id="payrollTrendChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('payrollTrendChart');
            if (!ctx) return;

            // Ambil data dari Controller
            const labels = {!! json_encode($payrollTrendLabels) !!};
            const dataVals = {!! json_encode($payrollTrendValues) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pengeluaran (Rp)',
                        data: dataVals,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.15)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
@endsection
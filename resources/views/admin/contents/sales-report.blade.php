@extends('admin.dashboard')
@section('content')
    <div class="m-2">
        @include('components.search')
    </div>
    <section class="m-2 max-lg:mt-10 px-3 pb-3 bg-white shadow-lg rounded-md">
        <div class="flex items-center justify-between py-2 border-b">
            <h1 class="font-bold text-2xl max-md:text-lg">Sales Report</h1>
            <div class="w-48">
                <select id="branch" name="branch" class="w-full text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                    <option value="">All Branches</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_loc }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="py-2">
            <div class="flex flex-wrap gap-3 px-2 mb-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox h-3 w-3 text-green-600" id="dailyChartToggle" checked>
                    <span class="ml-1 text-xs">Daily</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox h-3 w-3 text-green-600" id="weeklyChartToggle" checked>
                    <span class="ml-1 text-xs">Weekly</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox h-3 w-3 text-green-600" id="monthlyChartToggle" checked>
                    <span class="ml-1 text-xs">Monthly</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox h-3 w-3 text-green-600" id="totalChartToggle" checked>
                    <span class="ml-1 text-xs">By Branch</span>
                </label>
            </div>

            <div class="grid grid-cols-2 gap-2 p-2" id="chartsContainer">
                <div id="dailyChartDiv" class="chart-div bg-white rounded shadow p-1">
                    <h2 class="text-[10px] font-semibold">Daily Revenue</h2>
                    <div class="w-full h-24"><canvas id="dailyRevenueChart"></canvas></div>
                </div>
                <div id="weeklyChartDiv" class="chart-div bg-white rounded shadow p-1">
                    <h2 class="text-[10px] font-semibold">Weekly Revenue</h2>
                    <div class="w-full h-24"><canvas id="weeklyRevenueChart"></canvas></div>
                </div>
                <div id="monthlyChartDiv" class="chart-div bg-white rounded shadow p-1">
                    <h2 class="text-[10px] font-semibold">Monthly Revenue</h2>
                    <div class="w-full h-24"><canvas id="monthlyRevenueChart"></canvas></div>
                </div>
                <div id="totalChartDiv" class="chart-div bg-white rounded shadow p-1">
                    <h2 class="text-[10px] font-semibold">Revenue by Branch</h2>
                    <div class="w-full h-24"><canvas id="totalRevenueChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 p-2">
            <div class="flex-grow">
                <div class="bg-white rounded shadow">
                    <div class="px-2 py-1 border-b">
                        <h3 class="text-[10px] font-semibold text-gray-600">Recent Payments</h3>
                    </div>
                    <div class="overflow-x-auto max-h-32">
                        <table class="min-w-full table-auto text-[10px]">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-2 py-1 text-left font-medium text-gray-500">Date</th>
                                    <th class="px-2 py-1 text-left font-medium text-gray-500">Patient</th>
                                    <th class="px-2 py-1 text-right font-medium text-gray-500">Branch</th>
                                    <th class="px-2 py-1 text-center font-medium text-gray-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($paymentHistories as $history)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-0.5">{{ $history->created_at->format('M d') }}</td>
                                        <td class="px-2 py-0.5">{{ $history->payment->appointment->patient->first_name }} {{ $history->payment->appointment->patient->last_name }}</td>
                                        <td class="px-2 py-0.5 text-right">{{ $history->payment->appointment->branch->branch_loc }}</td>
                                        <td class="px-2 py-0.5 text-center">
                                            <span class="px-1 py-0.5 text-[8px] rounded-full inline-block min-w-[36px] ">
                                                ₱{{ number_format($history->paid_amount, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="min-w-[30%]">
                <div class="bg-white rounded shadow">
                    <div class="px-2 py-1 border-b">
                        <h3 class="text-[10px] font-semibold text-gray-600">Today's Procedures</h3>
                    </div>
                    <div class="overflow-x-auto max-h-32">
                        <table class="min-w-full table-auto text-[10px]">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-2 py-1 text-left font-medium text-gray-500">Procedure</th>
                                    <th class="px-2 py-1 text-center font-medium text-gray-500">#</th>
                                    <th class="px-2 py-1 text-right font-medium text-gray-500">Rev</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($todayProcedures as $procedure)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-2 py-0.5">{{ $procedure['procedure'] }}</td>
                                        <td class="px-2 py-0.5 text-center">{{ $procedure['count'] }}</td>
                                        <td class="px-2 py-0.5 text-right">₱{{ number_format($procedure['total_amount'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-2 py-2 text-center text-gray-500">No procedures today</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end p-2 border-t ">
            <div class="text-right">
                <span class="text-gray-600 text-sm">Total:</span>
                <span class="font-bold ml-1 text-xl">₱{{ number_format($totalRevenue, 2) }}</span>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => '₱' + context.raw.toLocaleString()
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (value) => '₱' + value.toLocaleString(),
                            font: { size: 8 },
                            maxTicksLimit: 4
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 8 },
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 6
                        }
                    }
                }
            };

            // Chart toggle functionality
            const toggles = {
                'dailyChartToggle': 'dailyChartDiv',
                'weeklyChartToggle': 'weeklyChartDiv',
                'monthlyChartToggle': 'monthlyChartDiv',
                'totalChartToggle': 'totalChartDiv'
            };

            function updateChartLayout() {
                const visibleCharts = document.querySelectorAll('.chart-div:not(.hidden)');
                const container = document.getElementById('chartsContainer');
                container.className = 'grid grid-cols-2 gap-2 p-2';
                visibleCharts.forEach(chart => {
                    chart.className = 'chart-div bg-white rounded shadow p-1';
                });
            }

            Object.entries(toggles).forEach(([toggleId, chartId]) => {
                document.getElementById(toggleId).addEventListener('change', function() {
                    const chartDiv = document.getElementById(chartId);
                    this.checked ? chartDiv.classList.remove('hidden') : chartDiv.classList.add('hidden');
                    updateChartLayout();
                });
            });

            document.getElementById('branch').addEventListener('change', function() {
                window.location.href = `{{ route('sales') }}?branch=${this.value}`;
            });

            // Daily Revenue Chart
            new Chart('dailyRevenueChart', {
                type: 'line',
                data: {
                    labels: Object.keys(@json($dailyRevenueData)),
                    datasets: [{
                        data: Object.values(@json($dailyRevenueData)),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1.5,
                        tension: 0.4
                    }]
                },
                options: chartOptions
            });

            // Weekly Revenue Chart
            new Chart('weeklyRevenueChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(@json($weeklyComparisonData)),
                    datasets: [{
                        data: Object.values(@json($weeklyComparisonData)),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });

            // Monthly Revenue Chart
            new Chart('monthlyRevenueChart', {
                type: 'line',
                data: {
                    labels: Object.keys(@json($monthlyRevenueData)),
                    datasets: [{
                        data: Object.values(@json($monthlyRevenueData)),
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderWidth: 1.5,
                        tension: 0.4
                    }]
                },
                options: chartOptions
            });

            // Total Revenue Chart
            new Chart('totalRevenueChart', {
                type: 'bar',
                data: {
                    labels: Object.keys(@json($comparisonData)),
                    datasets: [{
                        data: Object.values(@json($comparisonData)),
                        backgroundColor: 'rgba(255, 159, 64, 0.6)',
                        borderWidth: 0
                    }]
                },
                options: chartOptions
            });
        });
    </script>
@endsection

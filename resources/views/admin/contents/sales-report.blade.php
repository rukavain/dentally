@extends('admin.dashboard')
@section('content')
    <div class="m-4 ">
        @include('components.search')
    </div>
    <section class=" m-4 max-lg:mt-14 px-4 pb-4 bg-white shadow-lg rounded-md">
        <h1 class="font-bold text-3xl max-md:text-xl py-4">Sales Report</h1>
        <div>
            <div class="w-full flex flex-wrap gap-3 justify-around p-4 ">
                <div
                    class="w-[30%] flex flex-1 flex-col gap-5 justify-center items-start px-4 py-4 bg-green-200 rounded-2xl max-md:w-full">
                    <h2 class="font-semibold">Daily Revenue</h2>
                    <div class="w-full h-32">
                        <canvas id="dailyRevenueChart"></canvas>
                    </div>
                </div>
                <div
                    class="w-[30%] flex flex-1 flex-col gap-5 justify-center items-start px-4 py-4 bg-green-200 rounded-2xl max-md:w-full">
                    <h2 class="font-semibold">Monthly Revenue</h2>
                    <div class="w-full h-32">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
                <div
                    class="w-[30%] flex flex-1 flex-col gap-5 justify-center items-start px-4 py-4 bg-green-200 rounded-2xl max-md:w-full">
                    <h2 class="font-semibold">Total Revenue</h2>
                    <div class="w-full h-32">
                        <canvas id="totalRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full max-h-96 flex max-lg:flex-wrap gap-5 py-3 px-5 max-lg:max-h-full">

            <div class="w-[70%] flex-1 max-lg:w-full max-xl:w-[50%]">
                <h2 class="font-bold text-lg max-md:text-md py-2 pl-1">Recent Payments</h2>
                <div class="overflow-auto max-h-72">
                    <table class="min-w-full table-auto text-center">
                        <thead class="bg-green-200 text-green-700">
                            <tr class="border-b">
                                <th class="px-4 py-2 border max-xl:text-sm">Patient</th>
                                <th class="px-4 py-2 border max-xl:text-sm">Paid Amount</th>
                                <th class="px-4 py-2 border max-xl:hidden">Payment Method</th>
                                <th class="px-4 py-2 border max-xl:text-sm">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentHistories as $history)
                                <tr class="hover:bg-gray-100 border-b-2">
                                    <td class="px-4 py-2 max-xl:text-xs ">
                                        {{ $history->payment->appointment->patient->first_name }}
                                        {{ $history->payment->appointment->patient->last_name }}</td>
                                    <td class="px-4 py-2 max-xl:text-xs ">&#8369;
                                        {{ number_format($history->paid_amount, 2) }}</td>
                                    <td class="px-4 py-2  max-xl:hidden">{{ $history->payment_method }}</td>
                                    <td class="px-4 py-2 max-xl:text-xs ">{{ $history->created_at->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                class="w-[25%] flex-2 max-lg:w-full max-xl:w-[50%]  max-md:border-blue-600 max-lg:border-green-500 max-xl:border-violet-500">
                <h2 class="font-bold text-center text-lg max-md:text-md py-2 pl-1">Top Procedures</h2>
                <table class="min-w-full table-auto text-center">
                    <thead>
                        <tr class="bg-green-200 text-green-700">
                            <th class="px-4 py-2 max-md:text-xs">Procedure</th>
                            <th class="px-4 py-2 max-md:text-xs">Count</th>
                            <th class="px-4 py-2 max-md:text-xs max-lg:hidden">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($frequentlyPerformedProcedures as $procedure)
                            <tr class=" border-b-2 last:border-b-0">
                                <td class="px-3 py-5 text-sm max-md:text-sm">{{ $procedure['procedure'] }}</td>
                                <td class="px-3 py-5 text-sm max-md:text-sm">{{ $procedure['count'] }}</td>
                                <td class="px-3 py-5 text-sm max-md:text-sm max-lg:hidden">&#8369;
                                    {{ number_format($procedure['total_amount'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <script>
        const monthly = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyRevenueData = @json($monthlyRevenueData);

        const monthlyLabels = Object.keys(monthlyRevenueData);
        const monthlyData = Object.values(monthlyRevenueData);

        const currentMonth = new Date().getMonth();

        const backgroundColors = monthlyLabels.map((label, index) => {
            return index === currentMonth ? 'rgba(75, 192, 192, 0.2)' : 'rgba(75, 192, 192, 0.2)';
        });

        const borderColors = monthlyLabels.map((label, index) => {
            return index === currentMonth ? 'rgba(75, 192, 192, 1)' : 'rgba(75, 192, 192, 1)';
        });

        const monthlyRevenueChart = new Chart(monthly, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: monthlyData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `Revenue: ₱${tooltipItem.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });

        const daily = document.getElementById('dailyRevenueChart').getContext('2d');
        const dailyComparisonData = @json($dailyComparisonData);

        const dailyLabels = Object.keys(dailyComparisonData);
        const dailyData = Object.values(dailyComparisonData);

        const dailyRevenueChart = new Chart(daily, {
            type: 'pie',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Today',
                    data: dailyData,
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const totalRevenue = document.getElementById('totalRevenueChart').getContext('2d');
        const comparisonData = @json($comparisonData);

        const totalLabel = Object.keys(comparisonData);
        const totalData = Object.values(comparisonData);

        const totalRevenueChart = new Chart(totalRevenue, {
            type: 'doughnut',
            data: {
                labels: totalLabel,
                datasets: [{
                    label: 'Revenue',
                    data: totalData,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection

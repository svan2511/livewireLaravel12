<x-layouts.app :title="__('Dashboard')">

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">

        <!-- Main Container -->
        <div class="max-w-7xl mx-auto space-y-8">

            <!-- Header + Top Stats Cards -->
            <div class="space-y-6">

                <!-- Page Title + Last Updated -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Financial Dashboard</h1>
                        <p class="mt-1 text-sm text-gray-500">Real-time overview of disbursements, collections, OD & demand</p>
                    </div>
                    <div class="mt-4 sm:mt-0 text-sm text-gray-500">
                        Last updated: <span id="lastUpdated" class="font-medium">{{ now()->format('d M Y, h:i A') }}</span>
                    </div>
                </div>

                <!-- Top 3 Stat Cards (Modern Glassmorphism Style) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium">Total Users</p>
                                <p class="text-4xl font-bold mt-2">150</p>
                            </div>
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21h3a2 2 0 002-2v-1a2 2 0 00-2-2H9a2 2 0 00-2 2v1a2 2 0 002 2h3m-6 0h12"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Total Centers</p>
                                <p class="text-4xl font-bold mt-2">25</p>
                            </div>
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-8 0H5"/>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Total Members</p>
                                <p class="text-4xl font-bold mt-2">340</p>
                            </div>
                            <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Section – Clean & Elegant -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">Filter Data</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Year -->
                    <div>
                        <label for="yearFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                        <select id="yearFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="2025" selected>2025</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                        </select>
                    </div>

                    <!-- Center -->
                    <div>
                        <label for="centerFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Center</label>
                        <select id="centerFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="all">All Centers</option>
                            <option value="downtown">Downtown Branch</option>
                            <option value="uptown">Uptown Branch</option>
                            <option value="east">East Side</option>
                            <option value="west">West Side</option>
                        </select>
                    </div>

                    <!-- Member -->
                    <div>
                        <label for="memberFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member</label>
                        <select id="memberFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="all" selected>All Members</option>
                            <option value="john">John Doe (M001)</option>
                            <option value="jane">Jane Smith (M002)</option>
                            <option value="raj">Raj Kumar (M003)</option>
                            <option value="priya">Priya Sharma (M004)</option>
                        </select>
                    </div>

                    <!-- Apply Button (Optional – for visual polish) -->
                    <div class="flex items-end">
                        <button onclick="updateCharts()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Chart 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Disbursement vs Collection</h3>
                        <span class="text-sm text-gray-500">Monthly Trend</span>
                    </div>
                    <div class="h-96">
                        <canvas id="disbCollectionChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">OD vs Demand</h3>
                        <span class="text-sm text-gray-500">Monthly Trend</span>
                    </div>
                    <div class="h-96">
                        <canvas id="odDemandChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            // Sample data per year
            const yearlyData = {
                2025: { disb: [120000,150000,180000,160000,200000,170000,180000,190000,200000,210000,220000,230000], coll: [80000,110000,130000,140000,170000,150000,160000,170000,180000,190000,200000,210000], od: [30000,40000,35000,45000,50000,40000,42000,46000,48000,50000,52000,55000], dem: [50000,60000,55000,65000,70000,60000,62000,65000,67000,70000,72000,75000] },
                2024: { disb: [100000,120000,140000,130000,160000,150000,170000,180000,190000,195000,205000,215000], coll: [70000,90000,110000,120000,140000,130000,145000,155000,165000,175000,185000,195000], od: [25000,30000,32000,38000,42000,38000,40000,43000,45000,47000,49000,51000], dem: [45000,52000,50000,58000,62000,57000,59000,61000,63000,65000,68000,70000] }
            };

            let disbChart, odChart;

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1000, easing: 'easeOutQuart' },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 13 }, padding: 20 } },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        cornerRadius: 8,
                        callbacks: {
                            label: ctx => {
                                const val = ctx.parsed.y;
                                return `${ctx.dataset.label}: ₹${val.toLocaleString('en-IN')}`;
                            }
                        }
                    }
                },
                scales: {
                    x: { ticks: { autoSkip: false, maxRotation: 0, minRotation: 0, color: '#4B5563' }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: '#4B5563' } }
                },
                layout: { padding: { bottom: 20 } }
            };

            window.updateCharts = function() {
                const year = document.getElementById('yearFilter').value;
                const center = document.getElementById('centerFilter').value;
                const member = document.getElementById('memberFilter').value;

                const data = yearlyData[year] || yearlyData[2025];

                // Apply simple filtering logic (demo)
                const multiplier = (center === 'all' ? 1 : 0.9 + Math.random() * 0.4) * (member === 'all' ? 1 : 0.85 + Math.random() * 0.3);

                const finalDisb = data.disb.map(v => v * multiplier);
                const finalColl = data.coll.map(v => v * multiplier);
                const finalOD = data.od.map(v => v * multiplier);
                const finalDem = data.dem.map(v => v * multiplier);

                // Update charts
                [disbChart, odChart].forEach(chart => chart?.destroy());

                disbChart = new Chart(document.getElementById('disbCollectionChart'), {
                    type: 'bar', data: { labels: months, datasets: [
                        { label: 'Disbursement', data: finalDisb, backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: '#3B82F6', borderWidth: 2 },
                        { label: 'Collection', data: finalColl, backgroundColor: 'rgba(34, 197, 94, 0.5)', borderColor: '#22C55E', borderWidth: 2 }
                    ]}, options: commonOptions
                });

                odChart = new Chart(document.getElementById('odDemandChart'), {
                    type: 'bar', data: { labels: months, datasets: [
                        { label: 'Outstanding (OD)', data: finalOD, backgroundColor: 'rgba(239, 68, 68, 0.5)', borderColor: '#EF4444', borderWidth: 2 },
                        { label: 'Demand', data: finalDem, backgroundColor: 'rgba(251, 146, 60, 0.5)', borderColor: '#FB923C', borderWidth: 2 }
                    ]}, options: commonOptions
                });

                document.getElementById('lastUpdated').textContent = new Date().toLocaleString('en-IN');
            };

            // Initial load
            updateCharts();

            // Auto-update on dropdown change (optional – remove if you want button only)
            ['yearFilter', 'centerFilter', 'memberFilter'].forEach(id => {
                document.getElementById(id).addEventListener('change', updateCharts);
            });
        });
    </script>
</x-layouts.app>
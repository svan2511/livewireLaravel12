<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header + Top Stats Cards -->
        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Financial Dashboard</h1>
                    <p class="mt-1 text-sm text-gray-500">Real-time overview of disbursements, collections, OD & demand</p>
                </div>
                <div class="mt-4 sm:mt-0 text-sm text-gray-500">
                    Last updated: <span id="lastUpdated" class="font-medium">{{ now()->format('d M Y, h:i A') }}</span>
                </div>
            </div>

            <!-- Top Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Total Users</p>
                            <p class="text-4xl font-bold mt-2">{{$userCount}}</p>
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
                            <p class="text-4xl font-bold mt-2">{{$centerCount}}</p>
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
                            <p class="text-4xl font-bold mt-2">{{$memberCount}}</p>
                        </div>
                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">Filter Data</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label for="yearFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                    <select wire:model.live="selectedYear" id="yearFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                        <option value="2022">2022</option>
                    </select>
                </div>

                <div>
                    <label for="centerFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Center</label>
                    <select wire:model.live="selectedCenter" id="centerFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all">All Centers</option>
                        @foreach($centers as $center)
                        <option value="{{$center->id}}">{{$center->center_name}}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="memberFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Member</label>
                    <select wire:model.live="selectedMember" id="memberFilter" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="all">All Members</option>
                        @foreach($members as $member)
                        <option value="{{$member->id}}">{{$member->mem_name}}</option>
                        @endforeach
                    </select>
                </div>

                <!-- <div class="flex items-end">
                    <button wire:click="applyFilters" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Apply Filters
                    </button>
                </div> -->
            </div>
        </div>

        <!-- Charts Section -->
       <div class="space-y-8">

    <!-- Chart 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 w-full">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Disbursement vs Collection</h3>
            <span class="text-sm text-gray-500">Monthly Trend</span>
        </div>
        <div class="h-96 w-full">
            <canvas id="disbCollectionChart"></canvas>
        </div>
    </div>

    <!-- Chart 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 w-full">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">OD vs Demand</h3>
            <span class="text-sm text-gray-500">Monthly Trend</span>
        </div>
        <div class="h-96 w-full">
            <canvas id="odDemandChart"></canvas>
        </div>
    </div>

</div>

    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let disbChart = null;
let odChart   = null;

// This will always store the latest data coming from Livewire
let yearlyData = {};  
let lastYear = null;

function renderCharts() {

    const disbCanvas = document.getElementById('disbCollectionChart');
    const odCanvas   = document.getElementById('odDemandChart');

    // STOP if this is not dashboard
    if (!disbCanvas || !odCanvas) return;

    // Destroy old charts
    if (disbChart) disbChart.destroy();
    if (odChart) odChart.destroy();

    // Get Livewire filter values
    const lw = window.Livewire.find(
        document.querySelector('[wire\\:id]').getAttribute('wire:id')
    );

    const selectedYear   = lw.get('selectedYear');
    const selectedCenter = lw.get('selectedCenter');
    const selectedMember = lw.get('selectedMember');

    lastYear = selectedYear;

    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    const base = yearlyData[selectedYear] ?? yearlyData[Object.keys(yearlyData)[0]];

    if (!base) {
        console.warn("No graphdata received.");
        return;
    }

    const disb = base.disb;
    const coll = base.coll;
    const od   = base.od;
    const dem  = base.dem;

    // Render chart 1
    disbChart = new Chart(disbCanvas, {
        type: "bar",
        data: {
            labels: months,
            datasets: [
                { label: "Disbursement", data: disb, backgroundColor: "rgba(59,130,246,0.6)" },
                { label: "Collection", data: coll, backgroundColor: "rgba(34,197,94,0.6)" }
            ]
        },
        
        options: {
        responsive: true,
        maintainAspectRatio: false, // IMPORTANT
    },
    });

    // Render chart 2
    odChart = new Chart(odCanvas, {
        type: "bar",
        data: {
            labels: months,
            datasets: [
                { label: "OD",     data: od,  backgroundColor:"rgba(239,68,68,0.6)" },
                { label: "Demand", data: dem, backgroundColor:"rgba(251,146,60,0.6)" }
            ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false, // IMPORTANT
    },
    });
}

// FIRST LOAD — dashboard only
document.addEventListener("livewire:navigated", () => {
    const disbCanvas = document.getElementById('disbCollectionChart');
    if (disbCanvas) {
        yearlyData = @json($graphdata);
        renderCharts();
    }
});

// When Livewire sends updated data
window.addEventListener("update-charts", (event) => {
    console.log("Received updated graphdata from Livewire");
    yearlyData = event.detail.graphdata; // latest updated data
    renderCharts();
});

// For initial load (non-SPA)
document.addEventListener("DOMContentLoaded", () => {
    const disbCanvas = document.getElementById('disbCollectionChart');
    if (disbCanvas) {
        yearlyData = @json($graphdata);
        renderCharts();
    }
});
</script>


</div>
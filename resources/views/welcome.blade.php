<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subh Unnati | Employee Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">

<!-- BANNER with padding/margin from top, left & right -->
<div class="w-full px-6 pt-8 md:px-12 lg:px-20">
    <div class="w-full rounded-2xl overflow-hidden shadow-2xl">
        <img 
            src="{{ asset('storage/banner/banner.png') }}" 
            alt="Subh Unnati Microfinance"
            class="w-full h-64 md:h-96 object-cover"
            onerror="this.src='https://images.unsplash.com/photo-1579621970563-ebec75606932?w=1600&h=600&fit=crop&auto=format'"
        />
    </div>
</div>

<!-- MAIN CONTENT with good spacing -->
<div class="max-w-5xl mx-auto px-6 py-16 text-center">

    <!-- Welcome Text -->
    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
        Welcome Back, Team!
    </h1>
    <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed">
        Access your dashboard to manage loans, collections, reports and branch operations securely.
    </p>

    <!-- SINGLE BIG LOGIN BUTTON -->
    <div class="mb-20">
        <a href="{{ route('login') }}"
           class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold text-xl px-16 py-6 rounded-xl shadow-xl transition transform hover:scale-105">
            Login to Employee Dashboard
        </a>
    </div>

    <!-- 3 FEATURE CARDS – Clean & Professional -->
    <div class="grid md:grid-cols-3 gap-10 mt-10">
        
        <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
            <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-5 flex items-center justify-center">
                <svg class="w-9 h-9 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Loan Processing</h3>
            <p class="text-gray-600">Verify documents, sanction loans & track disbursals</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
            <div class="w-16 h-16 bg-green-100 rounded-full mx-auto mb-5 flex items-center justify-center">
                <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 3 2 3 .895 3 2-1.343 2-3m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1a9 9 0 11-9-9"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Daily Collections</h3>
            <p class="text-gray-600">Record repayments, print receipts & update portfolio</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition">
            <div class="w-16 h-16 bg-purple-100 rounded-full mx-auto mb-5 flex items-center justify-center">
                <svg class="w-9 h-9 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6m2 0h-2m2 0V9a2 2 0 00-2-2H7a2 2 0 00-2 2v10"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-3">Branch Reports</h3>
            <p class="text-gray-600">Real-time MIS, PAR reports & performance analytics</p>
        </div>

    </div>
</div>

<!-- Simple Footer -->
<footer class="bg-gray-900 text-gray-300 py-10 mt-20">
    <div class="max-w-5xl mx-auto px-6 text-center text-sm">
        <p class="font-medium">Subh Unnati Microfinance Pvt Ltd – Employee Portal</p>
        <p class="mt-2">© {{ date('Y') }} All Rights Reserved | RBI Registered NBFC-MFI</p>
    </div>
</footer>

</body>
</html>
@php
    $user = auth()->user();
    $data = $user->getAllPermissions()->pluck('module')->toArray();
     $redirectRoute = isset($data[0]) ? url($data[0]) : url('/');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-red-100 via-red-200 to-red-300 min-h-screen flex items-center justify-center">
    <div class="text-center p-8 bg-white rounded-3xl shadow-xl max-w-lg mx-4">
        <h1 class="text-9xl font-extrabold text-red-600 mb-4">403</h1>
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Access Denied</h2>
        <p class="text-gray-600 mb-6">
            Oops! You do not have the required permissions to view this page.
        </p>
        <a href="{{ $redirectRoute }}" class="inline-block px-6 py-3 bg-red-600 text-white font-semibold rounded-full shadow hover:bg-red-700 transition">
            Go Home
        </a>
        <div class="mt-8">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Forbidden" class="w-32 mx-auto">
        </div>
    </div>
</body>
</html>

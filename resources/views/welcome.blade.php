<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission - Vacations</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="h-screen bg-cover bg-center relative" style="background-image: url('https://i.redd.it/9kc1t6ey3gy41.jpg');">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-60"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center justify-center h-full text-white text-center">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('assets/images/logo-IFL.png') }}" class="w-3/12" alt="Logo" />
        </div>

        <!-- Buttons -->
        <div class="space-y-4 flex flex-col items-center max-w-xs w-full">
            <a href="{{ url('/vacations') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 w-48 rounded-lg transition duration-300 text-center">
                Vacations
            </a>
            <a href="{{ url('/mission') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 w-48 rounded-lg transition duration-300 text-center">
                Mission
            </a>
        </div>
    </div>
</body>

</html>

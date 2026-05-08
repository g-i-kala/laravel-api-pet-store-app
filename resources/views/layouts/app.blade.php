<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <title>Petstore Client</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CSS (np. przez Vite) --}}
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow mb-4">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <a class="text-xl font-semibold text-gray-800" href="{{ route('pets.index') }}">
                    Petstore
                </a>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('pets.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                        Lista
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Błędy walidacji --}}
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                <strong class="font-semibold">Wystąpiły błędy:</strong>
                <ul class="mt-2 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>

</html>

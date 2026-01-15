<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Merger Data Lahan | Trees4Trees')</title>

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-green-700 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('lands.index') }}" class="flex items-center gap-2 font-semibold text-lg">
                Trees4Trees
            </a>

            <a href="{{ route('lands.index') }}"
               class="text-sm hover:underline">
                Data Lahan
            </a>
        </div>
    </nav>

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t mt-10">
        <div class="max-w-7xl mx-auto px-4 py-4 text-sm text-gray-500 flex justify-between">
            <span>Merger Data GEKO & BHL</span>
            <span>By: <a href="https://github.com/almasrzld" class="hover:underline">Almas Rizaldi</a></span>
        </div>
    </footer>
</body>
</html>

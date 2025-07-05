<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mruyung Guest House & Cafe' }}</title>
    <script src="https://kit.fontawesome.com/ca9af5e3fc.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-slate-100 dark:bg-slate-700">
    @livewire('partials.navbar')
    <main>
        {{ $slot }}
    </main>
    @livewire('partials.footer')
    @livewireScripts
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $title ?? 'petabit' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css'])
    @livewireStyles
    <style>:root{--nb-safe-top:env(safe-area-inset-top,0px);--nb-safe-bottom:env(safe-area-inset-bottom,0px)}</style>
</head>
<body class="bg-gray-950 text-white min-h-screen">

    <main>
        {{ $slot }}
    </main>

    @livewireScripts

    {{-- Rich Petabit genome renderer (tsParticles aura), served locally (offline). --}}
    <script src="/js/tsparticles.bundle.min.js"></script>
    <script src="/js/pet-renderer.js"></script>
    <script>
        (function () {
            function mount() { window.PetabitRenderer && window.PetabitRenderer.mountAll(); }
            document.addEventListener('DOMContentLoaded', mount);
            window.addEventListener('load', mount);
            document.addEventListener('livewire:navigated', mount);
            document.addEventListener('livewire:init', function () {
                if (window.Livewire) { window.Livewire.hook('morphed', function () { mount(); }); }
            });
        })();
    </script>

    <script id="__nb-shell-config" type="application/json">@json($shellConfig ?? [])</script>
</body>
</html>

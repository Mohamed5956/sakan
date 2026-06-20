<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>@yield('title', 'سكن | ابحث عن عقارك المثالي')</title>
    <meta name="description" content="@yield('meta_description', 'سكن - منصة البحث عن العقارات والشقق والغرف في مصر')">
    <meta name="keywords"    content="@yield('meta_keywords', 'عقارات, شقق, غرف, إيجار, سكن')">
    <meta name="robots"      content="index, follow">
    <link rel="canonical"    href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title"       content="@yield('title', 'سكن')">
    <meta property="og:description" content="@yield('meta_description', 'منصة البحث عن العقارات')">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:type"        content="website">
    <meta property="og:locale"      content="ar_EG">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    {{-- Tailwind + Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

@include('layouts.navbar')

{{-- Main Content --}}
<main>
    @yield('content')
</main>

@include('layouts.footer')

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-btn')
        ?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
</script>

@stack('scripts')
</body>
</html>

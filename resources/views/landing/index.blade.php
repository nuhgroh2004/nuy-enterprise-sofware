<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Suite — MRP, CRM, SCM, FICO &amp; HRIS dalam satu sistem</title>
    <meta name="description" content="ERP Suite menyatukan produksi, penjualan, rantai pasok, keuangan, dan SDM dalam satu tampilan sesederhana desktop macOS.">

    @vite(['resources/css/landing.css', 'resources/js/landing.js'])
    <!-- <link rel="stylesheet" href="{{ asset('resources/css/landing.css') }}"> -->
</head>
<body>

    <x-landing.menu-bar />
    <x-landing.navbar />

    <main>
        <x-landing.hero />
        <x-landing.modules-section />
        <x-landing.features-section />
        <x-landing.stats-bar />
        <x-landing.testimonials-section />
        <x-landing.cta-section />
    </main>

    <x-landing.footer />

    <!-- <script src="{{ asset('resources/js/landing.js') }}"></script> -->
</body>
</html>

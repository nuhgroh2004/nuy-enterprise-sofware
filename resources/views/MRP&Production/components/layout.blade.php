<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MRP • {{ $title ?? 'Dashboard' }}</title>

    @vite([
        'resources/css/dashboard.css',
        'resources/js/dashboard.js'
    ])
</head>

<body>

    <div class="desktop">
        <div class="window">

            {{-- TITLE BAR --}}
            @include('MRP&Production.components.titlebar')

            {{-- SIDEBAR BACKDROP --}}
            <div
                class="sidebar-backdrop"
                id="sidebarBackdrop"
            ></div>

            <div class="body-area">

                {{-- SIDEBAR --}}
                @include('MRP&Production.components.sidebar')

                {{-- MAIN CONTENT --}}
                <div class="content">
                    @yield('content')
                </div>

            </div>

        </div>
    </div>

    {{-- DOCK --}}
    @include('components.dock')

</body>
</html>

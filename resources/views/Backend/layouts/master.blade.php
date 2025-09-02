<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
    <title>
        Argon Dashboard 3 PRO by Creative Tim
    </title>
    @include('backend.layouts.inc.style')
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    {{-- sidebar --}}
    @include('backend.layouts.inc.sidebar')
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @include('backend.layouts.inc.navbar')
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            {{-- cards --}}
            {{-- @include('backend.layouts.inc.cards') --}}

            @yield('content')

           {{-- Footer --}}
           @include('backend.layouts.inc.footer')
        </div>
    </main>

    @include('backend.layouts.inc.script')
</body>

</html>

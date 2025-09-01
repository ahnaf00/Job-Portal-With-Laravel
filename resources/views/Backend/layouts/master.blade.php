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
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-7 text-start">
                                    <p class="text-sm mb-1 text-uppercase font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        $230,220
                                    </h5>
                                    <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">+55%
                                        <span class="font-weight-normal text-secondary">since last month</span></span>
                                </div>
                                <div class="col-5">
                                    <div class="dropdown text-end">
                                        <a href="javascript:;" class="cursor-pointer text-secondary"
                                            id="dropdownUsers1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-xs text-secondary">6 May - 7 May</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                                            aria-labelledby="dropdownUsers1">
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7
                                                    days</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last
                                                    week</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30
                                                    days</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mt-sm-0 mt-4">
                    <div class="card">
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-7 text-start">
                                    <p class="text-sm mb-1 text-uppercase font-weight-bold">Customers</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        3.200
                                    </h5>
                                    <span class="text-sm text-end text-success font-weight-bolder mt-auto mb-0">+12%
                                        <span class="font-weight-normal text-secondary">since last month</span></span>
                                </div>
                                <div class="col-5">
                                    <div class="dropdown text-end">
                                        <a href="javascript:;" class="cursor-pointer text-secondary"
                                            id="dropdownUsers2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-xs text-secondary">6 May - 7 May</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                                            aria-labelledby="dropdownUsers2">
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7
                                                    days</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last
                                                    week</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30
                                                    days</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mt-sm-0 mt-4">
                    <div class="card">
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-7 text-start">
                                    <p class="text-sm mb-1 text-uppercase font-weight-bold">Avg. Revenue</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        $1.200
                                    </h5>
                                    <span class="font-weight-normal text-secondary text-sm"><span
                                            class="font-weight-bolder">+$213</span> since last month</span>
                                </div>
                                <div class="col-5">
                                    <div class="dropdown text-end">
                                        <a href="javascript:;" class="cursor-pointer text-secondary"
                                            id="dropdownUsers3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-xs text-secondary">6 May - 7 May</span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3"
                                            aria-labelledby="dropdownUsers3">
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 7
                                                    days</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last
                                                    week</a></li>
                                            <li><a class="dropdown-item border-radius-md" href="javascript:;">Last 30
                                                    days</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer pt-3  ">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold"
                                    target="_blank">Creative Tim</a>
                                for a better web.
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com" class="nav-link text-muted"
                                        target="_blank">Creative Tim</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted"
                                        target="_blank">About Us</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/blog" class="nav-link text-muted"
                                        target="_blank">Blog</a>
                                </li>
                                <li class="nav-item">
                                    <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted"
                                        target="_blank">License</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>

    @include('backend.layouts.inc.script')
</body>

</html>

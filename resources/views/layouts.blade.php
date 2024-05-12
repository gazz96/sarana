<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ url('css/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastify.min.css') }}" rel="stylesheet">
    <style>
        .table tr .tr-actions {
            display: none !important;
        }

        .table tr:hover .tr-actions {
            display: flex !important;
        }
    </style>
</head>

<body class="vh-100">

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
                <div class="offcanvas-md offcanvas-end bg-body-tertiary" id="offcanvas-sidebar">
                    <div class="offcanvas-body d-flex d-md-flex flex-shrink-0 flex-column p-0 pt-lg-3 overflow-y-auto vh-100">
                        <ul class="nav flex-column mb-auto">
                            <li class="nav-item">
                                <a href="{{ route('dashboard.index') }}"
                                    class="nav-link d-flex align-items-center gap-2">Dashboard</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('goods.index') }}"
                                    class="nav-link d-flex align-items-center gap-2">Invetaris</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('locations.index') }}"
                                    class="nav-link d-flex align-items-center gap-2">Lokasi</a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('problems.index') }}"
                                    class="nav-link d-flex align-items-center gap-2">Masalah</a>
                            </li>


                            <li class="nav-item">
                                <a href="{{ route('users.index') }}"
                                    class="nav-link d-flex align-items-center gap-2">Pegawai</a>
                            </li>
                        </ul>
                        <hr>
                        <div class="dropdown px-3 mb-3">
                            <a href="#"
                                class="d-flex align-items-center text-decoration-none dropdown-toggle show text-dark"
                                data-bs-toggle="dropdown" aria-expanded="true">
                                <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                                    class="rounded-circle me-2">
                                <strong>mdo</strong>
                            </a>
                            <ul class="dropdown-menu text-small shadow" data-popper-placement="top-start">
                                <li><a class="dropdown-item" href="#">New project...</a></li>
                                <li><a class="dropdown-item" href="#">Settings</a></li>
                                <li><a class="dropdown-item" href="#">Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Sign out</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-lg-10">
                @yield('content')
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/toastify.min.js') }}"></script>

    @yield('footer')


</body>

</html>

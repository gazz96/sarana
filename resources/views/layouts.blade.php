<!DOCTYPE html>
<!--
  HOW TO USE:
  data-layout: fluid (default), boxed
  data-sidebar-theme: dark (default), colored, light
  data-sidebar-position: left (default), right
  data-sidebar-behavior: sticky (default), fixed, compact
-->
<html lang="en" data-bs-theme="light" data-layout="boxed" data-sidebar-theme="light" data-sidebar-position="left"
    data-sidebar-behavior="sticky">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Template">
    <meta name="author" content="Bagas Topati">

    <title>Sarana & Prasana Sekolah</title>

    <link rel="canonical" href="https://instagram.com/bagas.topati" />
    <link rel="shortcut icon" href="img/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="{{ url('css/custom-main.css') }}" rel="stylesheet">
    <link href="{{ url('appstack/css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- BEGIN SETTINGS -->
    <!-- Remove this after purchasing -->
    {{-- <script src="{{ url('appstack/js/settings.js') }}"></script> --}}
    <!-- END SETTINGS -->

    
    @yield('header')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-content js-simplebar">
                @if($app_logo = $option->getByKey('app_logo'))
                <a class="sidebar-brand" href="{{url('dashboard')}}">
                    <img src="{{url("uploads/{$app_logo}")}}" alt="Logo" class="img-fluid">
                </a>
                @endif

                <ul class="sidebar-nav">
                    <li class="sidebar-header fw-semibold">
                        Navigasi
                    </li>
                   
                    @if(auth()->user()->hasRole(['admin', 'super user']))

                    <li class="sidebar-item">
                        <a href="{{url('dashboard')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('goods.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="list"></i>
                            <span>Inventaris</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('locations.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="file"></i>
                            <span>Lokasi</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('problems.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="alert-triangle"></i>
                            <span>Masalah</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{route('users.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="users"></i>
                            <span>Pegawai</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('profile')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="user-check"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a data-bs-target="#navbar-reports" data-bs-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-lucide="pie-chart"></i> <span
                                class="align-middle">Laporan</span>
                            {{-- <span class="badge badge-sidebar-primary">5</span> --}}
                        </a>
                        <ul id="navbar-reports" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{route('reports.problem')}}">Masalah</a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{route('reports.finance')}}">Keuangan</a>
                            </li>
                            {{-- <li class="sidebar-item"><a class="sidebar-link" href="{{route('settings.approval')}}">Persetujuan</a></li> --}}
                        </ul>
                    </li>
                
                    <li class="sidebar-item">
                        <a data-bs-target="#navbar-settings" data-bs-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-lucide="sliders"></i> <span
                                class="align-middle">Pengaturan</span>
                            {{-- <span class="badge badge-sidebar-primary">5</span> --}}
                        </a>
                        <ul id="navbar-settings" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{route('settings.general')}}">Umum</a>
                            </li>
                            {{-- <li class="sidebar-item"><a class="sidebar-link" href="{{route('settings.approval')}}">Persetujuan</a></li> --}}
                        </ul>
                    </li>
                  
                    @endif

                    @if(auth()->user()->hasRole('teknisi'))

                    <li class="sidebar-item">
                        <a href="{{url('dashboard')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    
                    <li class="sidebar-item">
                        <a href="{{route('problems.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="alert-triangle"></i>
                            <span>Masalah</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('profile')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="user-check"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                  
                    @endif

                    @if(auth()->user()->hasRole(['lembaga', 'keuangan']))

                    <li class="sidebar-item">
                        <a href="{{url('dashboard')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    
                    <li class="sidebar-item">
                        <a href="{{route('problems.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="alert-triangle"></i>
                            <span>Masalah</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('profile')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="user-check"></i>
                            <span>Profile</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a data-bs-target="#navbar-reports" data-bs-toggle="collapse" class="sidebar-link collapsed">
                            <i class="align-middle" data-lucide="pie-chart"></i> <span
                                class="align-middle">Laporan</span>
                            {{-- <span class="badge badge-sidebar-primary">5</span> --}}
                        </a>
                        <ul id="navbar-reports" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{route('reports.problem')}}">Masalah</a>
                            </li>
                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{route('reports.finance')}}">Keuangan</a>
                            </li>
                            {{-- <li class="sidebar-item"><a class="sidebar-link" href="{{route('settings.approval')}}">Persetujuan</a></li> --}}
                        </ul>
                    </li>
                  
                    @endif


                    @if(auth()->user()->hasRole('guru'))

                    <li class="sidebar-item">
                        <a href="{{url('dashboard')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    
                    <li class="sidebar-item">
                        <a href="{{route('problems.index')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="alert-triangle"></i>
                            <span>Masalah</span>
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="{{route('profile')}}" class="sidebar-link">
                            <i class="align-middle text-body" data-lucide="user-check"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                  
                    @endif

                </ul>

               
            </div>
        </nav>
        <div class="main">
            <nav class="navbar navbar-expand navbar-bg">
                <a class="sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

        

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown"
                                data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle text-body" data-lucide="message-circle"></i>
                                    <span class="indicator">4</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0"
                                aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        4 New Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="img/avatars/avatar-5.jpg" class="img-fluid rounded-circle"
                                                    alt="Ashley Briggs" width="40" height="40">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div>Ashley Briggs</div>
                                                <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis
                                                    arcu tortor.</div>
                                                <div class="text-muted small mt-1">15m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="img/avatars/avatar-2.jpg" class="img-fluid rounded-circle"
                                                    alt="Carl Jenkins" width="40" height="40">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div>Carl Jenkins</div>
                                                <div class="text-muted small mt-1">Curabitur ligula sapien euismod
                                                    vitae.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="img/avatars/avatar-4.jpg" class="img-fluid rounded-circle"
                                                    alt="Stacie Hall" width="40" height="40">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div>Stacie Hall</div>
                                                <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.
                                                </div>
                                                <div class="text-muted small mt-1">4h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="img/avatars/avatar-3.jpg" class="img-fluid rounded-circle"
                                                    alt="Bertha Martin" width="40" height="40">
                                            </div>
                                            <div class="col-10 ps-2">
                                                <div>Bertha Martin</div>
                                                <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed,
                                                    posuere ac, mattis non.</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all messages</a>
                                </div>
                            </div>
                        </li>
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown"
                                data-bs-toggle="dropdown">
                                <div class="position-relative">
                                    <i class="align-middle text-body" data-lucide="bell-off"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0"
                                aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    4 New Notifications
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-danger" data-lucide="alert-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div>Update completed</div>
                                                <div class="text-muted small mt-1">Restart server 12 to complete the
                                                    update.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-lucide="bell"></i>
                                            </div>
                                            <div class="col-10">
                                                <div>Lorem ipsum</div>
                                                <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate
                                                    hendrerit et.</div>
                                                <div class="text-muted small mt-1">6h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-lucide="home"></i>
                                            </div>
                                            <div class="col-10">
                                                <div>Login from 192.186.1.1</div>
                                                <div class="text-muted small mt-1">8h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-lucide="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div>New connection</div>
                                                <div class="text-muted small mt-1">Anna accepted your request.</div>
                                                <div class="text-muted small mt-1">12h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li> --}}
                        {{-- <li class="nav-item nav-theme-toggle dropdown">
                            <a class="nav-icon js-theme-toggle" href="#">
                                <div class="position-relative">
                                    <i class="align-middle text-body nav-theme-toggle-light" data-lucide="sun"></i>
                                    <i class="align-middle text-body nav-theme-toggle-dark" data-lucide="moon"></i>
                                </div>
                            </a>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                                data-bs-toggle="dropdown">
                                <i class="align-middle" data-lucide="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" 
                                href="#"
                                data-bs-toggle="dropdown">
                                {{-- <img src="img/avatars/avatar.jpg" class="img-fluid rounded-circle me-1 mt-n2 mb-n2"
                                    alt="Chris Wood" width="40" height="40" />  --}}
                                <span>{{auth()->user()->name ?? '-'}}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{route('profile')}}"><i class="align-middle me-1"
                                        data-lucide="user"></i> Profile</a>
                                {{-- <a class="dropdown-item" href="#"><i class="align-middle me-1" data-lucide="pie-chart"></i> Analytics</a> --}}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('settings.general')}}">Pengaturan</a>
                                {{-- <a class="dropdown-item" href="#">Help</a> --}}
                                <a class="dropdown-item" href="{{route('logout')}}">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                {{-- <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">Blank Page</h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Empty card</h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>
                        </div>
                    </div>

                </div> --}}
                @yield('content')
            </main>

            {{-- <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Support</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Help Center</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Privacy</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#">Terms of Service</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-0">
                                &copy; 2024</a>
                            </p>
                        </div>
                    </div>
                </div>
            </footer> --}}
        </div>
    </div>

    <script src="{{ url('appstack/js/jquery.min.js') }}"></script>
    <script src="{{ url('appstack/js/app.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    @yield('footer')
    

</body>

</html>

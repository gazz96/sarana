<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sarana & Prasarana Sekolah Dashboard">
    <meta name="author" content="Bagas Topati">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SARANAS - Sistem Manajemen Sarana Prasarana')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with DaisyUI - Correct Order -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.19/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'sm': '640px',
                        'md': '768px', 
                        'lg': '1024px',
                        'xl': '1280px',
                        '2xl': '1536px',
                    },
                    colors: {
                        saranas: {
                            blue: '#295EA4',
                            yellow: '#FFCB4F',
                            'blue-light': '#4A7DB8',
                            'blue-dark': '#1E4578',
                            'yellow-light': '#FFE070',
                            'yellow-dark': '#E5B040',
                        }
                    }
                }
            },
            daisyui: {
                themes: [
                    {
                        saranas: {
                            "primary": "#295EA4",
                            "primary-focus": "#1E4578",
                            "primary-content": "#ffffff",
                            "secondary": "#FFCB4F",
                            "secondary-focus": "#E5B040",
                            "secondary-content": "#1E4578",
                            "accent": "#4A7DB8",
                            "accent-focus": "#295EA4",
                            "accent-content": "#ffffff",
                            "neutral": "#1E4578",
                            "neutral-focus": "#15294e",
                            "neutral-content": "#ffffff",
                            "base-100": "#ffffff",
                            "base-200": "#f5f5f5",
                            "base-300": "#e5e5e5",
                            "base-content": "#1E4578",
                            "info": "#295EA4",
                            "success": "#22c55e",
                            "warning": "#FFCB4F",
                            "error": "#ef4444",
                        },
                    },
                ],
            }
        }
    </script>
    
    <!-- Chart.js for Dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: #f5f5f5;
        }
        
        /* DashPro Sidebar Styling */
        .sidebar-fixed {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 10;
            height: 100vh;
            width: 250px;
            background: white;
            padding: 1.5rem 1rem 1rem 1rem;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }
        
        .sidebar-content {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        @media (max-width: 1023px) {
            .sidebar-fixed {
                display: none;
            }
        }
        
        @media (min-width: 1024px) {
            .sidebar-fixed {
                display: block;
            }
            
            .main-content {
                padding-left: 250px;
            }
        }
        
        aside {
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }
        
        .logo svg path.ccustom {
            fill: #5417D7;
        }
    </style>

    @stack('head')
    @yield('header')
</head>

<body class="bg-base-200" data-theme="saranas">
    <!-- menu on navbar mobile view -->
    <div class="flex flex-row justify-between lg:hidden bg-white p-5">
        <div class="logo flex flex-row justify-center items-center gap-x-2">
            @if(false)
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="ccustom" fill-rule="evenodd" clip-rule="evenodd"
                        d="M10 0C15.5228 0 20 4.47715 20 10V0H30C35.5228 0 40 4.47715 40 10C40 15.5228 35.5228 20 30 20C35.5228 20 40 24.4772 40 30C40 32.7423 38.8961 35.2268 37.1085 37.0334L37.0711 37.0711L37.0379 37.1041C35.2309 38.8943 32.7446 40 30 40C27.2741 40 24.8029 38.9093 22.999 37.1405C22.9756 37.1175 22.9522 37.0943 22.9289 37.0711C22.907 37.0492 22.8852 37.0272 22.8635 37.0051C21.0924 35.2009 20 32.728 20 30C20 35.5228 15.5228 40 10 40C4.47715 40 0 35.5228 0 30V20H10C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0ZM18 10C18 14.4183 14.4183 18 10 18V2C14.4183 2 18 5.58172 18 10ZM38 30C38 25.5817 34.4183 22 30 22C25.5817 22 22 25.5817 22 30H38ZM2 22V30C2 34.4183 5.58172 38 10 38C14.4183 38 18 34.4183 18 30V22H2ZM22 18V2L30 2C34.4183 2 38 5.58172 38 10C38 14.4183 34.4183 18 30 18H22Z"
                        fill="#5417D7"></path>
                </svg>
                <h2 class="font-bold text-2xl" style="color: #295EA4;">SARANAS</h2>
            @endif
        </div>
        <a href="#" id="btn-dropdown" class="flex flex-row items-center p-2 border border-gray-300 rounded-full">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 7H21" stroke="#1E4578" stroke-width="2" stroke-linecap="round" />
                <path d="M3 12H21" stroke="#1E4578" stroke-width="2" stroke-linecap="round" />
                <path d="M3 17H21" stroke="#1E4578" stroke-width="2" stroke-linecap="round" />
            </svg>
        </a>
    </div>
    <!-- end menu mobile -->

    <!-- floating menu navigation on mobile -->
    <div id="dropdown-menu" class="lg:hidden hidden flex fixed flex-col gap-y-4 absolute left-0 top-[72px] bg-white w-screen h-auto p-6 z-20 shadow-lg">
        <div class="flex flex-col md:flex-row gap-x-24 gap-y-4">
            <div class="flex flex-col gap-y-4">
                <h6 class="text-sm text-gray-400 font-semibold">MENU UTAMA</h6>
                <ul class="flex flex-col gap-y-4">
                    <li>
                        <a href="{{ url('dashboard') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('dashboard') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="{{ request()->is('dashboard') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.44" d="M7.33008 14.4898L9.71008 11.3998C10.0501 10.9598 10.6801 10.8798 11.1201 11.2198L12.9501 12.6598C13.3901 12.9998 14.0201 12.9198 14.3601 12.4898L16.6701 9.50977" stroke="{{ request()->is('dashboard') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->hasRole(['admin', 'super user']))
                        <li>
                            <a href="{{ route('goods.index') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('goods*') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.0008 22H16.0008C20.0208 22 20.7408 20.39 20.9508 18.43L21.7008 10.43C21.9708 7.99 21.2708 6 17.0008 6H7.0008C2.7308 6 2.0308 7.99 2.3008 10.43L3.0508 18.43C3.2608 20.39 3.9808 22 8.0008 22Z" stroke="{{ request()->is('goods*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path opacity="0.4" d="M8 6V5.2C8 3.43 8 2 11.2 2H12.8C16 2 16 3.43 16 5.2V6" stroke="{{ request()->is('goods*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Inventaris
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('locations.index') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('locations*') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="{{ request()->is('locations*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="{{ request()->is('locations*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 2V6" stroke="{{ request()->is('locations*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 18V22" stroke="{{ request()->is('locations*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Lokasi
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('problems.index') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('problems*') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.9 2.10001L8.10001 3.60001C7.50001 3.80001 7.10001 4.40001 7.10001 5.10001V8.40001C7.10001 12.4 9.60001 15.9 13.1 17.3L13.5 17.5C14.1 17.7 14.7 17.5 15.1 17.1L19.7 12.5C20.3 11.9 20.3 11 19.7 10.4L14.9 5.60001C14.3 5.00001 13.4 4.90001 12.9 5.40001L12.9 2.10001Z" stroke="{{ request()->is('problems*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path opacity="0.4" d="M7.10001 8.40001L2.20001 13.3C1.60001 13.9 1.60001 14.8 2.20001 15.4L7.00001 20.2C7.60001 20.8 8.50001 20.8 9.10001 20.2L14 15.3" stroke="{{ request()->is('problems*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Masalah
                        </a>
                    </li>
                </ul>
            </div>
            <div class="flex flex-col gap-y-4">
                <h6 class="text-sm text-gray-400 font-semibold">LAINNYA</h6>
                <ul class="flex flex-col gap-y-4">
                    <li>
                        <a href="{{ route('profile') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('profile') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.0005 12C13.8305 12 15.1805 10.51 15.0005 8.68L14.3405 2H9.67048L9.00048 8.68C8.82048 10.51 10.1705 12 12.0005 12Z" stroke="{{ request()->is('profile') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M18.3108 12C20.3308 12 21.8108 10.36 21.6108 8.35L21.3308 5.6C20.9708 3 19.9708 2 17.3508 2H14.3008L15.0008 9.01C15.1708 10.66 16.6608 12 18.3108 12Z" stroke="{{ request()->is('profile') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5.64037 12C7.29037 12 8.78037 10.66 8.94037 9.01L9.16037 6.8L9.64037 2H6.59037C3.97037 2 2.97037 3 2.61037 5.6L2.34037 8.35C2.14037 10.36 3.62037 12 5.64037 12Z" stroke="{{ request()->is('profile') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <g opacity="0.4">
                                    <path d="M3.00977 11.2197V15.7097C3.00977 20.1997 4.80977 21.9997 9.29977 21.9997H14.6898C19.1798 21.9997 20.9798 20.1997 20.9798 15.7097V11.2197" stroke="{{ request()->is('profile') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="{{ request()->is('profile') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </g>
                            </svg>
                            Profile
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->hasRole(['admin', 'super user']))
                        <li>
                            <a href="{{ route('settings.general') }}" class="flex flex-row gap-x-2 font-semibold text-base" style="{{ request()->is('settings*') ? 'color: #295EA4;' : 'color: #1E4578;' }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.34" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="{{ request()->is('settings*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M2 12.8799V11.1199C2 10.0799 2.85 9.21994 3.9 9.21994C5.71 9.21994 6.45 7.93994 5.54 6.36994C5.02 5.46994 5.33 4.29994 6.24 3.77994L7.97 2.78994C8.76 2.31994 9.78 2.59994 10.25 3.38994L10.36 3.57994C11.26 5.14994 12.74 5.14994 13.65 3.57994L13.76 3.38994C14.23 2.59994 15.25 2.31994 16.04 2.78994L17.77 3.77994C18.68 4.29994 18.99 5.46994 18.47 6.36994C17.56 7.93994 18.3 9.21994 20.11 9.21994C21.15 9.21994 22.01 10.0699 22.01 11.1199V12.8799C22.01 13.9199 21.16 14.7799 20.11 14.7799C18.3 14.7799 17.56 16.0599 18.47 17.6299C18.99 18.5399 18.68 19.6999 17.77 20.2199L16.04 21.2099C15.25 21.6799 14.23 21.3999 13.76 20.6099L13.65 20.4199C12.75 18.8499 11.27 18.8499 10.36 20.4199L10.25 20.6099C9.78 21.3999 8.76 21.6799 7.97 21.2099L6.24 20.2199C5.33 19.6999 5.02 18.5299 5.54 17.6299C6.45 16.0599 5.71 14.7799 3.9 14.7799C2.85 14.7799 2 13.9199 2 12.8799Z" stroke="{{ request()->is('settings*') ? '#295EA4' : '#1E4578' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Pengaturan
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!-- end menu navigation -->

    <div class="flex flex-row justify-start">
        <div class="sidebar-fixed bg-base-100 shadow-xl">
            <div class="sidebar-content">
                <div class="logo flex flex-row justify-center items-center gap-x-2 py-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%);">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    <h2 class="font-bold text-2xl" style="color: #295EA4;">SARANAS</h2>
                </div>
                <div class="flex flex-col gap-y-4">
                    @if(auth()->check() && auth()->user()->hasRole(['admin', 'super user']))
                        <div class="flex flex-col gap-y-4">
                            <h6 class="text-sm text-gray-400 font-semibold">MENU UTAMA</h6>
                            <ul class="flex flex-col gap-y-2">
                                <li>
                                    <a href="{{ url('dashboard') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('dashboard') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.44" d="M7.33008 14.4898L9.71008 11.3998C10.0501 10.9598 10.6801 10.8798 11.1201 11.2198L12.9501 12.6598C13.3901 12.9998 14.0201 12.9198 14.3601 12.4898L16.6701 9.50977" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('goods.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('goods*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('goods*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8.0008 22H16.0008C20.0208 22 20.7408 20.39 20.9508 18.43L21.7008 10.43C21.9708 7.99 21.2708 6 17.0008 6H7.0008C2.7308 6 2.0308 7.99 2.3008 10.43L3.0508 18.43C3.2608 20.39 3.9808 22 8.0008 22Z" stroke="{{ request()->is('goods*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M8 6V5.2C8 3.43 8 2 11.2 2H12.8C16 2 16 3.43 16 5.2V6" stroke="{{ request()->is('goods*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Inventaris</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('locations.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('locations*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('locations*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="{{ request()->is('locations*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 14C13.1046 14 14 13.1046 14 12C14 10.8954 13.1046 10 12 10C10.8954 10 10 10.8954 10 12C10 13.1046 10.8954 14 12 14Z" stroke="{{ request()->is('locations*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 2V6" stroke="{{ request()->is('locations*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 18V22" stroke="{{ request()->is('locations*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Lokasi</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('problems.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('problems*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('problems*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9 2.10001L8.10001 3.60001C7.50001 3.80001 7.10001 4.40001 7.10001 5.10001V8.40001C7.10001 12.4 9.60001 15.9 13.1 17.3L13.5 17.5C14.1 17.7 14.7 17.5 15.1 17.1L19.7 12.5C20.3 11.9 20.3 11 19.7 10.4L14.9 5.60001C14.3 5.00001 13.4 4.90001 12.9 5.40001L12.9 2.10001Z" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7.10001 8.40001L2.20001 13.3C1.60001 13.9 1.60001 14.8 2.20001 15.4L7.00001 20.2C7.60001 20.8 8.50001 20.8 9.10001 20.2L14 15.3" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Masalah</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('users.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('users*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('users*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.16055 10.87C9.06055 10.86 8.94055 10.86 8.83055 10.87C6.45055 10.79 4.56055 8.84 4.56055 6.44C4.56055 3.99 6.54055 2 9.00055 2C11.4505 2 13.4405 3.99 13.4405 6.44C13.4305 8.84 11.5405 10.79 9.16055 10.87Z" stroke="{{ request()->is('users*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M16.4093 4C18.3493 4 19.9093 5.57 19.9093 7.5C19.9093 9.39 18.4093 10.93 16.5393 11C16.4593 10.99 16.3693 10.99 16.2793 11" stroke="{{ request()->is('users*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M4.1607 14.56C1.7407 16.18 1.7407 18.82 4.1607 20.43C6.9107 22.27 11.4207 22.27 14.1707 20.43C16.5907 18.81 16.5907 16.17 14.1707 14.56C11.4307 12.73 6.9207 12.73 4.1607 14.56Z" stroke="{{ request()->is('users*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M18.3398 20C19.0598 19.85 19.7398 19.56 20.2998 19.13C21.8598 17.96 21.8598 16.03 20.2998 14.86C19.7498 14.44 19.0798 14.16 18.3698 14" stroke="{{ request()->is('users*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Pegawai</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('profile') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('profile') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0005 12C13.8305 12 15.1805 10.51 15.0005 8.68L14.3405 2H9.67048L9.00048 8.68C8.82048 10.51 10.1705 12 12.0005 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18.3108 12C20.3308 12 21.8108 10.36 21.6108 8.35L21.3308 5.6C20.9708 3 19.9708 2 17.3508 2H14.3008L15.0008 9.01C15.1708 10.66 16.6608 12 18.3108 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.64037 12C7.29037 12 8.78037 10.66 8.94037 9.01L9.16037 6.8L9.64037 2H6.59037C3.97037 2 2.97037 3 2.61037 5.6L2.34037 8.35C2.14037 10.36 3.62037 12 5.64037 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <g opacity="0.4">
                                                <path d="M3.00977 11.2197V15.7097C3.00977 20.1997 4.80977 21.9997 9.29977 21.9997H14.6898C19.1798 21.9997 20.9798 20.1997 20.9798 15.7097V11.2197" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="flex flex-col gap-y-4">
                            <h6 class="text-sm text-base-content/50 font-semibold uppercase tracking-wider">Laporan & Pengaturan</h6>
                            <ul class="flex flex-col gap-y-2">
                                <li>
                                    <a href="{{ route('reports.problem') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('reports*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('reports*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.6707 14.3L21.2707 19.3C21.1207 20.83 21.0007 22 18.2907 22H5.71074C3.00074 22 2.88074 20.83 2.73074 19.3L2.33074 14.3C2.25074 13.47 2.51074 12.7 2.98074 12.11C2.99074 12.1 2.99074 12.1 3.00074 12.09C3.55074 11.42 4.38074 11 5.31074 11H18.6907C19.6207 11 20.4407 11.42 20.9807 12.07C20.9907 12.08 21.0007 12.09 21.0007 12.1C21.4907 12.69 21.7607 13.46 21.6707 14.3Z" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" />
                                            <path opacity="0.4" d="M3.5 11.4298V6.27979C3.5 2.87979 4.35 2.02979 7.75 2.02979H9.02C10.29 2.02979 10.58 2.40979 11.06 3.04979L12.33 4.74979C12.65 5.16979 12.84 5.42979 13.69 5.42979H16.24C19.64 5.42979 20.49 6.27979 20.49 9.67979V11.4698" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M9.42969 17H14.5697" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Laporan</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('settings.general') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('settings*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('settings*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.34" d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="{{ request()->is('settings*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M2 12.8799V11.1199C2 10.0799 2.85 9.21994 3.9 9.21994C5.71 9.21994 6.45 7.93994 5.54 6.36994C5.02 5.46994 5.33 4.29994 6.24 3.77994L7.97 2.78994C8.76 2.31994 9.78 2.59994 10.25 3.38994L10.36 3.57994C11.26 5.14994 12.74 5.14994 13.65 3.57994L13.76 3.38994C14.23 2.59994 15.25 2.31994 16.04 2.78994L17.77 3.77994C18.68 4.29994 18.99 5.46994 18.47 6.36994C17.56 7.93994 18.3 9.21994 20.11 9.21994C21.15 9.21994 22.01 10.0699 22.01 11.1199V12.8799C22.01 13.9199 21.16 14.7799 20.11 14.7799C18.3 14.7799 17.56 16.0599 18.47 17.6299C18.99 18.5399 18.68 19.6999 17.77 20.2199L16.04 21.2099C15.25 21.6799 14.23 21.3999 13.76 20.6099L13.65 20.4199C12.75 18.8499 11.27 18.8499 10.36 20.4199L10.25 20.6099C9.78 21.3999 8.76 21.6799 7.97 21.2099L6.24 20.2199C5.33 19.6999 5.02 18.5299 5.54 17.6299C6.45 16.0599 5.71 14.7799 3.9 14.7799C2.85 14.7799 2 13.9199 2 12.8799Z" stroke="{{ request()->is('settings*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Pengaturan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->hasRole('teknisi'))
                        <div class="flex flex-col gap-y-4">
                            <h6 class="text-sm text-base-content/50 font-semibold uppercase tracking-wider">MENU TEKNISI</h6>
                            <ul class="flex flex-col gap-y-2">
                                <li>
                                    <a href="{{ url('dashboard') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('dashboard') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.44" d="M7.33008 14.4898L9.71008 11.3998C10.0501 10.9598 10.6801 10.8798 11.1201 11.2198L12.9501 12.6598C13.3901 12.9998 14.0201 12.9198 14.3601 12.4898L16.6701 9.50977" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('problems.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('problems*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('problems*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9 2.10001L8.10001 3.60001C7.50001 3.80001 7.10001 4.40001 7.10001 5.10001V8.40001C7.10001 12.4 9.60001 15.9 13.1 17.3L13.5 17.5C14.1 17.7 14.7 17.5 15.1 17.1L19.7 12.5C20.3 11.9 20.3 11 19.7 10.4L14.9 5.60001C14.3 5.00001 13.4 4.90001 12.9 5.40001L12.9 2.10001Z" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7.10001 8.40001L2.20001 13.3C1.60001 13.9 1.60001 14.8 2.20001 15.4L7.00001 20.2C7.60001 20.8 8.50001 20.8 9.10001 20.2L14 15.3" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Masalah</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('profile') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('profile') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0005 12C13.8305 12 15.1805 10.51 15.0005 8.68L14.3405 2H9.67048L9.00048 8.68C8.82048 10.51 10.1705 12 12.0005 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18.3108 12C20.3308 12 21.8108 10.36 21.6108 8.35L21.3308 5.6C20.9708 3 19.9708 2 17.3508 2H14.3008L15.0008 9.01C15.1708 10.66 16.6608 12 18.3108 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.64037 12C7.29037 12 8.78037 10.66 8.94037 9.01L9.16037 6.8L9.64037 2H6.59037C3.97037 2 2.97037 3 2.61037 5.6L2.34037 8.35C2.14037 10.36 3.62037 12 5.64037 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <g opacity="0.4">
                                                <path d="M3.00977 11.2197V15.7097C3.00977 20.1997 4.80977 21.9997 9.29977 21.9997H14.6898C19.1798 21.9997 20.9798 20.1997 20.9798 15.7097V11.2197" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->hasRole(['lembaga', 'keuangan']))
                        <div class="flex flex-col gap-y-4">
                            <h6 class="text-sm text-gray-400 font-semibold">MENU MANAJEMEN</h6>
                            <ul class="flex flex-col gap-y-2">
                                <li>
                                    <a href="{{ url('dashboard') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('dashboard') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.44" d="M7.33008 14.4898L9.71008 11.3998C10.0501 10.9598 10.6801 10.8798 11.1201 11.2198L12.9501 12.6598C13.3901 12.9998 14.0201 12.9198 14.3601 12.4898L16.6701 9.50977" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('problems.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('problems*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('problems*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9 2.10001L8.10001 3.60001C7.50001 3.80001 7.10001 4.40001 7.10001 5.10001V8.40001C7.10001 12.4 9.60001 15.9 13.1 17.3L13.5 17.5C14.1 17.7 14.7 17.5 15.1 17.1L19.7 12.5C20.3 11.9 20.3 11 19.7 10.4L14.9 5.60001C14.3 5.00001 13.4 4.90001 12.9 5.40001L12.9 2.10001Z" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7.10001 8.40001L2.20001 13.3C1.60001 13.9 1.60001 14.8 2.20001 15.4L7.00001 20.2C7.60001 20.8 8.50001 20.8 9.10001 20.2L14 15.3" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Masalah</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('profile') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('profile') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0005 12C13.8305 12 15.1805 10.51 15.0005 8.68L14.3405 2H9.67048L9.00048 8.68C8.82048 10.51 10.1705 12 12.0005 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18.3108 12C20.3308 12 21.8108 10.36 21.6108 8.35L21.3308 5.6C20.9708 3 19.9708 2 17.3508 2H14.3008L15.0008 9.01C15.1708 10.66 16.6608 12 18.3108 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.64037 12C7.29037 12 8.78037 10.66 8.94037 9.01L9.16037 6.8L9.64037 2H6.59037C3.97037 2 2.97037 3 2.61037 5.6L2.34037 8.35C2.14037 10.36 3.62037 12 5.64037 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <g opacity="0.4">
                                                <path d="M3.00977 11.2197V15.7097C3.00977 20.1997 4.80977 21.9997 9.29977 21.9997H14.6898C19.1798 21.9997 20.9798 20.1997 20.9798 15.7097V11.2197" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('reports.problem') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('reports*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('reports*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.6707 14.3L21.2707 19.3C21.1207 20.83 21.0007 22 18.2907 22H5.71074C3.00074 22 2.88074 20.83 2.73074 19.3L2.33074 14.3C2.25074 13.47 2.51074 12.7 2.98074 12.11C2.99074 12.1 2.99074 12.1 3.00074 12.09C3.55074 11.42 4.38074 11 5.31074 11H18.6907C19.6207 11 20.4407 11.42 20.9807 12.07C20.9907 12.08 21.0007 12.09 21.0007 12.1C21.4907 12.69 21.7607 13.46 21.6707 14.3Z" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" />
                                            <path opacity="0.4" d="M3.5 11.4298V6.27979C3.5 2.87979 4.35 2.02979 7.75 2.02979H9.02C10.29 2.02979 10.58 2.40979 11.06 3.04979L12.33 4.74979C12.65 5.16979 12.84 5.42979 13.69 5.42979H16.24C19.64 5.42979 20.49 6.27979 20.49 9.67979V11.4698" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M9.42969 17H14.5697" stroke="{{ request()->is('reports*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Laporan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    @if(auth()->check() && auth()->user()->hasRole('guru'))
                        <div class="flex flex-col gap-y-4">
                            <h6 class="text-sm text-gray-400 font-semibold">MENU GURU</h6>
                            <ul class="flex flex-col gap-y-2">
                                <li>
                                    <a href="{{ url('dashboard') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('dashboard') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('dashboard') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.44" d="M7.33008 14.4898L9.71008 11.3998C10.0501 10.9598 10.6801 10.8798 11.1201 11.2198L12.9501 12.6598C13.3901 12.9998 14.0201 12.9198 14.3601 12.4898L16.6701 9.50977" stroke="{{ request()->is('dashboard') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('problems.index') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('problems*') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('problems*') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.9 2.10001L8.10001 3.60001C7.50001 3.80001 7.10001 4.40001 7.10001 5.10001V8.40001C7.10001 12.4 9.60001 15.9 13.1 17.3L13.5 17.5C14.1 17.7 14.7 17.5 15.1 17.1L19.7 12.5C20.3 11.9 20.3 11 19.7 10.4L14.9 5.60001C14.3 5.00001 13.4 4.90001 12.9 5.40001L12.9 2.10001Z" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                            <path opacity="0.4" d="M7.10001 8.40001L2.20001 13.3C1.60001 13.9 1.60001 14.8 2.20001 15.4L7.00001 20.2C7.60001 20.8 8.50001 20.8 9.10001 20.2L14 15.3" stroke="{{ request()->is('problems*') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Masalah</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('profile') }}" class="flex flex-row gap-x-3 font-semibold text-base px-4 py-3 rounded-xl transition-all duration-200 {{ request()->is('profile') ? 'text-white shadow-lg' : 'text-base-content/70 hover:bg-base-200' }}" {{ request()->is('profile') ? 'style="background: linear-gradient(135deg, #295EA4 0%, #1E4578 100%); box-shadow: 0 4px 15px rgba(41, 94, 164, 0.3);"' : '' }}>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0005 12C13.8305 12 15.1805 10.51 15.0005 8.68L14.3405 2H9.67048L9.00048 8.68C8.82048 10.51 10.1705 12 12.0005 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18.3108 12C20.3308 12 21.8108 10.36 21.6108 8.35L21.3308 5.6C20.9708 3 19.9708 2 17.3508 2H14.3008L15.0008 9.01C15.1708 10.66 16.6608 12 18.3108 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.64037 12C7.29037 12 8.78037 10.66 8.94037 9.01L9.16037 6.8L9.64037 2H6.59037C3.97037 2 2.97037 3 2.61037 5.6L2.34037 8.35C2.14037 10.36 3.62037 12 5.64037 12Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            <g opacity="0.4">
                                                <path d="M3.00977 11.2197V15.7097C3.00977 20.1997 4.80977 21.9997 9.29977 21.9997H14.6898C19.1798 21.9997 20.9798 20.1997 20.9798 15.7097V11.2197" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z" stroke="{{ request()->is('profile') ? '#ffffff' : 'currentColor' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </g>
                                        </svg>
                                        <span>Profile</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                    
                    <!-- User Info di Sidebar Bawah dengan Dropdown -->
                    <div class="mt-auto pt-4 border-t border-base-200">
                        <div class="flex flex-col gap-y-3">
                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <button id="profile-dropdown-btn" class="w-full flex items-center gap-x-3 p-3 rounded-xl transition-all duration-200 group" style="background-color: rgba(255, 203, 79, 0.1);" onclick="toggleProfileDropdown()">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold shadow-md" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%);">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0 text-left">
                                        <p class="text-sm font-semibold text-base-content truncate group-hover:text-primary transition-colors">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-base-content/60 truncate">
                                            {{ auth()->user()->email }}
                                        </p>
                                    </div>
                                    <svg id="dropdown-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="transition-transform duration-200" style="color: #FFCB4F;">
                                        <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="profile-dropdown" class="hidden absolute bottom-full left-0 right-0 mb-2 bg-base-100 rounded-xl shadow-lg border border-base-200 overflow-hidden">
                                    <div class="p-2">
                                        <a href="{{ route('profile') }}" class="flex items-center gap-x-3 px-3 py-2 text-sm text-base-content/70 hover:bg-base-200 rounded-lg transition-colors">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 14C7.02944 14 3 18.0294 3 23H21C21 18.0294 16.9706 14 12 14Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <span>Profile</span>
                                        </a>
                                        
                                        <form action="{{ route('logout') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-x-3 px-3 py-2 text-sm text-error hover:bg-error/10 rounded-lg transition-colors text-left">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 21H12C17 21 20 18 20 13V12C20 7 17 4 12 4H9C4 4 1 7 1 12V13C1 18 4 21 9 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 15V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12 8V8.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function toggleProfileDropdown() {
                            const dropdown = document.getElementById('profile-dropdown');
                            const arrow = document.getElementById('dropdown-arrow');
                            
                            if (dropdown.classList.contains('hidden')) {
                                dropdown.classList.remove('hidden');
                                arrow.classList.add('rotate-180');
                            } else {
                                dropdown.classList.add('hidden');
                                arrow.classList.remove('rotate-180');
                            }
                        }
                        
                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(event) {
                            const dropdownBtn = document.getElementById('profile-dropdown-btn');
                            const dropdown = document.getElementById('profile-dropdown');
                            const arrow = document.getElementById('dropdown-arrow');
                            
                            if (!dropdownBtn.contains(event.target) && !dropdown.contains(event.target)) {
                                dropdown.classList.add('hidden');
                                arrow.classList.remove('rotate-180');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
        <div class="flex-auto w-screen main-content">
            <div class="w-full navbar bg-base-100 shadow-sm py-4 px-7 sticky top-0 z-10">
                <div class="flex flex-row justify-between items-center">
                    <div class="relative w-full md:w-[500px]">
                        <form action="#">
                            <div class="relative">
                                <input type="text" placeholder="Cari data..."
                                    class="w-full py-3 rounded-full pl-5 pr-12 border border-base-300 bg-base-200 focus:outline-none focus:ring-2 focus:border-transparent transition-all" style="--tw-ring-color: #FFCB4F;" onfocus="this.style.borderColor='#FFCB4F'" onblur="this.style.borderColor='rgba(0, 0, 0, 0.1)'">
                                <button
                                    class="top-2 right-2 absolute z-10 flex flex-row items-center p-2 rounded-full transition-colors" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%);" onmouseover="this.style.background='linear-gradient(135deg, #1E4578 0%, #E5B040 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%)'">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path opacity="0.4" d="M22 22L20 20" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <section class="content px-7 py-6">
                @yield('content')
            </section>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btndropdown = document.getElementById('btn-dropdown');
            const dropdownmenu = document.getElementById('dropdown-menu');

            if (btndropdown && dropdownmenu) {
                btndropdown.addEventListener("click", function () {
                    dropdownmenu.classList.toggle("hidden");
                });

                document.addEventListener("click", function (event) {
                    if (!btndropdown.contains(event.target) && !dropdownmenu.contains(event.target)) {
                        dropdownmenu.classList.add("hidden");
                    }
                });
            }
        });
    </script>

    @stack('scripts')
    @yield('footer')
</body>

</html>
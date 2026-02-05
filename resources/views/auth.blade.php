<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SARANAS - Sistem Manajemen Sarana Prasarana Sekolah">
    <meta name="author" content="SARANAS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | {{ $option->getByKey('app_name') }}</title>

    <!-- DaisyUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        .auth-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        
        .auth-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(50px, 50px) rotate(360deg); }
        }
        
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: morph 8s ease-in-out infinite;
        }
        
        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 70%;
            left: 80%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 40%;
            left: 40%;
            animation-delay: 4s;
        }
        
        @keyframes morph {
            0%, 100% { border-radius: 50%; transform: scale(1); }
            50% { border-radius: 40%; transform: scale(1.1); }
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.2);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transition: all 0.5s ease;
        }
        
        .btn-gradient:hover::before {
            left: 0;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(102, 126, 234, 0.4);
        }
        
        .role-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
        }
        
        .animate-slide-in-delay {
            animation: slideIn 0.6s ease-out 0.2s both;
        }
    </style>
</head>

<body>
    <div class="auth-container flex items-center justify-center p-4">
        <!-- Floating Background Shapes -->
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>

        <!-- Login Card -->
        <div class="login-card rounded-3xl p-8 w-full max-w-md relative z-10 animate-slide-in">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl role-badge mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang!</h1>
                <p class="text-gray-600 text-sm">Masuk ke sistem SARANAS</p>
                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full bg-purple-50 border border-purple-200">
                    <span class="w-2 h-2 rounded-full bg-purple-500 mr-2 animate-pulse"></span>
                    <span class="text-xs text-purple-700 font-medium">Sistem Manajemen Sarana Prasarana</span>
                </div>
            </div>

            <!-- Flash Messages -->
            @if ($status = session('status'))
                <div class="alert alert-{{ $status === 'success' ? 'success' : ($status === 'warning' ? 'warning' : 'info') }} shadow-lg mb-6 animate-slide-in-delay">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        @if($status === 'success')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @elseif($status === 'warning')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @endif
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-6 animate-slide-in-delay">
                @csrf
                
                <!-- Email Field -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Email</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Masukan email Anda" 
                            class="input input-bordered input-field w-full pl-12 pr-4 h-12 rounded-xl"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @error('email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium text-gray-700">Password</span>
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Masukan password Anda" 
                            class="input input-bordered input-field w-full pl-12 pr-12 h-12 rounded-xl"
                            required
                            autocomplete="current-password"
                            id="password-input"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-4 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="cursor-pointer label">
                        <input type="checkbox" class="checkbox checkbox-primary checkbox-sm rounded" />
                        <span class="label-text text-gray-600 text-sm ml-2">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-800 font-medium transition-colors">
                        Lupa password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-gradient btn-primary w-full h-12 rounded-xl text-white font-semibold shadow-lg">
                    <span class="relative z-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk
                    </span>
                </button>
            </form>

            <!-- Quick Login Options (Demo) -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500 mb-4">Demo Login:</p>
                <div class="grid grid-cols-2 gap-3">
                    <button onclick="fillDemo('admin')" class="btn btn-outline btn-sm rounded-xl hover:bg-purple-50 hover:border-purple-300 transition-all">
                        <span class="text-xs font-medium">👨‍💼 Admin</span>
                    </button>
                    <button onclick="fillDemo('guru')" class="btn btn-outline btn-sm rounded-xl hover:bg-purple-50 hover:border-purple-300 transition-all">
                        <span class="text-xs font-medium">👨‍🏫 Guru</span>
                    </button>
                    <button onclick="fillDemo('teknisi')" class="btn btn-outline btn-sm rounded-xl hover:bg-purple-50 hover:border-purple-300 transition-all">
                        <span class="text-xs font-medium">🔧 Teknisi</span>
                    </button>
                    <button onclick="fillDemo('lembaga')" class="btn btn-outline btn-sm rounded-xl hover:bg-purple-50 hover:border-purple-300 transition-all">
                        <span class="text-xs font-medium">🏢 Lembaga</span>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    © 2026 SARANAS - Sistem Manajemen Sarana Prasarana Sekolah
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Version 1.0.0 | Developed with ❤️
                </p>
            </div>
        </div>

        <!-- Features Panel (Desktop) -->
        <div class="hidden lg:block absolute right-12 top-1/2 transform -translate-y-1/2 max-w-sm">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20">
                <h3 class="text-white font-bold text-lg mb-4">✨ Fitur Unggulan</h3>
                <ul class="space-y-3 text-white/90 text-sm">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Manajemen Inventaris Lengkap</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Laporan Kerusakan Online</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Workflow Otomatis</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Monitoring Real-time</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('eye-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        function fillDemo(role) {
            const demoCredentials = {
                'admin': 'admin@test.com',
                'guru': 'guru@test.com',
                'teknisi': 'teknisi@test.com',
                'lembaga': 'lembaga@test.com'
            };

            document.querySelector('input[name="email"]').value = demoCredentials[role];
            document.querySelector('input[name="password"]').value = 'password123';
            document.querySelector('input[name="password"]').focus();
        }

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const button = form.querySelector('button[type="submit"]');
                button.innerHTML = `
                    <span class="relative z-10 flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                `;
                button.disabled = true;
            });
        });
    </script>
</body>
</html>
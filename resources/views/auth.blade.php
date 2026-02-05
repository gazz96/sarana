<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ $option->getByKey('app_name') }}</title>

    <!-- DaisyUI CSS -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-field:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .demo-btn {
            transition: all 0.2s;
        }
        
        .demo-btn:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="login-container flex items-center justify-center p-4">
        <div class="login-card p-8 w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h1>
                <p class="text-gray-600 text-sm">Login ke sistem SARANAS</p>
            </div>

            <!-- Flash Messages -->
            @if ($status = session('status'))
                <div class="alert alert-{{ $status === 'success' ? 'success' : ($status === 'warning' ? 'warning' : 'info') }} mb-6">
                    @if($status === 'success')
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($status === 'warning')
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Masukan email Anda" 
                            class="input input-bordered w-full pl-10 input-field"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Masukan password Anda" 
                            class="input input-bordered w-full pl-10 pr-10 input-field"
                            required
                            autocomplete="current-password"
                            id="password-input"
                        />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="checkbox checkbox-sm" />
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-800">
                        Lupa password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login w-full py-3 text-white font-medium rounded-lg">
                    Masuk
                </button>
            </form>

            <!-- Demo Login -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500 mb-3">Demo Login:</p>
                <div class="grid grid-cols-4 gap-2">
                    <button onclick="fillDemo('admin')" class="demo-btn btn btn-outline btn-sm rounded-lg">
                        <span class="text-xs">Admin</span>
                    </button>
                    <button onclick="fillDemo('guru')" class="demo-btn btn btn-outline btn-sm rounded-lg">
                        <span class="text-xs">Guru</span>
                    </button>
                    <button onclick="fillDemo('teknisi')" class="demo-btn btn btn-outline btn-sm rounded-lg">
                        <span class="text-xs">Teknisi</span>
                    </button>
                    <button onclick="fillDemo('lembaga')" class="demo-btn btn btn-outline btn-sm rounded-lg">
                        <span class="text-xs">Lembaga</span>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>© 2026 SARANAS - Sistem Manajemen Sarana Prasarana</p>
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
    </script>
</body>
</html>
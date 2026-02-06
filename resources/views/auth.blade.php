<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | {{ $option->getByKey('app_name') }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .login-card {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            max-width: 1200px;
            width: 100%;
            display: flex;
            flex-direction: row;
        }
        
        .login-left {
            background: linear-gradient(135deg, #5417D7 0%, #6d28d9 100%);
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 400px;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-20px, -20px); }
        }
        
        .login-left-content {
            position: relative;
            z-index: 10;
        }
        
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }
        
        .brand-logo svg {
            width: 48px;
            height: 48px;
        }
        
        .brand-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
        }
        
        .welcome-text {
            margin-bottom: 2rem;
        }
        
        .welcome-text h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }
        
        .welcome-text p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .features-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }
        
        .features-list li svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        
        .login-footer {
            position: relative;
            z-index: 10;
        }
        
        .login-footer p {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .login-right {
            flex: 1;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }
        
        .form-header {
            margin-bottom: 2rem;
        }
        
        .form-header h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .form-header p {
            color: #64748b;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .form-input-wrapper {
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.95rem;
            color: #1e293b;
            background: white;
            transition: all 0.2s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #5417D7;
            box-shadow: 0 0 0 3px rgba(84, 23, 215, 0.1);
        }
        
        .form-input::placeholder {
            color: #94a3b8;
        }
        
        .form-input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 20px;
            height: 20px;
            pointer-events: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            width: 20px;
            height: 20px;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: #64748b;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .remember-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        
        .remember-checkbox input {
            width: 16px;
            height: 16px;
            accent-color: #5417D7;
        }
        
        .remember-checkbox span {
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .forgot-link {
            color: #5417D7;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }
        
        .forgot-link:hover {
            color: #6d28d9;
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: #5417D7;
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-submit:hover {
            background: #6d28d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(84, 23, 215, 0.3);
        }
        
        .demo-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .demo-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-align: center;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .demo-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        
        .demo-btn {
            padding: 0.625rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .demo-btn:hover {
            background: #f8fafc;
            border-color: #5417D7;
            color: #5417D7;
        }
        
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }
        
        .alert-warning {
            background: #fefce8;
            border: 1px solid #fde047;
            color: #854d0e;
        }
        
        .alert-info {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }
        
        .alert-icon {
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }
        
        .text-error {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
        
        @media (max-width: 968px) {
            .login-card {
                flex-direction: column;
            }
            
            .login-left {
                min-width: auto;
                padding: 2rem;
            }
            
            .login-right {
                padding: 2rem;
            }
        }
        
        @media (max-width: 640px) {
            .login-container {
                padding: 0.5rem;
            }
            
            .login-left, .login-right {
                padding: 1.5rem;
            }
            
            .welcome-text h2 {
                font-size: 1.5rem;
            }
            
            .demo-buttons {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Left Side -->
            <div class="login-left">
                <div class="login-left-content">
                    <div class="brand-logo">
                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10 0C15.5228 0 20 4.47715 20 10V0H30C35.5228 0 40 4.47715 40 10C40 15.5228 35.5228 20 30 20C35.5228 20 40 24.4772 40 30C40 32.7423 38.8961 35.2268 37.1085 37.0334L37.0711 37.0711L37.0379 37.1041C35.2309 38.8943 32.7446 40 30 40C27.2741 40 24.8029 38.9093 22.999 37.1405C22.9756 37.1175 22.9522 37.0943 22.9289 37.0711C22.907 37.0492 22.8852 37.0272 22.8635 37.0051C21.0924 35.2009 20 32.728 20 30C20 35.5228 15.5228 40 10 40C4.47715 40 0 35.5228 0 30V20H10C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0ZM18 10C18 14.4183 14.4183 18 10 18V2C14.4183 2 18 5.58172 18 10ZM38 30C38 25.5817 34.4183 22 30 22C25.5817 22 22 25.5817 22 30H38ZM2 22V30C2 34.4183 5.58172 38 10 38C14.4183 38 18 34.4183 18 30V22H2ZM22 18V2L30 2C34.4183 2 38 5.58172 38 10C38 14.4183 34.4183 18 30 18H22Z"
                                fill="white"></path>
                        </svg>
                        <span class="brand-name">SARANAS</span>
                    </div>
                    
                    <div class="welcome-text">
                        <h2>Selamat Datang<br>Kembali!</h2>
                        <p>Sistem Manajemen Sarana Prasarana terintegrasi untuk efisiensi dan transparansi.</p>
                    </div>
                    
                    <ul class="features-list">
                        <li>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Manajemen Aset Terintegrasi
                        </li>
                        <li>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pelacakan Real-time
                        </li>
                        <li>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Laporan & Analitik
                        </li>
                        <li>
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Multi-user & Role-based
                        </li>
                    </ul>
                </div>
                
                <div class="login-footer">
                    <p>© 2026 SARANAS. Hak Cipta Dilindungi.</p>
                </div>
            </div>
            
            <!-- Right Side -->
            <div class="login-right">
                <!-- Flash Messages -->
                @if ($status = session('status'))
                    <div class="alert alert-{{ $status === 'success' ? 'success' : ($status === 'warning' ? 'warning' : 'info') }}">
                        @if($status === 'success')
                            <svg class="alert-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($status === 'warning')
                            <svg class="alert-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @else
                            <svg class="alert-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                <div class="form-header">
                    <h3>Login ke Akun</h3>
                    <p>Masukkan email dan password untuk melanjutkan</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="form-input-wrapper">
                            <svg class="form-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <input
                                type="email"
                                name="email"
                                placeholder="nama@email.com"
                                class="form-input"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                            />
                        </div>
                        @error('email')
                            <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="form-input-wrapper">
                            <svg class="form-input-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <input
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                class="form-input"
                                required
                                autocomplete="current-password"
                                id="password-input"
                            />
                            <svg
                                class="password-toggle"
                                id="eye-icon"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                onclick="togglePassword()"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        @error('password')
                            <p class="text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember" />
                            <span>Ingat saya</span>
                        </label>
                        <a href="#" class="forgot-link">Lupa password?</a>
                    </div>

                    <button type="submit" class="btn-submit">Masuk</button>

                    <div class="demo-section">
                        <p class="demo-title">Demo Login</p>
                        <div class="demo-buttons">
                            <button type="button" onclick="fillDemo('admin')" class="demo-btn">Admin</button>
                            <button type="button" onclick="fillDemo('guru')" class="demo-btn">Guru</button>
                            <button type="button" onclick="fillDemo('teknisi')" class="demo-btn">Teknisi</button>
                            <button type="button" onclick="fillDemo('lembaga')" class="demo-btn">Lembaga</button>
                        </div>
                    </div>
                </form>
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
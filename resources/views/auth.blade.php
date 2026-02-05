<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Responsive Bootstrap 5 Admin &amp; Dashboard Template">
        <meta name="author" content="Bootlab">
    
        <title>Sign In | {{ $option->getByKey('app_name') }}</title>
    
        <link rel="canonical" href="https://appstack.bootlab.io/auth-sign-in.html">
        <link rel="shortcut icon" href="img/favicon.ico">
    
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&amp;display=swap" rel="stylesheet">
    
        <style>
            .auth-full-page {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Poppins', sans-serif;
            }
            .auth-form {
                background: white;
                border-radius: 20px;
                padding: 3rem;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                width: 100%;
                max-width: 450px;
            }
            .form-control {
                border: 2px solid #e2e8f0;
                padding: 0.75rem 1rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }
            .form-control:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            }
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border: none;
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                font-weight: 500;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
            }
            .invalid-feedback {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
            .alert {
                border-radius: 10px;
                margin-bottom: 1.5rem;
            }
        </style>
    </head>

    <body>

        <div class="auth-full-page d-flex">
            <div class="auth-form p-3">
                @if ($status = session('status'))
                    <div class="alert alert-{{ $status }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="text-center">
                    <h1 class="h2">Selamat datang kembali!</h1>
                    <p class="lead">
                        Sign in to your account to continue
                    </p>
                </div>

                <div class="mb-3">
                    
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="i-email" class="form-label">Email</label>
                            <input type="email" class="form-control rounded-pill" value="{{ old('email') }}" name="email" placeholder="Masukan Email" id="i-email" required>
                            @error('email')
                                <span class="d-block invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="i-password" class="form-label">Password</label>
                            <input type="password" class="form-control rounded-pill" id="i-password" name="password" placeholder="Masukan Password" required>
                            @error('password')
                                <span class="d-block invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary d-block w-100 rounded-pill">Masuk</button>
                    </form>
                </div>

                {{-- <div class="text-center">
                    Don't have an account? <a href="/auth-sign-up">Sign up</a>
                </div> --}}
            </div>
        </div>

    </body>
    
</html>

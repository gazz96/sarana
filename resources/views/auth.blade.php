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
    
        <link href="{{ url('appstack/css/app.css') }}" rel="stylesheet">
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
                    
                    <form action="" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="i-email">Email</label>
                            <input type="email" class="form-control form-control-lg rounded-pill" value="{{ old('email') }}" name="email" placeholder="Masukan Email" id="i-email">
                            @error('email')
                                <span class="d-block invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="i-password">Password</label>
                            <input type="password" class="form-control form-control-lg rounded-pill" id="i-password" name="password" placeholder="Masukan Password">
                            @error('password')
                                <span class="d-block invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="btn btn-lg btn-primary d-block w-100 rounded-pill">Masuk</button>
                    </form>
                </div>

                {{-- <div class="text-center">
                    Don't have an account? <a href="/auth-sign-up">Sign up</a>
                </div> --}}
            </div>
        </div>

    </body>
    
</html>

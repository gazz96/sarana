<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    
    .table tr .tr-actions {
        display: none !important;
    }
    .table tr:hover .tr-actions {
        display: flex !important;
    }

    </style> 
  </head>
  <body>

    <div class="container">
        <div class="row my-5">
            <div class="col-md-4"></div>
            <div class="col-md-4">

                @if($status = session('status'))
                <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                    {{session('message')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <div class="card">
                    <div class="card-body">

                        <form action="" method="POST">
                            @csrf 
                            <div class="mb-3">
                                <label for="i-email">Email</label>
                                <input type="email" class="form-control" value="{{old('email')}}" name="email" placeholder="Masukan Email" id="i-email">
                                @error('email')
                                <span class="d-block invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
        
                            <div class="mb-3">
                                <label for="i-password">Password</label>
                                <input type="password" class="form-control" id="i-password" name="password" placeholder="Masukan Password">
                                @error('password')
                                <span class="d-block invalid-feedback">{{$message}}</span>
                                @enderror
                            </div>
        
                            <button class="btn btn-primary d-block w-100">Masuk</button>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
@extends('layouts')

@section('content')


@if($user->id)
<form action="{{route('users.update', $user)}}" method="POST">
    @method('PUT')
@else 
<form action="{{route('users.store')}}" method="POST">
@endif

    @csrf

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                
                <h3>Form Pegawai</h3>

                @if($status = session('status'))
                <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                    {{session('message')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        
                        <div class="mb-3">
                            <labael for="i-role_id">Jabatan</labael>
                            <select type="text" name="role_id" class="form-control" id="i-role_id">
                                <option value="">Pilih Jabatan</option>
                                @foreach($roles as $role)
                                <option value="{{$role->id}}" {{$user->role_id == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <labael for="i-name">Nama</labael>
                            <input type="text" name="name" class="form-control" id="i-name" value="{{ old('name', $user->name) }}">
                            @error('name')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-email">Email</labael>
                            <input type="email" name="email" class="form-control" id="i-email" value="{{ old('email', $user->email) }}">
                            @error('email')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-password">Password</labael>
                            <input type="password" name="password" class="form-control" id="i-password">
                            @error('password')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{route('users.index')}}" class="btn border me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
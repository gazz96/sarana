@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h3>Pegawai</h3>
            
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input name="s" type="text" class="form-control" placeholder="Masukan keyword..." value="{{ request('s') }}">
                    </div>

                    <div class="col-md-4"></div>

                    <div class="col-md-4 d-flex justify-content-end">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah</a>
                    </div>
                </div>
            </form>

            @if($status = session('status'))
            <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                {{session('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                {{$user->name}}
                                <div class="d-flex align-items-center tr-actions">
                                    <a href="{{route('users.edit', $user)}}" class="text-decoration-none">Edit</a>
                                    <form action="{{route('users.destroy', $user)}}" method="POST" class="ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn p-0 text-danger" onclick="return confirm('HAPUS???')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            <td>{{$user->email}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h3>Barang Invetaris</h3>
            
            <form action="">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input name="s" type="text" class="form-control form-control-lg rounded-pill" placeholder="Masukan keyword..." value="{{ request('s') }}">
                    </div>

                    <div class="col-md-4"></div>

                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <a href="{{ route('goods.create') }}" class="btn btn-lg btn-primary rounded-pill">Tambah</a>
                    </div>
                </div>
            </form>

            @if($status = session('status'))
            <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                {{session('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="table-responsive bg-white">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Lokasi</th>
                            <th>Merk</th>
                            <th>Detail</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goods as $good)
                        <tr>
                            <td>
                                <small style="color: #1A143C;">{{$good->code}}</small>
                                <div class="fw-bold">{{$good->name}}</div>
                                <div class="d-flex align-items-center tr-actions">
                                    <a href="{{route('goods.edit', $good)}}" class="text-decoration-none">Edit</a>
                                    <form action="{{route('goods.destroy', $good)}}" method="POST" class="ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn p-0 text-danger" onclick="return confirm('HAPUS???')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            <td>{{$good->location->name ?? '-'}}</td>
                            <td>{{$good->merk}}</td>
                            <td>{{$good->detail}}</td>
                            <td>{{ucfirst(\App\Models\Good::$STATUS[$good->status])}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-2">
                {{$goods->links()}}
            </div>

        </div>
    </div>
</div>

@endsection
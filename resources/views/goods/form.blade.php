@extends('layouts')

@section('content')


@if($good->id)
<form action="{{route('goods.update', $good)}}" method="POST">
    @method('PUT')
@else 
<form action="{{route('goods.store')}}" method="POST">
@endif
    @csrf

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                
                <h3>Form Barang Inventaris</h3>

                @if($status = session('status'))
                <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                    {{session('message')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="card">
                    <div class="card-body">

                        <div class="mb-3">
                            <labael for="i-name">Nama</labael>
                            <input type="text" name="name" class="form-control" id="i-name" value="{{ old('name', $good->name) }}">
                            @error('name')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-merk">Merk</labael>
                            <input type="text" name="merk" class="form-control" id="i-merk" value="{{ old('merk', $good->merk) }}">
                            @error('merk')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-detail">Detail</labael>
                            <textarea name="detail" class="form-control" id="i-detail">{{ old('detail', $good->detail) }}</textarea>
                            @error('detail')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-location_id">Lokasi</labael>
                            <select name="location_id" class="form-control" id="i-location_id">
                                <option value="">Pilih Lokasi</option>
                                @foreach($locations as $location)
                                <option value="{{$location->id}}" {{ 
                                    $location->id == old('location_id', $good->location_id) ? 'selected' : ''
                                    }}>{{$location->name}}</option>
                                @endforeach
                            </select>
                            @error('location_id')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <labael for="i-status">Status</labael>
                            <select name="status" class="form-control" id="i-status">
                                <option value="">Pilih Status</option>
                                @foreach(\App\Models\Good::$STATUS as $index => $status)
                                <option value="{{$index}}" {{ 
                                    (string)$index === old('status', $good->status) ? 'selected' : ''
                                    }}>{{$status}}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <span class="invalid-feedback d-block">{{$message}}</span>
                            @enderror
                        </div>

                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{route('goods.index')}}" class="btn border me-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
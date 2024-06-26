@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h3>Masalah</h3>
            
            <form action="">
                <div class="row align-items-center mb-3">
                    <div class="col-md-4">
                        <input name="s" type="text" class="form-control form-control-lg rounded-pill" placeholder="Masukan keyword..." value="{{ request('s') }}">
                    </div>

                    <div class="col-md-4"></div>
                    @if(auth()->user()->hasRole(['admin', 'guru', 'pimpinan', 'super user']))
                    <div class="col-md-4 d-flex align-items-center justify-content-end">
                        <a href="{{ route('problems.create') }}" class="btn btn-lg btn-primary rounded-pill">Tambah</a>
                    </div>
                    @endif
                </div>
            </form>

            @if($status = session('status'))
            <div class="alert alert-{{$status}} alert-dismissible fade show" role="alert">
                {{session('message')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- <div class="table-responsive bg-white">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Permintaan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([] as $problem)
                        <tr>
                            <td>
                                {{$problem->code}}
                                <div class="d-flex align-items-center tr-actions">
                                    <a href="{{route('problems.print', $problem)}}" target="_blank" class="text-decoration-none me-2">Cetak</a> 
                                    <a href="{{route('problems.edit', $problem)}}" class="text-decoration-none mx-2">Edit</a>
                                    <form action="{{route('problems.destroy', $problem)}}" method="POST" class="mx-2">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn p-0 text-danger" onclick="return confirm('HAPUS???')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                            <td>{{$problem->user->name ?? '-'}}</td>
                            <td>{{date('d F Y H:i:s', strtotime($problem->date))}}
                            <td>{{number_format($problem->items()->sum('price'))}}</td>
                            <td>{{ucfirst(\App\Models\Problem::$STATUS[$problem->status])}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    {{$problems->links()}}
                </div>
            </div> --}}

            {!! $table !!}
        </div>
    </div>
</div>

@endsection
@extends('layouts')

@section('content')

<div class="container my-5">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>{{\App\Models\Good::count()}}</h4>                    
                    <p class="mb-0">Jumlah Inventaris</p>
                </div>
            </div>
        </div>


        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>{{\App\Models\Location::count()}}</h4>                    
                    <p class="mb-0">Jumlah Lokasi</p>
                </div>
            </div>
        </div>

        
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>{{\App\Models\Good::count()}}</h4>                    
                    <p class="mb-0">Masalah Berjalan</p>
                </div>
            </div>
        </div>


        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <h4>{{\App\Models\Good::count()}}</h4>                    
                    <p class="mb-0">Masalah Selesai</p>
                </div>
            </div>
        </div>



    </div>
</div>

@endsection
@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ number_format(\App\Models\Good::count()) }}</h3>
                            <p class="mb-2">Jumlah Inventaris</p>
                           
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <span data-lucide="list"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3 mb-3">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ number_format(\App\Models\Location::count()) }}</h3>
                            <p class="mb-2">Jumlah Lokasi</p>
                           
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <span data-lucide="pin"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-md-3 mb-3">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ number_format(\App\Models\Problem::count()) }}</h3>
                            <p class="mb-2">Masalah Berjalan</p>
                           
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <span data-lucide="triangle-alert"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-md-3 mb-3">
            <div class="card flex-fill">
                <div class="card-body py-4">
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <h3 class="mb-2">{{ number_format(\App\Models\Problem::count()) }}</h3>
                            <p class="mb-2">Masalah Selesai</p>
                           
                        </div>
                        <div class="d-inline-block ms-3">
                            <div class="stat">
                                <span data-lucide="check"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

@endsection
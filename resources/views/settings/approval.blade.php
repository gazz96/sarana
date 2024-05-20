@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <h3>Persetujuan</h3>


            <form action="{{route('settings.save')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">

                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="i-settings_app_logo">Pegawai</label>
                                    <input name="options[app_logo]" type="file" class="form-control" id="i-settings_app_logo">
                                    <small class="text-info">Pegawai yang dapat melakukan approval pada masalah</small>
                                </div>

                                
                            </div>
                        </div>

                        

                    </div>
                </div>

                <button class="btn btn-primary">Simpan</button>

            </form>
            
        
        </div>
    </div>
</div>

@endsection
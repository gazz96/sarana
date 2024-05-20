@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <h3>Pengaturan Umum</h3>


            <form action="{{route('settings.save')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">

                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="i-settings_app_logo">Logo</label>
                                    <input name="options[app_logo]" type="file" class="form-control" id="i-settings_app_logo">
                                    @if($image_url = $option->getByKey('app_logo'))
                                    <img src="{{url("uploads/" . $image_url)}}" class="img-fluid"/>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="i-settings_app_name">Nama Instansi</label>
                                    <input name="options[app_name]" type="text" class="form-control" value="{{$option->getByKey('app_name')}}" id="i-settings_app_name">
                                </div>

                                <div class="mb-3">
                                    <label for="i-settings_address_instance">Alamat</label>
                                    <textarea name="options[address_instance]" type="text" class="form-control" id="i-settings_address_instance">{{$option->getByKey('address_instance')}}</textarea>
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
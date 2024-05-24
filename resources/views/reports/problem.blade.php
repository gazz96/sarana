@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <h3 class="mb-3">Laporan Masalah</h3>

            <form action="" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label for="">Tahun</label>
                        <select name="year" id="i-year" class="form-control" onchange="this.form.submit()">
                            <option value="">Pilih Tahun</option>
                            @foreach ($years as $year)
                                <option value="{{$year->tahun}}" {{request('year') == $year->tahun ? 'selected' : ''}}>{{$year->tahun}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            
            
            {!!$table!!}
        
        </div>
    </div>
</div>

@endsection
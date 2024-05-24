@extends('layouts')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            
            <h3 class="mb-3">Laporan Keuangan</h3>

            <form action="" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label for="i-date">Tanggal Pengeluaran</label>
                        <input type="text" name="date" id="i-date" class="form-control" value="{{request('date')}}" placeholder="Tanggal">
                    </div>

               
                </div>
            </form>

            <div class="row">
                <div class="col-md-3 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <h3 class="mb-2">Rp {{ number_format($total_pengeluaran)}}</h3>
                                    <p class="mb-2">Total Pengeluaran</p>
                                    {{-- <div class="mb-0">
                                        <span class="badge badge-subtle-success me-2"> +5.35% </span>
                                        <span class="text-muted">Since last week</span>
                                    </div> --}}
                                </div>
                                <div class="d-inline-block ms-3">
                                    <div class="stat">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="dollar-sign" class="lucide lucide-dollar-sign align-middle text-success"><line x1="12" x2="12" y1="2" y2="22"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <h3 class="mb-2">{{ number_format($total_good_fixed)}}</h3>
                                    <p class="mb-2">Barang diperbaiki</p>
                                    {{-- <div class="mb-0">
                                        <span class="badge badge-subtle-success me-2"> +5.35% </span>
                                        <span class="text-muted">Since last week</span>
                                    </div> --}}
                                </div>
                                <div class="d-inline-block ms-3">
                                    <div class="stat">
                                        <div class="i" data-lucide="files"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {!!$table!!}
        
        </div>
    </div>
</div>

@endsection


@section('footer')

<script>

    document.addEventListener("DOMContentLoaded", function() {
        // Daterangepicker
        $("input[name=\"date\"]").daterangepicker({
            opens: "left",
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY/MM/DD'
            }
        });

        $('input[name="date"]').on('apply.daterangepicker', function(e, picker){
            $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
            $('form').trigger('submit')
        })
    })



</script>

@endsection
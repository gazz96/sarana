@extends('layouts')

@section('content')
    @if ($problem->id)
        <form action="{{ route('problems.update', $problem) }}" method="POST">
            @method('PUT')
    @else
        <form action="{{ route('problems.store') }}" method="POST">
    @endif

    @csrf

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-12">

                <h3>Form Masalah</h3>

                @if ($status = session('status'))
                    <div class="alert alert-{{ $status }} alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <div class="card">
                    <div class="card-body">

                        <div class="row mb-3">
                            <div class="mb-3 col-md-3">
                                <label for="i-kode">Kode</label>
                                <input name="code" type="text" class="form-control" id="i-kode" placeholder="Kosongkan untuk nomor otomatis" value="{{old('code', $problem->code)}}">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="i-date">Tanggal</label>
                                <input name="date" type="date" class="form-control" id="i-date" placeholder="" value="{{old('date', $problem->date ?? date('Y-m-d'))}}">
                            </div>
            
                            <div class="mb-3 col-md-3">
                                <label for="i-user_id">Permintaan</label>
                                <input type="text" class="form-control" id="i-user_id" disabled value="{{auth()->user()->email}}">
                            </div>
            
                            <div class="mb-3 col-md-3">
                                <label for="i-status">Status</label>
                                <input type="text" class="form-control" id="i-status" disabled value="{{\App\Models\Problem::$STATUS[$problem->status ?? 0]}}">
                            </div>
                        </div>

                    </div>
                </div>
                

                
            </div>
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                    
                        <table class="table table-hover table-stripped table-responsive" id="table-problem_items">
                            <thead>
                                <tr>
                                    <th class="text-center">BARANG</th>
                                    <th class="text-center">MASALAH</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($problem->id)
                                    @foreach($problem->items()->get() as $index => $item)
                                        <tr>
                                            <td>
                                                <input type='hidden' name="items[{{$index}}][id]" value=""/>
                                                <input type='hidden' name="items[{{$index}}][good_id]" value="{{$item->good_id}}"/>
                                                <input type='hidden' name="items[{{$index}}][issue]" value="{{$item->issue}}"/>
                                                {{ $item->good->name ?? '-'}}
                                            </td>
                                            <td>{{$item->issue}}</td>
                                            <td class='text-center'>
                                                <button type='button' class='btn btn-sm btn-danger btn-delete-item'>
                                                    <i data-lucide="x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center">
                    <a href="{{ route('problems.index') }}" class="btn border me-2">Kembali</a>
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modal-item">
                        <i class="bi bi-plus me-1"></i>
                        Tambah Barang
                    </button>
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bi bi-save me-1"></i>
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

    </form>

    <!-- Modal -->
    <div class="modal fade" id="modal-item" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-problem_item">
                        <div class="mb-3">
                            <label for="i-good_id">Barang</label>
                            <select name="good_id" id="i-good_id" class="form-control">
                                <option value="">Pilih Barang</option>
                                @foreach($goods as $good)
                                <option value="{{$good->id}}">{{$good->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="i-issue">Masalah</label>
                            <textarea name="issue" id="i-issue" cols="30" rows="5" class="form-control"></textarea>
                        </div>

                        <button class="btn btn-primary" id="btn-add-item">Tambah</button>
                        <button class="btn btn-primary" id="btn-add-more-item">Tambah & Buat Lagi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('footer')
<script>

    let items = [];

    @if($problem->id)
        @foreach($problem->items()->get() as $item)
        items.push({
            good_id: "{{$item->good_id}}",
            good_name: "{{$item->good->name ?? '-'}}",
            issue: "{{$item->issue}}",
        })
        @endforeach
    @endif


    const addRow = (index, item) => {
        return `
            <tr>
                <td>
                    <input type='hidden' name="items[${index}][id]" value=""/>
                    <input type='hidden' name="items[${index}][good_id]" value="${item.good_id}"/>
                    <input type='hidden' name="items[${index}][issue]" value="${item.issue}"/>
                    ${item.good_name}
                </td>
                <td>${item.issue}</td>
                <td class='text-center'>
                    <button type='button' class='btn btn-sm btn-danger btn-delete-item'>
                        HAPUS
                    </button>
                </td>
            </tr>
        `
    }

    const addProblem = (problem) => {

        if(!problem.good_id) 
        {
            return false;
        }

        let find = items.filter(item => item.good_id === problem.good_id);
        if(!find.length) {
            items.push(problem);
            return problem;
        }
        return false;
    }

    let form_keys = {
        good_id: 'Barang',
        issue: 'Masalah',
        note: 'Catatan',
        price: 'Harga'
    }


    let tableProblemItems = $('#table-problem_items');
    $(document).on('click', '#btn-add-item', function(e){
        e.preventDefault();
        let form = $('#form-problem_item')

        let problem = {
            good_id: form.find('#i-good_id').val(),
            good_name: form.find('#i-good_id').find('option:selected').html(),
            issue: form.find('#i-issue').val()
        }

        if(addProblem(problem)) {
            tableProblemItems.find('tbody').empty();
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
            })
            form[0].reset();
            $('#modal-item').modal('hide');
            return;
        }

        Toastify({
            text: "Periksa Kembali",
            className: "bg-warning"
        }).showToast();

        
        
    });

    $(document).on('click', '#btn-add-more-item', function(e){
        e.preventDefault();
        let form = $('#form-problem_item')

        let problem = {
            good_id: form.find('#i-good_id').val(),
            good_name: form.find('#i-good_id').find('option:selected').html(),
            issue: form.find('#i-issue').val(),
        }

        if(addProblem(problem)) {
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
            })
            form[0].reset();
            return;
        }

        Toastify({
            text: "Periksa Kembali",
            className: "bg-warning"
        }).showToast();

        
        
    });

    $(document).on('click', '.btn-delete-item', function(e){
        e.preventDefault();
        const index = items.indexOf($(this).parent().parent().index());
        items.splice(index, 1);
        $(this).parent().parent().remove();
    })

</script>
@endsection

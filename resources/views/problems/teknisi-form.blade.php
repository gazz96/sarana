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
                                <input name="code" type="text" class="form-control" id="i-kode" placeholder="Kosongkan untuk nomor otomatis" value="{{old('code', $problem->code)}}" readonly>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label for="i-date">Tanggal</label>
                                <input name="date" type="date" class="form-control" id="i-date" placeholder="" value="{{old('date', $problem->date ?? date('Y-m-d'))}}" readonly>
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
                                    <th class="text-center">NOTE</th>
                                    <th class="text-center">BIAYA PERBAIKAN</th>
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
                                                <input type='hidden' name="items[{{$index}}][problem]" value="{{$item->probelm}}"/>
                                                <input type='hidden' name="items[{{$index}}[note]" value="{{$item->note}}"/>
                                                <input type='hidden' name="items[{{$index}}][price]" value="{{$item->price}}"/>
                                                {{ $item->good->name ?? '-'}}
                                            </td>
                                            <td>{{$item->problem}}</td>
                                            <td>{{$item->note}}</td>
                                            <td class="text-end">{{ number_format($item->price) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-center">GRANDTOTAL</th>
                                    <th id="total" class="text-end">{{ number_format($problem->items()->sum('price')) }}</th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
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
                            <label for="i-problem">Masalah</label>
                            <textarea name="problem" id="i-problem" cols="30" rows="5" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="i-note">Note</label>
                            <textarea name="note" id="i-note" cols="30" rows="5" class="form-control"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="i-price">Biaya Perbaikan</label>
                            <input name="price" type="number" class="form-control" id="i-price">
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
            problem: "{{$item->problem}}",
            note: "{{$item->note}}",
            price: "{{$item->price}}"
        })
        @endforeach
    @endif


    const addRow = (index, item) => {
        return `
            <tr>
                <td>
                    <input type='hidden' name="items[${index}][id]" value=""/>
                    <input type='hidden' name="items[${index}][good_id]" value="${item.good_id}"/>
                    <input type='hidden' name="items[${index}][problem]" value="${item.problem}"/>
                    <input type='hidden' name="items[${index}][note]" value="${item.note}"/>
                    <input type='hidden' name="items[${index}][price]" value="${item.price}"/>
                    ${item.good_name}
                </td>
                <td>${item.problem}</td>
                <td>${item.price}</td>
                <td>${item.note}</td>
                <td class='text-center'>
                    <button type='button' class='btn btn-sm btn-danger btn-delete-item'>
                        <i class='bi bi-x'></i>
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
        problem: 'Masalah',
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
            problem: form.find('#i-problem').val(),
            note: form.find('#i-note').val(),
            price: form.find('#i-price').val()
        }

        if(addProblem(problem)) {
            tableProblemItems.find('tbody').empty();
            let total = 0;
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
                total += problem.price;
            })
            $('#total').html(total)

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
            problem: form.find('#i-problem').val(),
            note: form.find('#i-note').val(),
            price: form.find('#i-price').val()
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
        $(this).parent().parent().remove();
    })

</script>
@endsection

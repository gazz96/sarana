@extends('layouts')

@section('content')
    @if ($problem->id)
        <form action="{{ route('problems.update', $problem) }}" method="POST">
            @method('PUT')
    @else
        <form action="{{ route('problems.store') }}" method="POST">
    @endif

    @csrf

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $problem->id ? 'Edit Laporan' : 'Buat Laporan Baru' }}
            </h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Laporkan kerusakan sarana dan prasarana</p>
        </div>

        @if ($status = session('status'))
            <div class="mb-6 p-4 rounded-lg {{ $status == 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Informasi Dasar Card -->
        <div class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body">
                <h2 class="card-title mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Dasar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Tanggal -->
                    <div class="form-control">
                        <label for="date" class="label">
                            <span class="label-text">Tanggal Laporan</span>
                        </label>
                        <input name="date" type="date" 
                               class="input input-bordered w-full"
                               value="{{old('date', $problem->date ?? date('Y-m-d'))}}">
                    </div>

                    <!-- Pelapor -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Pelapor</span>
                        </label>
                        <input type="text" 
                               class="input input-bordered w-full"
                               value="{{ auth()->user()->name }}" readonly>
                        <label class="label">
                            <span class="label-text-alt">{{ auth()->user()->email }}</span>
                        </label>
                    </div>

                </div>
            </div>
        </div>
        
        <!-- Barang Rusak Card -->
        <div class="card bg-base-100 shadow-sm mb-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="card-title">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Barang Rusak
                    </h2>
                    <button type="button" onclick="openModal()" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Barang
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra" id="table-problem_items">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th>Masalah</th>
                                <th>Foto</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($problem->id && $problem->items()->count() > 0)
                                @foreach($problem->items()->get() as $index => $item)
                                    <tr data-index="{{$index}}">
                                        <td>
                                            <input type='hidden' name="items[{{$index}}][id]" value="{{$item->id}}"/>
                                            <input type='hidden' name="items[{{$index}}][good_id]" value="{{$item->good_id}}"/>
                                            <input type='hidden' name="items[{{$index}}][issue]" value="{{$item->issue}}"/>
                                            <div class="font-medium">{{ $item->good->name ?? '-' }}</div>
                                        </td>
                                        <td>{{$item->issue}}</td>
                                        <td>
                                            @if($item->photos && count($item->photos) > 0)
                                                <div class="flex gap-2 flex-wrap">
                                                    @foreach($item->photos as $photo)
                                                        <img src="{{ asset($photo) }}" alt="Photo" class="w-12 h-12 object-cover rounded-lg cursor-pointer hover:opacity-80" onclick="window.open('{{ asset($photo) }}', '_blank')">
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-opacity-50 text-xs">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type='button' class='btn-edit-item btn btn-ghost btn-sm text-primary' data-index="{{$index}}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <button type='button' class='btn-delete-item btn btn-ghost btn-sm text-error'>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(!$problem->id || $problem->items()->count() === 0)
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-base-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="text-lg font-bold">Belum ada barang yang dilaporkan</h3>
                        <p class="text-sm opacity-70 mt-1">Klik tombol "Tambah Barang" untuk memulai</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('problems.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <div class="flex gap-3">
                <button type="submit" class="btn btn-success">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Laporan
                </button>
            </div>
        </div>
    </div>

    </form>

    <!-- daisyUI Modal -->
    <dialog id="modal-item" class="modal">
        <div class="modal-box">
            <h3 id="modal-title" class="font-bold text-lg mb-4">Tambah Barang Rusak</h3>
            
            <form action="" id="form-problem_item">
                <!-- Pilih Barang -->
                <div class="form-control mb-4">
                    <label for="i-good_id" class="label">
                        <span class="label-text">Pilih Barang</span>
                    </label>
                    <select name="good_id" id="i-good_id" class="select select-bordered w-full" required>
                        <option value="">-- Cari Barang --</option>
                    </select>
                    <label class="label">
                        <span class="label-text-alt">Ketik untuk mencari barang</span>
                    </label>
                </div>

                <!-- Deskripsi Masalah -->
                <div class="form-control mb-4">
                    <label for="i-issue" class="label">
                        <span class="label-text">Deskripsi Masalah</span>
                    </label>
                    <textarea name="issue" id="i-issue" class="textarea textarea-bordered w-full" rows="3" required placeholder="Jelaskan masalah yang terjadi..."></textarea>
                </div>

                <!-- Upload Foto -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Foto Kerusakan <span class="text-opacity-50">(Opsional)</span></span>
                    </label>
                    <input type="file" id="temp-photo" name="photo" accept="image/*" class="file-input file-input-bordered w-full">
                    <label class="label">
                        <span class="label-text-alt">Max 5MB (JPG, PNG)</span>
                    </label>
                </div>

                <!-- Modal footer -->
                <div class="modal-action">
                    <button type="button" onclick="closeModal()" class="btn btn-ghost">Batal</button>
                    <button type="button" id="btn-add-more-item" class="btn btn-outline">Tambah Lagi</button>
                    <button type="button" id="btn-add-item" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button onclick="closeModal()">close</button>
        </form>
    </dialog>
@endsection

@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('footer')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Helper function untuk asset URL
    function asset(path) {
        return '/' + path;
    }
    // Modal functions for daisyUI
    function openModal(index = null) {
        const modal = document.getElementById('modal-item');
        const title = document.getElementById('modal-title');
        const form = $('#form-problem_item');
        
        editingIndex = index;
        
        // Reset form first
        form.find('#i-issue').val('');
        $('#i-good_id').val(null).trigger('change');
        pond.removeFiles();
        tempPhotosArray = [];
        
        if (index !== null && items[index]) {
            // Edit mode
            const item = items[index];
            title.textContent = 'Edit Barang Rusak';
            
            // Load existing data
            form.find('#i-issue').val(item.issue);
            
            // Create option for existing good and select it
            const newOption = new Option(item.good_name, item.good_id, true, true);
            $('#i-good_id').append(newOption).trigger('change');
            
            // Load existing photos
            if (item.photos && item.photos.length > 0) {
                tempPhotosArray = [...item.photos];
                
                // Create a simple preview area for existing photos
                const previewHtml = item.photos.map(photoPath => `
                    <div class="existing-photo-item relative mb-2">
                        <img src="${asset(photoPath)}" alt="Photo" class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-90" onclick="window.open('${asset(photoPath)}', '_blank')">
                        <button type="button" class="btn-remove-existing-photo absolute top-0 right-0 bg-error hover:bg-red-700 text-white rounded-full p-1 shadow" data-photo-path="${photoPath}" style="transform: translate(50%, -50%);">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `).join('');
                
                // Add preview area after file input
                const $previewArea = $('<div class="existing-photos-gallery mt-3 p-3 bg-base-200 rounded-lg">' +
                    '<p class="text-sm font-medium mb-2">Foto yang sudah diupload:</p>' +
                    '<div class="existing-photos-grid flex gap-2 flex-wrap">' + previewHtml + '</div>' +
                '</div>');
                
                $('#temp-photo').parent().append($previewArea);
            }
        } else {
            // Add mode
            title.textContent = 'Tambah Barang Rusak';
        }
        
        modal.showModal();
    }
    
    function closeModal() {
        const modal = document.getElementById('modal-item');
        
        // Remove existing photos preview area
        $('.existing-photos-gallery').remove();
        
        modal.close();
    }
    
    let items = [];
    let tempPhotosArray = [];
    let editingIndex = null; // Track which item is being edited

    // Initialize Select2 for AJAX search
    $(document).ready(function() {
        $('#i-good_id').select2({
            ajax: {
                url: '{{ route('goods.search') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            placeholder: '-- Cari Barang --',
            minimumInputLength: 2,
            theme: 'default',
            width: '100%'
        });
    });

    // Initialize FilePond
    const inputElement = document.querySelector('#temp-photo');
    const pond = FilePond.create(inputElement, {
        labelIdle: 'Drag & Drop atau Klik untuk Upload Foto',
        labelFileProcessing: 'Mengupload',
        labelFileLoading: 'Loading',
        labelFileProcessingComplete: 'Upload Selesai',
        labelFileProcessingAborted: 'Upload Dibatalkan',
        labelTapToCancel: 'Klik untuk batalkan',
        labelTapToRetry: 'Klik untuk retry',
        allowMultiple: true,
        maxFileSize: '5MB',
        acceptedFileTypes: ['image/*'],
        instantUpload: false,
        allowReorder: true
    });

    // Handle file uploads manually
    pond.on('addfile', function(error, file) {
        if (!error) {
            uploadPhoto(file);
        }
    });

    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('photo', file.file);
        formData.append('problem_item_id', 'temp');
        
        Toastify({
            text: "Sedang mengupload foto...",
            className: "bg-info",
            duration: 2000
        }).showToast();
        
        fetch('{{ route('photos.upload') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                tempPhotosArray.push(data.photo_path);
                
                Toastify({
                    text: "Foto berhasil diupload",
                    className: "bg-success"
                }).showToast();
            } else {
                Toastify({
                    text: "Gagal: " + data.message,
                    className: "bg-danger"
                }).showToast();
                
                // Remove failed file from pond
                pond.removeFile(file.id);
            }
        })
        .catch(error => {
            Toastify({
                text: "Terjadi kesalahan",
                className: "bg-danger"
            }).showToast();
            
            pond.removeFile(file.id);
        });
    }

    // Remove photo from array when removed from FilePond
    pond.on('removefile', function(error, file) {
        if (!error) {
            const photoPath = file.getMetadata()?.path;
            if (photoPath) {
                // Remove from tempPhotosArray
                tempPhotosArray = tempPhotosArray.filter(path => path !== photoPath);
                
                // If editing existing item, also remove from the item's photos
                if (editingIndex !== null && items[editingIndex]) {
                    items[editingIndex].photos = items[editingIndex].photos.filter(path => path !== photoPath);
                }
            }
        }
    });

    @if($problem->id)
        @foreach($problem->items()->get() as $item)
        items.push({
            id: "{{$item->id}}",
            good_id: "{{$item->good_id}}",
            good_name: "{{$item->good->name ?? '-'}}",
            issue: "{{$item->issue}}",
            photos: @json(isset($item->photos) ? $item->photos : [])
        })
        @endforeach
    @endif

    const addRow = (index, item) => {
        const photosHtml = item.photos && item.photos.length > 0 
            ? item.photos.map(photo => `
                <img src="${asset(photo)}" alt="Photo" class="w-12 h-12 object-cover rounded-lg cursor-pointer hover:opacity-80" onclick="window.open('${asset(photo)}', '_blank')">`
            ).join('')
            : '<span class="text-gray-400 text-xs">Tidak ada foto</span>';

        return `
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" data-index="${index}">
                <td class="px-6 py-4">
                    <input type='hidden' name="items[${index}][id]" value="${item.id || ''}"/>
                    <input type='hidden' name="items[${index}][good_id]" value="${item.good_id}"/>
                    <input type='hidden' name="items[${index}][issue]" value="${item.issue}"/>
                    <input type='hidden' name="items[${index}][photos][]" value="${item.photos ? item.photos.join(',') : ''}"/>
                    <div class="font-medium text-gray-900 dark:text-white">${item.good_name}</div>
                </td>
                <td class="px-6 py-4">${item.issue}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-2 flex-wrap">
                        ${photosHtml}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type='button' class='btn-edit-item font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2' data-index="${index}">
                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button type='button' class='btn-delete-item font-medium text-red-600 dark:text-red-500 hover:underline'>
                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `;
    }

    const addProblem = (problem) => {
        if(!problem.good_id) {
            return false;
        }

        // Check duplicate berdasarkan good_id DAN issue, bukan good_id saja
        let find = items.filter(item => 
            item.good_id === problem.good_id && 
            item.issue === problem.issue &&
            item.id !== problem.id // Exclude current item when editing
        );
        
        if(!find.length) {
            items.push(problem);
            return problem;
        }
        return false;
    }

    let tableProblemItems = $('#table-problem_items');
    
    $(document).on('click', '#btn-add-item', function(e){
        e.preventDefault();
        let form = $('#form-problem_item');
        let selectedOption = $('#i-good_id').select2('data')[0];

        if(!selectedOption || !selectedOption.id) {
            Toastify({
                text: "Pilih barang terlebih dahulu",
                className: "bg-warning"
            }).showToast();
            return;
        }

        let problem = {
            id: editingIndex !== null && items[editingIndex] ? items[editingIndex].id : null,
            good_id: selectedOption.id,
            good_name: selectedOption.text,
            issue: form.find('#i-issue').val(),
            photos: [...tempPhotosArray] // Copy photos array
        }

        if (editingIndex !== null) {
            // Update existing item
            items[editingIndex] = problem;
            
            tableProblemItems.find('tbody').empty();
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
            });
            
            Toastify({
                text: "Barang berhasil diupdate",
                className: "bg-success"
            }).showToast();
        } else {
            // Add new item
            if(addProblem(problem)) {
                tableProblemItems.find('tbody').empty();
                items.map((problem, index) => {
                    tableProblemItems.find('tbody').append(addRow(index, problem));
                });
                
                // Hide empty message
                tableProblemItems.find('.text-center.py-12').remove();
                
                Toastify({
                    text: "Barang berhasil ditambahkan",
                    className: "bg-success"
                }).showToast();
            } else {
                Toastify({
                    text: "Barang dengan masalah yang sama sudah ada dalam daftar",
                    className: "bg-warning"
                }).showToast();
                return;
            }
        }
        
        // Reset form
        form.find('#i-issue').val('');
        $('#i-good_id').val(null).trigger('change');
        pond.removeFiles();
        tempPhotosArray = [];
        editingIndex = null;
        closeModal();
    });

    $(document).on('click', '#btn-add-more-item', function(e){
        e.preventDefault();
        let form = $('#form-problem_item');
        let selectedOption = $('#i-good_id').select2('data')[0];

        if(!selectedOption || !selectedOption.id) {
            Toastify({
                text: "Pilih barang terlebih dahulu",
                className: "bg-warning"
            }).showToast();
            return;
        }

        let problem = {
            good_id: selectedOption.id,
            good_name: selectedOption.text,
            issue: form.find('#i-issue').val(),
            photos: [...tempPhotosArray] // Copy photos array
        }

        if(addProblem(problem)) {
            tableProblemItems.find('tbody').empty();
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
            });
            
            // Hide empty message
            tableProblemItems.find('.text-center.py-12').remove();
            
            // Reset form for next item
            form.find('#i-issue').val('');
            $('#i-good_id').val(null).trigger('change');
            pond.removeFiles();
            tempPhotosArray = [];
            
            Toastify({
                text: "Barang ditambahkan. Tambah lagi?",
                className: "bg-success"
            }).showToast();
            return;
        }

        Toastify({
            text: "Barang dengan masalah yang sama sudah ada dalam daftar",
            className: "bg-warning"
        }).showToast();
    });

    $(document).on('click', '.btn-edit-item', function(e){
        e.preventDefault();
        const index = $(this).data('index');
        openModal(index);
    });

    $(document).on('click', '.btn-delete-item', function(e){
        e.preventDefault();
        const row = $(this).closest('tr');
        const index = row.data('index');
        
        if(index !== undefined) {
            items.splice(index, 1);
        }
        
        row.remove();
        
        if(items.length === 0) {
            tableProblemItems.find('tbody').html(`
                <tr>
                    <td colspan="4" class="text-center">
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-base-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="text-lg font-bold">Belum ada barang yang dilaporkan</h3>
                            <p class="text-sm opacity-70 mt-1">Klik tombol "Tambah Barang" untuk memulai</p>
                        </div>
                    </td>
                </tr>
            `);
        }
        
        Toastify({
            text: "Barang dihapus",
            className: "bg-info"
        }).showToast();
    });

    // Handle removing existing photos in edit mode
    $(document).on('click', '.btn-remove-existing-photo', function(e){
        e.preventDefault();
        const photoPath = $(this).data('photo-path');
        const photoItem = $(this).closest('.existing-photo-item');
        
        // Remove from tempPhotosArray
        tempPhotosArray = tempPhotosArray.filter(path => path !== photoPath);
        
        // If editing existing item, also remove from the item's photos
        if (editingIndex !== null && items[editingIndex]) {
            items[editingIndex].photos = items[editingIndex].photos.filter(path => path !== photoPath);
            
            // Update table immediately to reflect the change
            tableProblemItems.find('tbody').empty();
            items.map((problem, index) => {
                tableProblemItems.find('tbody').append(addRow(index, problem));
            });
        }
        
        // Remove photo from preview
        photoItem.fadeOut(300, function() {
            $(this).remove();
            
            // If no more photos, remove the entire preview area
            if ($('.existing-photos-grid').find('.existing-photo-item').length === 0) {
                $('.existing-photos-gallery').fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
        
        Toastify({
            text: "Foto dihapus",
            className: "bg-info"
        }).showToast();
    });
</script>
@endsection
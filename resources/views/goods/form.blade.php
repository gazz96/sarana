@extends('layouts')

@section('content')

@if($good->id)
<form action="{{route('goods.update', $good)}}" method="POST">
    @method('PUT')
@else 
<form action="{{route('goods.store')}}" method="POST">
@endif
    @csrf

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="bg-base-100 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background-color: rgba(41, 94, 164, 0.1);">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #295EA4;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v18l-8-4m8 4v6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-base-content">{{ $good->id ? 'Edit Barang' : 'Tambah Barang' }}</h1>
                    <p class="text-sm text-base-content/70">{{ $good->id ? 'Perbarui data barang inventaris' : 'Tambahkan barang baru ke inventaris' }}</p>
                </div>
            </div>
        </div>

        <!-- Alert Message -->
        @if($status = session('status'))
            <div class="alert {{ $status == 'success' ? 'alert-success bg-success/10 border-success/20' : 'alert-error bg-error/10 border-error/20' }} rounded-2xl">
                @if($status == 'success')
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @else
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-base-100 rounded-2xl p-6 shadow-sm">
            <div class="space-y-6">
                <!-- Nama Barang -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Barang <span class="text-error">*</span></span>
                    </label>
                    <input type="text" 
                           name="name" 
                           class="input input-bordered w-full rounded-xl {{ $errors->has('name') ? 'input-error' : '' }}" 
                           placeholder="Masukkan nama barang"
                           value="{{ old('name', $good->name) }}">
                    @error('name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Merk -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Merk</span>
                    </label>
                    <input type="text" 
                           name="merk" 
                           class="input input-bordered w-full rounded-xl {{ $errors->has('merk') ? 'input-error' : '' }}" 
                           placeholder="Masukkan merk barang (opsional)"
                           value="{{ old('merk', $good->merk) }}">
                    @error('merk')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <!-- Detail -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Detail</span>
                    </label>
                    <textarea name="detail" 
                              class="textarea textarea-bordered w-full rounded-xl {{ $errors->has('detail') ? 'textarea-error' : '' }}" 
                              placeholder="Deskripsi detail barang (opsional)"
                              rows="4">{{ old('detail', $good->detail) }}</textarea>
                    @error('detail')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Lokasi <span class="text-error">*</span></span>
                        </label>
                        <select name="location_id" 
                                class="select select-bordered w-full rounded-xl {{ $errors->has('location_id') ? 'select-error' : '' }}">
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                            <option value="{{$location->id}}" {{ 
                                $location->id == old('location_id', $good->location_id) ? 'selected' : ''
                                }}>{{$location->name}}</option>
                            @endforeach
                        </select>
                        @error('location_id')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Status <span class="text-error">*</span></span>
                        </label>
                        <select name="status" 
                                class="select select-bordered w-full rounded-xl {{ $errors->has('status') ? 'select-error' : '' }}">
                            <option value="">Pilih Status</option>
                            @foreach(\App\Models\Good::$STATUS as $index => $status)
                            <option value="{{$index}}" {{ 
                                (string)$index === old('status', $good->status) ? 'selected' : ''
                                }}>{{$status}}</option>
                            @endforeach
                        </select>
                        @error('status')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{route('goods.index')}}" class="btn btn-outline border-base-300 rounded-xl">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <button type="submit" class="btn text-white border-none rounded-xl" style="background-color: #295EA4;" onmouseover="this.style.backgroundColor='#1E4578'" onmouseout="this.style.backgroundColor='#295EA4'">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $good->id ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </div>
</form>

@endsection
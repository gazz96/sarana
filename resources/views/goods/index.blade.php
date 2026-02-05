@extends('layouts')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Inventaris Barang</h1>
                <p class="text-sm opacity-70 mt-1">Kelola data barang dan inventaris sarana prasarana</p>
            </div>
            <div>
                <a href="{{ route('goods.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Barang
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Message -->
    @if($status = session('status'))
        <div class="alert {{ $status == 'success' ? 'alert-success' : 'alert-error' }} mb-6">
            @if($status == 'success')
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @else
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <!-- Search Card -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <form action="" method="GET">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <div class="form-control">
                            <div class="input-group">
                                <input name="s" type="text" 
                                       class="input input-bordered w-full" 
                                       placeholder="Cari barang berdasarkan kode atau nama..." 
                                       value="{{ request('s') }}">
                                @if(request('s'))
                                <button type="button" onclick="window.location.href='{{ route('goods.index') }}'" class="btn btn-square btn-ghost">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(request('s'))
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari
                        </button>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Lokasi</th>
                            <th>Merk</th>
                            <th>Detail</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goods as $good)
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <div class="badge badge-sm badge-neutral mb-1">{{ $good->code }}</div>
                                    <div class="font-semibold">{{ $good->name }}</div>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <a href="{{route('goods.edit', $good)}}" class="text-xs btn btn-ghost btn-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{route('goods.destroy', $good)}}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs btn btn-ghost btn-sm text-error" onclick="return confirm('HAPUS???')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="badge badge-outline">{{ $good->location->name ?? '-' }}</div>
                            </td>
                            <td>{{ $good->merk }}</td>
                            <td class="max-w-xs truncate">{{ $good->detail }}</td>
                            <td>
                                @if($good->status === 'AKTIF')
                                    <div class="badge badge-success">AKTIF</div>
                                @else
                                    <div class="badge badge-error">TIDAK AKTIF</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($goods->hasPages())
            <div class="border-t border-base-300 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm opacity-70">
                        Menampilkan <span class="font-medium">{{ $goods->firstItem() }}</span> sampai 
                        <span class="font-medium">{{ $goods->lastItem() }}</span> dari 
                        <span class="font-medium">{{ $goods->total() }}</span> hasil
                    </div>
                    <div class="join">
                        @if($goods->onFirstPage())
                            <button class="join-item btn btn-sm" disabled>«</button>
                        @else
                            <a href="{{ $goods->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
                        @endif
                        
                        @foreach($goods->getUrlRange(1, $goods->lastPage()) as $page => $url)
                            @if($page == $goods->currentPage())
                                <button class="join-item btn btn-sm btn-active">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}" class="join-item btn btn-sm">{{ $page }}</a>
                            @endif
                        @endforeach
                        
                        @if($goods->hasMorePages())
                            <a href="{{ $goods->nextPageUrl() }}" class="join-item btn btn-sm">»</a>
                        @else
                            <button class="join-item btn btn-sm" disabled>»</button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Empty State -->
    @if(!$goods->count() && !request('s'))
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v18l-8-4m8 4v6"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Belum ada data barang</h3>
        <p class="opacity-70 max-w-md mx-auto mb-6">Mulai dengan menambahkan data barang inventaris sarana dan prasarana.</p>
        <a href="{{ route('goods.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Barang Pertama
        </a>
    </div>
    @endif
</div>

@endsection
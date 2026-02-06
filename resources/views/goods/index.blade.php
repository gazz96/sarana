@extends('layouts')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-base-100 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content mb-1">Inventaris Barang</h1>
                <p class="text-sm text-base-content/70">
                    Kelola data barang dan inventaris sarana prasarana
                </p>
            </div>
            <div>
                <a href="{{ route('goods.create') }}" class="btn text-white border-none rounded-xl" style="background-color: #295EA4;" onmouseover="this.style.backgroundColor='#1E4578'" onmouseout="this.style.backgroundColor='#295EA4'">
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

    <!-- Search Card -->
    <div class="bg-base-100 rounded-2xl p-6 shadow-sm">
        <form action="" method="GET">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1 relative">
                    <input name="s" type="text" 
                           class="input input-bordered w-full pl-12 rounded-xl" 
                           placeholder="Cari barang berdasarkan kode atau nama..." 
                           value="{{ request('s') }}">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request('s'))
                    <button type="button" onclick="window.location.href='{{ route('goods.index') }}'" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-circle btn-ghost btn-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    @endif
                </div>
                <div>
                    <button type="submit" class="btn text-white border-none rounded-xl" style="background-color: #295EA4;" onmouseover="this.style.backgroundColor='#1E4578'" onmouseout="this.style.backgroundColor='#295EA4'">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-base-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead class="" style="background: linear-gradient(135deg, rgba(41, 94, 164, 0.05) 0%, rgba(255, 203, 79, 0.1) 100%);">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Lokasi</th>
                        <th>Merk</th>
                        <th>Detail</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($goods as $good)
                    <tr class="hover:bg-base-200/50" style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 203, 79, 0.05)'" onmouseout="this.style.backgroundColor='transparent'">
                        <td>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background: linear-gradient(135deg, rgba(41, 94, 164, 0.1) 0%, rgba(255, 203, 79, 0.2) 100%);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #295EA4;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v18l-8-4m8 4v6"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="badge badge-sm text-white mb-1" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%);">{{ $good->code }}</div>
                                    <div class="font-semibold text-base-content">{{ $good->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="badge badge-outline bg-base-200">{{ $good->location->name ?? '-' }}</div>
                        </td>
                        <td class="text-base-content/80">{{ $good->merk ?: '-' }}</td>
                        <td class="max-w-xs truncate text-base-content/70">{{ $good->detail ?: '-' }}</td>
                        <td>
                            @if($good->status === 'AKTIF')
                                <div class="badge bg-success/10 text-success border-success/20">AKTIF</div>
                            @else
                                <div class="badge text-white" style="background-color: #FFCB4F; color: #1E4578;">TIDAK AKTIF</div>
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{route('goods.edit', $good)}}" class="btn btn-ghost btn-sm btn-circle" title="Edit" style="" onmouseover="this.style.backgroundColor='rgba(255, 203, 79, 0.2)'" onmouseout="this.style.backgroundColor='transparent'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{route('goods.destroy', $good)}}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-ghost btn-sm btn-circle text-error hover:bg-error/10" title="Hapus" onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($goods->hasPages())
        <div class="border-t border-base-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-base-content/70">
                    Menampilkan <span class="font-semibold text-base-content">{{ $goods->firstItem() }}</span> sampai 
                    <span class="font-semibold text-base-content">{{ $goods->lastItem() }}</span> dari 
                    <span class="font-semibold text-base-content">{{ $goods->total() }}</span> hasil
                </div>
                <div class="join">
                    @if($goods->onFirstPage())
                        <button class="join-item btn btn-sm" disabled>«</button>
                    @else
                        <a href="{{ $goods->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
                    @endif
                    
                    @foreach($goods->getUrlRange(1, $goods->lastPage()) as $page => $url)
                        @if($page == $goods->currentPage())
                            <button class="join-item btn btn-sm" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%); color: white; border: none;">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="join-item btn btn-sm" style="">{{ $page }}</a>
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

    <!-- Empty State -->
    @if(!$goods->count() && !request('s'))
    <div class="text-center py-16 bg-base-100 rounded-2xl">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6" style="background: linear-gradient(135deg, rgba(41, 94, 164, 0.1) 0%, rgba(255, 203, 79, 0.2) 100%);">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #295EA4;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v18l-8-4m8 4v6"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-base-content mb-2">Belum ada data barang</h3>
        <p class="text-base-content/70 max-w-md mx-auto mb-6">Mulai dengan menambahkan data barang inventaris sarana dan prasarana untuk memudahkan manajemen aset.</p>
        <a href="{{ route('goods.create') }}" class="btn text-white border-none rounded-xl" style="background: linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%);" onmouseover="this.style.background='linear-gradient(135deg, #1E4578 0%, #E5B040 100%)'" onmouseout="this.style.background='linear-gradient(135deg, #295EA4 0%, #FFCB4F 100%)'">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Barang Pertama
        </a>
    </div>
    @endif
</div>

@endsection
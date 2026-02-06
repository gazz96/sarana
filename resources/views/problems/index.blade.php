@extends('layouts')

@section('content')

<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-base-100 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content mb-1">Laporan Kerusakan</h1>
                <p class="text-sm text-base-content/70">
                    Kelola dan monitor laporan kerusakan sarana prasarana
                </p>
            </div>
            @if(auth()->user()->hasRole(['admin', 'guru', 'pimpinan', 'super user']))
            <div>
                <a href="{{ route('problems.create') }}" class="btn text-white border-none rounded-xl" style="background-color: #295EA4;" onmouseover="this.style.backgroundColor='#1E4578'" onmouseout="this.style.backgroundColor='#295EA4'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Laporan
                </a>
            </div>
            @endif
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
                           placeholder="Cari berdasarkan kode laporan..." 
                           value="{{ request('s') }}">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if(request('s'))
                    <button type="button" onclick="window.location.href='{{ route('problems.index') }}'" class="absolute right-2 top-1/2 -translate-y-1/2 btn btn-circle btn-ghost btn-sm">
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
            {!! $table !!}
        </div>

        <!-- Pagination -->
        @if(isset($problems) && $problems->hasPages())
        <div class="border-t border-base-200 px-6 py-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-base-content/70">
                    Menampilkan <span class="font-semibold text-base-content">{{ $problems->firstItem() }}</span> sampai 
                    <span class="font-semibold text-base-content">{{ $problems->lastItem() }}</span> dari 
                    <span class="font-semibold text-base-content">{{ $problems->total() }}</span> hasil
                </div>
                <div class="join">
                    @if($problems->onFirstPage())
                        <button class="join-item btn btn-sm" disabled>«</button>
                    @else
                        <a href="{{ $problems->previousPageUrl() }}" class="join-item btn btn-sm">«</a>
                    @endif
                    
                    @foreach($problems->getUrlRange(1, $problems->lastPage()) as $page => $url)
                        @if($page == $problems->currentPage())
                            <button class="join-item btn btn-sm btn-active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="join-item btn btn-sm">{{ $page }}</a>
                        @endif
                    @endforeach
                    
                    @if($problems->hasMorePages())
                        <a href="{{ $problems->nextPageUrl() }}" class="join-item btn btn-sm">»</a>
                    @else
                        <button class="join-item btn btn-sm" disabled>»</button>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Empty State -->
    @if(!isset($problems) || ($problems->count() === 0 && !request('s')))
    <div class="text-center py-16 bg-base-100 rounded-2xl">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-6" style="background-color: rgba(41, 94, 164, 0.1);">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #295EA4;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-base-content mb-2">Belum ada laporan kerusakan</h3>
        <p class="text-base-content/70 max-w-md mx-auto mb-6">Mulai dengan membuat laporan kerusakan sarana dan prasarana untuk memulai proses perbaikan dengan cepat.</p>
        @if(auth()->user()->hasRole(['admin', 'guru', 'pimpinan', 'super user']))
        <a href="{{ route('problems.create') }}" class="btn text-white border-none rounded-xl" style="background-color: #295EA4;" onmouseover="this.style.backgroundColor='#1E4578'" onmouseout="this.style.backgroundColor='#295EA4'">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Laporan Pertama
        </a>
        @endif
    </div>
    @endif
</div>

@endsection
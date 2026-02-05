@extends('layouts')

@section('content')

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Laporan Kerusakan</h1>
                <p class="text-sm opacity-70 mt-1">Kelola dan monitor laporan kerusakan sarana prasarana</p>
            </div>
            @if(auth()->user()->hasRole(['admin', 'guru', 'pimpinan', 'super user']))
            <div>
                <a href="{{ route('problems.create') }}" class="btn btn-primary">
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
                                       placeholder="Cari berdasarkan kode laporan..." 
                                       value="{{ request('s') }}">
                                @if(request('s'))
                                <button type="button" onclick="window.location.href='{{ route('problems.index') }}'" class="btn btn-square btn-ghost">
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
                {!! $table !!}
            </div>

            <!-- Pagination -->
            @if(isset($problems) && $problems->hasPages())
            <div class="border-t border-base-300 px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm opacity-70">
                        Menampilkan <span class="font-medium">{{ $problems->firstItem() }}</span> sampai 
                        <span class="font-medium">{{ $problems->lastItem() }}</span> dari 
                        <span class="font-medium">{{ $problems->total() }}</span> hasil
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
    </div>

    <!-- Empty State -->
    @if(!isset($problems) || ($problems->count() === 0 && !request('s')))
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary/10 mb-4">
            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold mb-2">Belum ada laporan kerusakan</h3>
        <p class="opacity-70 max-w-md mx-auto mb-6">Mulai dengan membuat laporan kerusakan sarana dan prasarana untuk memulai proses perbaikan.</p>
        @if(auth()->user()->hasRole(['admin', 'guru', 'pimpinan', 'super user']))
        <a href="{{ route('problems.create') }}" class="btn btn-primary">
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
<html>

<head>
    <title>CETAK PERMOHONAN PERBAIKAN INVENTARIS</title>
    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-size: 14px;
        }

        .wrapper {
            width: 100%;
            max-width: 900px;
            margin: 30px auto;
            border: 1px solid #222;
            padding: 10px;
        }

        .table {
            border-collapse: collapse;
            width: 100%
        }

        .table.table-bordered tr th,
        .table.table-bordered tr td {
            border: 1px solid #222;
            padding: 4px;
        }

        .row {
            margin-right: -10px;
            margin-left: -10px;
            box-sizing: border-box;
        }

        .row [class*="col-"] {
            float: left;
            box-sizing: border-box;
            padding-left: 10px;
            padding-right: 10px;
            position: relative;
        }

        .row::before {
            display: table;
            content: ' ';
            clear: both;
        }

        .row::after {
            display: table;
            content: '';
            clear: both;
        }

        .col-3 {
            width: 25%;
        }

        .col-12 {
            width: 100%;
        }

        .text-center {
            text-align: center
        }

        .text-right {
            text-align: right;
        }

        .clearfix::after {
            display: table;
            content: '';
            clear: both;
        }
    </style>
</head>
<body>

    <div class="wrapper">

        <div class="row">
            <div class="col-12">

                <div style="margin-bottom: 32px;">
                    <h3 style="font-size: 24px; text-align: center; margin-bottom: 10px;">SEKOLAH ISLAM TERPADU AL MUSABBIHIN</h3>
                    <p class="text-center">PERMOHONAN PERBAIKAN INVENTARIS</p>
                </div>

                <table>
                    <tr>
                        <td>TANGGAL PENGAJUAN</td>
                        <td>: {{ date('d F Y', strtotime($problem->date)) }}</td>
                    </tr>

                    <tr>
                        <td>NAMA</td>
                        <td>: {{ strtoupper($problem->user->name ?? '-') }}</td>
                    </tr>

                    <tr>
                        <td>JABATAN</td>
                        <td>: {{ strtoupper($problem->user->role->name ?? '-') }}</td>
                    </tr>
                    
                    <tr>
                        <td>STATUS</td>
                        <td>: {{\App\Models\Problem::$STATUS[$problem->status ?? 0]}}</td>
                    </tr>
                </table>

                <p>DENGAN INI MENGAJUKAN PERMOHONAN PERSETUJUAN UNTUK PERBAIKAN SESUAI DENGAN RINCIAN DIBAWAH INI:</p>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA BARANG</th>
                            <th>NOMOR IVENTARIS</th>
                            <th>LOKASI BARANG</th>
                            <th>HARGA</th>
                            <th>ALASAN PERBAIKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($problem->items()->get() as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->good->name ?? '-'}}</td>
                                <td>{{ $item->good->code ?? '-' }}</td>
                                <td>{{ $item->good->location->name ?? '-' }}</td>
                                <td class="text-right">{{ number_format($item->price) }}</td>
                                <td>{{ $item->issue }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-center">GRADTOTAL</td>
                            <td class="text-right">{{ number_format($problem->items()->sum('price')) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
        <div class="clearfix" style="margin-bottom: 20px;"></div>
        <div class="row">
            <div class="col-12">
                <div>CATATAN</div>
                <div style="border: 1px solid #222; min-height: 80px;"></div>
            </div>
        </div>

        <div class="clearfix" style="margin-bottom: 20px;"></div>
        <div class="row">
            <div class="col-3">
                <div class="text-center">YANG MENGAJUKAN</div>
                <div style="border-bottom: 1px solid #222; padding-bottom: 80px;"></div>
                {{ strtoupper($problem->user->name ?? '') }}
            </div>

            <div class="col-3">
                <div class="text-center">DIKETAHUI</div>
                <div style="border-bottom: 1px solid #222; padding-bottom: 80px;"></div>
                {{ strtoupper($problem->user_management->name ?? '') }}
            </div>

            <div class="col-3">
                <div class="text-center">PETUGAS/TEKNISI</div>
                <div style="border-bottom: 1px solid #222; padding-bottom: 80px;"></div>
                {{ strtoupper($problem->technician->name ?? '') }}
            </div>

            <div class="col-3">
                <div class="text-center">KEPALA BIDANG KEUANGAN</div>
                <div style="border-bottom: 1px solid #222; padding-bottom: 80px;"></div>
                {{ strtoupper($problem->finance->name ?? '') }}
            </div>

            

        </div>

        
    </div>
</body>

</html>

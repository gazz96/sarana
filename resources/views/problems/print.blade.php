<h3>SEKOLAH ISLAM TERPADU AL MUSABBIHIN</h3> 
<p>PERMOHONAN PERBAIKAN INVENTARIS</p>


<table>
    <tr>
        <td>TANGGAL PENGAJUAN</td>
        <td>: {{date('d F Y', strtotime($problem->date))}}</td>
    </tr>

    <tr>
        <td>NAMA</td>
        <td>: {{$problem->user->email ?? '-'}}</td>
    </tr>

    <tr>
        <td>JABATAN</td>
        <td>: {{$problem->user->role ?? 'Tata Usaha'}}</td>
    </tr>
</table>

<p>DENGAN INI MENGAJUKAN PERMOHONAN PERSETUJUAN UNTUK PERBAIKAN SESUAI DENGAN RINCIAN DIBAWAH INI:</p>

<table>
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
                <td>{{$i++}}</td>
                <td>{{$item->good->name}}</td>
                <td>{{$item->good_id}}</td>
                <td>{{$item->good->location->name ?? '-'}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->problem}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">GRADTOTAL</td>
            <td>{{$problem->items()->sum('price')}}</td>
        </tr>
    </tfoot>
<table>
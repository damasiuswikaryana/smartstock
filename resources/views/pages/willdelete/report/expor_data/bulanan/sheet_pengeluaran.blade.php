@php
    $styleRH = 'background-color: navy;color: white;';
    $styleNA = 'background-color: yellow;color: black;';
    $styleH = 'background-color: white;color: black;';
    $styleX = 'background-color: pink;color: red;';
@endphp
<table>
    <thead>
        <tr>
            <th>Laporan Pendapatan Bulanan</th>
        </tr>
        <tr>
            <th>{{ strtoupper($data_outlet->nama) }} PERIODE {{ TanggalBulan($awal).' - '. TanggalBulan($akhir) }}</th>
        </tr>
        <tr>
            <th></th>
        </tr>
        <tr>
            <th><b>Pengeluaran Outlet dan Gerobak</b></th>
        </tr>
        <tr>
            <th style='border:1px solid #000; {{ $styleRH }}'>NO.</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TIPE</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>GEROBAK / OUTLET</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>PENGELUARAN</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>JUMLAH</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>TANGGAL</th>
            <th style='border:1px solid #000; {{ $styleRH }}'>STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data_pengeluaran_all as $dt_pengeluaran)
        @php
        @endphp
        <tr>
            <td style='border:1px solid #000;'>{{ $loop->iteration }}</td>
            <td style='border:1px solid #000;'>{{ $dt_pengeluaran->pengeluaran_tipe }}</td>
            <td style='border:1px solid #000;'>{{ $dt_pengeluaran->pengeluaran_tipe == "Gerobak" ? $dt_pengeluaran->gerobak->kode : $dt_pengeluaran->outlet->nama }}</td>
            <td style='border:1px solid #000;'>{{ $dt_pengeluaran->pengeluaran_nama }}</td>
            <td style='border:1px solid #000;'>{{ rupiah($dt_pengeluaran->pengeluaran_harga) }}</td>
            <td style='border:1px solid #000;'>{{ tanggalIndo($dt_pengeluaran->pengeluaran_date) }}</td>
            <td style='border:1px solid #000;'>{{ $dt_pengeluaran->pengeluaran_status }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th></th>
            <th colspan="5">Diekspor melalui Management App pada : {{ Carbon\Carbon::now()->locale('id')->isoFormat('dddd, LL HH:mm'); }}</th>
        </tr>
    </tfoot>
</table>
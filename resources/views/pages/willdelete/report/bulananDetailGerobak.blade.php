<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Transaksi {{ $data_gerobak->kode }} - {{ $bulan_tahun }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body px-2 pt-0 pb-2">
    <div class="col-12 mt-3 mb-2">
        <div class="form-search" style="display:block;">
            <i class="ph-duotone ph-magnifying-glass icon-search"></i>
            <input type="search" id="search_detail_report_gerobak" class="form-control" placeholder="Cari data disini..." style="max-width:100%;">
        </div>
    </div>
    @php
        $thead = ['No', 'Tanggal', 'Produk Terjual', 'Cash', 'Qris', 'Total', 'Status', 'Opsi'];
    @endphp
    <x-datatable id="detail_report_gerobak" :thead=$thead :filter="null">
        @foreach ($data_trans_gerobak as $trans)
        <tr
        data-child-value="
        <tr class='bg-light'>
            <td class='p-2'><b>Produk</b></td>
            <td class='p-2'><b>Kategori</b></td>
            <td class='p-2 text-end'><b>Harga</b></td>
            <td class='p-2 text-center'><b>Stock</b></td>
            <td class='p-2 text-center'><b>Terjual</b></td>
            <td class='p-2 text-end'><b>Penjualan</b></td>
        </tr>
        @php $total_penjualan = 0; @endphp
        @foreach ($trans->product as $trans_prod)
        <tr class='bg-light'>
            <td class='p-2'>{{ $trans_prod->product->nama }}</td>
            <td class='p-2'>{{ $trans_prod->product->category->title }}</td>
            <td class='p-2 text-end'>{{ rupiah($trans_prod->product_harga) }}</td>
            <td class='p-2 text-center'>
                <h5 class='mb-0'><span class='badge bg-light-secondary'>{{ $trans_prod->stock->sum('stock') }}</span></h5>
            </td>
            <td class='p-2 text-center'>
                <h5 class='mb-0'><span class='badge bg-light-secondary'>{{ $trans_prod->product_sales }}</span></h5>
            </td>
            @php $subtotal = $trans_prod->product_sales * $trans_prod->product_harga; @endphp
            <td class='p-2 text-end'>{{ rupiah($subtotal) }}</td>
            @php $total_penjualan = $total_penjualan + $subtotal; @endphp
        </tr>
            @foreach($trans_prod->varian as $varian)
            <tr class='bg-light'>
                <td class='p-2'><i class='ms-2 me-2 material-icons-two-tone'>subdirectory_arrow_right</i> {{ $varian->nama }}</td>
                <td class='p-2'>{{ $varian->category->title }}</td>
                <td class='p-2 text-end'>{{ rupiah($varian->product_harga) }}</td>
                <td class='p-2 text-center'></td>
                <td class='p-2 text-center'>
                    <h5 class='mb-0'><span class='badge bg-light-secondary'>{{ $varian->product_sales }}</span></h5>
                </td>
                @php $subtotalVarian = $varian->product_sales * $varian->product_harga; @endphp
                <td class='p-2 text-end'>{{ rupiah($subtotalVarian) }}</td>
                @php $total_penjualan = $total_penjualan + $subtotalVarian; @endphp
            </tr>
            @endforeach
        @endforeach
        <tr class='bg-light'>
            <td class='p-2'><b>Total Penjualan</b></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2 text-end fw-bold'>{{ rupiah($total_penjualan) }}</td>
        </tr>
        @php $total_nominal = 0; @endphp
        @if ($trans->transaction_nominal->count() > 0)
        <tr class='bg-light'>
            <td class='p-2'><b>Pembayaran Cash</b></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2 text-end fw-bold'>{{ rupiah($trans->transaction_nominal->where('metode_bayar', 'Cash')->sum('transaction_amount')) }}</td>
            @php 
              $total_nominal = $total_nominal + $trans->transaction_nominal->where('metode_bayar', 'Cash')->sum('transaction_amount');
            @endphp
        </tr>
        <tr class='bg-light'>
            <td class='p-2'><b>Pembayaran Qris</b></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2 text-end fw-bold'>{{ rupiah($trans->transaction_nominal->where('metode_bayar', 'Qris')->sum('transaction_amount')) }}</td>
            @php 
              $total_nominal = $total_nominal + $trans->transaction_nominal->where('metode_bayar', 'Qris')->sum('transaction_amount');
            @endphp
        </tr>
        @endif
        <tr class='bg-light'>
            <td class='p-2'><b>Total Pengeluaran</b></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2 text-end text-danger'>- {{ $trans->pengeluaran->count() > 0 ? rupiah($trans->pengeluaran->sum('pengeluaran_harga')) : '' }}</td>
        </tr>
        @php
            if ($trans->pengeluaran->count() > 0) {
                $total_penjualan_after_pengeluaran = $total_penjualan - $trans->pengeluaran->sum('pengeluaran_harga');
            } else {
                $total_penjualan_after_pengeluaran = $total_penjualan;
            }
        @endphp
        <tr class='bg-light'>
            <td class='p-2'><b>Total Pendapatan</b></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2'></td>
            <td class='p-2 text-end fw-bold'>
                @if ($total_nominal == $total_penjualan_after_pengeluaran)
                (Balanced)
                @elseif ($total_nominal > $total_penjualan_after_pengeluaran)
                <span class='fw-medium text-success'>(+ {{ rupiah($total_nominal - $total_penjualan_after_pengeluaran) }})</span>
                @else
                <span class='fw-medium text-danger'>(- {{ rupiah($total_penjualan_after_pengeluaran - $total_nominal) }})</span>
                @endif
                {{ rupiah($total_penjualan_after_pengeluaran) }}
            </td>
        </tr>
        ">
            <td>{{ $loop->iteration }}</td>
            <td>{{ tanggalIndo($trans->trans_date) }}</td>
            <td class='text-center'>
                <h5 class='mb-0'><span class='badge bg-light-secondary'>{{ $trans->transaction_produk->sum('product_sales') }}</span></h5>
            </td>
            <td class='text-end'>{{ rupiah($trans->transaction_nominal->where('metode_bayar', 'Cash')->sum('transaction_amount')) }}</td>
            <td class='text-end'>{{ rupiah($trans->transaction_nominal->where('metode_bayar', 'Qris')->sum('transaction_amount')) }}</td>
            <td class='text-end'>{{ rupiah($trans->transaction_nominal->sum('transaction_amount')) }}</td>
            <td class='text-center'>
                {!! $trans->trans_status == 'Approved' ? "<h5 class='mb-0'><span class='badge bg-success'>Approved</span></h5>" : "<h5 class='mb-0'><span class='badge bg-light-danger'>Pending</span></h5>" !!}
            </td>
            <td class='text-center'>
                <a title="Detail Data" href="javascript:void(0);" class="dt-control avtar avtar-s btn-link-info btn-pc-default btn-edit"><i class="ti ti-caret-down f-20"></i></a>
            </td>
        </tr>
        @endforeach
    </x-datatable>
    
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header px-3 py-2">
                <h4>Summary Penjualan</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rekapProduk as $rp)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $rp['nama_produk'] }}</td>
                            <td>{{ $rp['category'] }}</td>
                            <td>{{ $rp['total_qty'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/b-3.1.1/b-html5-3.1.1/fc-5.0.1/fh-4.0.1/r-3.0.2/sl-2.0.4/datatables.min.js"></script>
<script type="text/javascript">
    function format(data) {
        return `<table class="table bg-light mb-0" style="width:100%">${data}</table>`;
    }
    $(document).ready(function() {
        let table = $("#detail_report_gerobak").DataTable({
            lengthMenu: [10, 20, 30, 40, 50, 100],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
        });
        
        $('#search_detail_report_gerobak').keyup(function() {
            table.search($(this).val()).draw();
        });
        
        $('#detail_report_gerobak tbody').on('click', 'td a.dt-control', function() {
            const tr = $(this).closest('tr');
            const row = table.row(tr[0]);
            if (!row.length) return;
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            } else {
                row.child(format(tr.attr('data-child-value'))).show();
                tr.next('tr').find('td').addClass('p-0');
                tr.addClass('shown');
            }
        });
        
        table.on('draw', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            tooltipTriggerList1.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    });
</script>
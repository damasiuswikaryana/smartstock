@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="List Produk" module="Data Produk" >
        <li class="breadcrumb-item">Master Produk</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="ph-duotone ph-plus-circle icon-search me-2"></i> Tambah Produk Baru</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
            </div>
        </div>
    </div>
    
    @php
        $thead = ['Nama Produk', 'Kategori', 'Harga', 'Satuan', 'Opsi'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
    
    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Produk Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                @php
                    $hour = now()->format('H');
                @endphp
                @if($hour >= 21 && $hour < 23)
                <form class="modal-body" method="post" id="form-tambah">
                    <div class="px-4">
                        @csrf
                        <!-- @method('POST') -->
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Kategori Produk:</label>
                            <div class="col-lg-8">
                                <div class="">
                                    <select name="kategori" class="form-select select2">
                                        @foreach(App\Models\Category::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Nama Produk:</label>
                            <div class="col-lg-8">
                                <div class="">
                                    <input type="text" class="form-control" placeholder="Nama Produk"
                                        name="nama" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Detail:</label>
                            <div class="col-lg-8 row pe-0">
                                <div class="col-12 col-lg-6">
                                    <input type="text" class="form-control" placeholder="Satuan"
                                        name="satuan" />
                                </div>
                                <div class="col-12 col-lg-6 pe-0">
                                    <input type="text" class="form-control number-separator" placeholder="Harga Jual"
                                        name="harga" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Tampilkan di Gerobak:</label>
                            <div class="col-lg-8 row">
                                <div class="col-12 col-lg-6">
                                    <select name="showin_gerobak" class="form-select select2">
                                        <option value=1>Ya</option>
                                        <option value=0>Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Tampilkan di Outlet:</label>
                            <div class="col-lg-8 row">
                                <div class="col-12 col-lg-6">
                                    <select name="showin_outlet" class="form-select select2">
                                        <option value=1>Ya</option>
                                        <option value=0>Tidak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Produk Varian:</label>
                            <div class="col-lg-8 row">
                                <div class="col-12 col-lg-6">
                                    <select name="is_varian" id="is_varian" class="form-select select2">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <select name="product_master_id" id="product_master_id" class="form-select select2">
                                        @foreach ($data_product as $product)
                                        <option value="{{ $product->id }}">{{ $product->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah">Submit Data</button>
                </div>
                @else
                <div class="modal-body">
                    <div class="alert alert-info">
                        <p class="mb-0 text-center">Penambahan produk hanya dapat dilakukan setelah closing, pada jam 21:00 - 23:00</p>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $('#product_master_id').hide();
        
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });
        
        $('#is_varian').on('change', function() {
            var selected = $(this).val();
            if (selected === '0') { $('#product_master_id').hide(); } 
            else { $('#product_master_id').show(); }
        });
            
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.index') }}",
            lengthMenu: [10, 20, 30, 40, 50, 100],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            ordering: false,
            columns: [
                { data: 'nama',     name: 'nama', },
                { data: 'kategori', name: 'kategori', },
                { data: 'harga',    name: 'harga', class: 'text-end', },
                { data: 'satuan',   name: 'satuan', class: 'text-center', },
                { data: 'action',   name: 'action', class: 'text-center', width: '5%', orderable: false, searchable: false, },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
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
        
        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: '{{ route("product.simpan") }}', // Route untuk simpan data
              method: 'POST',
              data: $(this).serialize(),
              beforeSend: showLoader(),
              success: function(response) {
                $('#form-tambah')[0].reset();
                table.ajax.reload(null, false);
                hideLoader();
                $('#exampleModalCenter').modal('hide');
                showToastSuccess("Data berhasil ditambahkan");
              },
              error: function(xhr) {
                hideLoader();
                showToastError("Gagal menambahkan data");
              }
            });
        });
        
        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');
            var url = "{{ route('product.hapus', ':id:') }}";
            var url = url.replace(':id:', id);
            
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        table.ajax.reload(null, false);
                        hideLoader();
                        showToastSuccess("Berhasil menghapus data");
                    },
                    error: function () {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat menghapus data");
                    }
                });
            }
        });
    </script>
    
@endpush

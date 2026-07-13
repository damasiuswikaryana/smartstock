@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Werehouse List" module="Werehouse Management">
        <li class="breadcrumb-item">Master Data</li>
        <li class="breadcrumb-item">Werehouse</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add
                Werehouse</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search here...">
            </div>
        </div>
    </div>

    @php
        $thead = ['Werehouse', 'Address', 'Kabupaten', 'Provinsi', 'Latitude', 'Longitude', 'Last Updated', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Werehouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Werehouse Name:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Input werehouse name"
                                    name="nama">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Address:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Input werehouse address"
                                    name="alamat">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Area:</label>
                            <div class="col-lg-8 row">
                                <div class="col-12 col-lg-6">
                                    <input type="text" class="form-control" placeholder="Kabupaten" name="kabupaten" />
                                </div>
                                <div class="col-12 col-lg-6 pe-0">
                                    <input type="text" class="form-control" placeholder="Provinsi" name="provinsi" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Coordinates:</label>
                            <div class="col-lg-8 row">
                                <div class="col-12 col-lg-6">
                                    <input type="text" class="form-control" placeholder="Latitude" name="lat" />
                                </div>
                                <div class="col-12 col-lg-6 pe-0">
                                    <input type="text" class="form-control" placeholder="Longitude" name="long" />
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('outlet.index') }}",
            scrollY: '300px',
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            },
            lengthMenu: [10, 20, 30, 40, 50, 100],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
            columns: [{
                    data: 'nama',
                    name: 'nama',
                    class: 'py-1 fw-bold',
                },
                {
                    data: 'alamat',
                    name: 'alamat',
                    class: 'py-1',
                },
                {
                    data: 'kabupaten',
                    name: 'kabupaten',
                    class: 'py-1',
                },
                {
                    data: 'provinsi',
                    name: 'provinsi',
                    class: 'py-1',
                },
                {
                    data: 'lat',
                    name: 'lat',
                    visible: true,
                    class: 'py-1',
                },
                {
                    data: 'long',
                    name: 'long',
                    visible: true,
                    class: 'py-1',
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    visible: true,
                    class: 'py-1',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center py-1',
                    orderable: false,
                    searchable: false,
                },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });

        table.on('draw', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            tooltipTriggerList1.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('outlet.simpan') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah')[0].reset();
                        table.ajax.reload(null, false);
                        hideLoader();
                        $('#exampleModalCenter').modal('hide');
                        showToastSuccess("Data berhasil ditambahkan");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Gagal menambahkan data");
                }
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('outlet.hapus', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function(res) {
                        table.ajax.reload(null, false);
                        hideLoader();
                        showToastSuccess("Berhasil menghapus data");
                    },
                    error: function() {
                        hideLoader();
                        showToastError("Terjadi kesalahan saat menghapus data");
                    }
                });
            }
        });
    </script>
@endpush

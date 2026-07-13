@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="List Item" module="Item Management">
        <li class="breadcrumb-item">Item Master</li>
        <li class="breadcrumb-item">Item</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add New
                Item</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search...">
            </div>
        </div>
    </div>

    @php
        $thead = ['Code', 'Item Name', 'Vendor', 'Category', 'Last Updated', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="row px-4">
                        @csrf
                        @method('POST')
                        <div class="col-6">
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Item Code:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" placeholder="Item Code (3 characters only)"
                                        name="kode" maxlength="3" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Item Name:</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" placeholder="Item Name" name="nama" />
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Vendor:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="vendor_id">
                                        @foreach ($vendor as $v)
                                            <option value="{{ $v->id }}">
                                                {{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-lg-4 col-form-label">Category:</label>
                                <div class="col-lg-8">
                                    <select class="form-control" name="category_id">
                                        @foreach ($category as $c)
                                            <option value="{{ $c->id }}">
                                                {{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Description:</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="description"></textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-lg-2 col-form-label">Catatan:</label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="catatan"></textarea>
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
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('item.index') }}",
            scrollY: true,
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
                    data: 'kode',
                    name: 'kode',
                    class: 'text-center py-1',
                },
                {
                    data: 'nama',
                    name: 'nama',
                    class: 'py-1 fw-bold',
                },
                {
                    data: 'vendor',
                    name: 'vendor',
                    class: 'py-1',
                },
                {
                    data: 'category',
                    name: 'category',
                    class: 'py-1 text-center',
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                    class: 'text-center py-1',
                    visible: true,
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
                url: '{{ route('item.simpan') }}', // Route untuk simpan data
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
            var url = "{{ route('item.hapus', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Delete this data?')) {
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
                        showToastSuccess("Data has been deleted");
                    },
                    error: function() {
                        hideLoader();
                        showToastError("Error while deleting data");
                    }
                });
            }
        });
    </script>
@endpush

@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Vendor List" module="Vendor Management">
        <li class="breadcrumb-item">Item Master</li>
        <li class="breadcrumb-item">Vendor</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add Vendor</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search here...">
            </div>
        </div>
    </div>

    @php
        $thead = ['Vendor', 'Phone', 'Email', 'Bank', 'Account', 'Down Payment', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-2">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-6">
                                <h4 class="fw-bold mb-3">Vendor Info</h4>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Name:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Vendor name" name="nama"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Code:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Vendor code" name="kode"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Address:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Vendor address"
                                            name="alamat" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Postal Code:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Postal code" name="kode_pos"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Kabupaten:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Kabupaten" name="kabupaten"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Province:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Province" name="provinsi"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Country:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Country" name="negara"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Phone:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control"
                                            placeholder="Vendor phone number / Whatsapp" name="phone" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Email:</label>
                                    <div class="col-lg-8">
                                        <input type="email" class="form-control" placeholder="Vendor email"
                                            name="email" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Website:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Vendor website"
                                            name="website" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Status:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="status">
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Incative
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <h4 class="fw-bold mb-3">Payment Info</h4>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">NPWP:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="NPWP" name="npwp"
                                            value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Bank:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="bank_id">
                                            @foreach ($bank as $b)
                                                <option value="{{ $b->id }}">
                                                    {{ $b->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Bank Account Number:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Bank Account Number"
                                            name="bank_account_number" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Bank Account Name:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Bank Account Name"
                                            name="bank_account_name" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">DP Availibility:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="is_dp">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-5 row">
                                    <label class="col-lg-4 col-form-label">Terms of Payment:</label>
                                    <div class="col-lg-8">
                                        <input type="number" class="form-control" placeholder="Ex: 30 (in days)"
                                            name="termin_pembayaran" value="">
                                    </div>
                                </div>

                                <h4 class="fw-bold mb-3">Person in Charge Info</h4>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">PIC Name:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="PIC Name"
                                            name="pic_name" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Position:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Position"
                                            name="pic_jabatan" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3 row">
                                    <label class="col-lg-12 col-form-label pb-1">Note:</label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="catatan"></textarea>
                                    </div>
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
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
            ajax: "{{ route('vendor.index') }}",
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
                    data: 'phone',
                    name: 'phone',
                    class: 'py-1',
                },
                {
                    data: 'email',
                    name: 'email',
                    class: 'py-1',
                },
                {
                    data: 'bank',
                    name: 'bank',
                    class: 'py-1 text-center',
                },
                {
                    data: 'bank_account_number',
                    name: 'bank_account_number',
                    visible: true,
                    class: 'py-1 text-center',
                },
                {
                    data: 'is_dp',
                    name: 'is_dp',
                    visible: true,
                    class: 'py-1 text-center',
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
                url: '{{ route('vendor.simpan') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah')[0].reset();
                        table.ajax.reload(null, false);
                        hideLoader();
                        $('#exampleModalCenter').modal('hide');
                        showToastSuccess("Data has been added");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while adding data");
                }
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('vendor.hapus', ':id:') }}";
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

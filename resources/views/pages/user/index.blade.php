@extends('layouts.main')

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="User List" module="Users Management">
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Users</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add New
                User</button>
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search...">
            </div>
        </div>
    </div>

    @php
        $thead = ['User', 'Werehouse', 'Phone', 'Username', 'Status', 'Opsi'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Employee ID:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Enter employee ID" name="emp_id">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Name:</label>
                            <div class="col-lg-8 row me-0 pe-0">
                                <div class="col-12 col-lg-6">
                                    <input type="text" class="form-control" placeholder="First Name" name="firstname">

                                </div>
                                <div class="col-12 col-lg-6 pe-0">
                                    <input type="text" class="form-control" placeholder="Last Name" name="lastname">

                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Username:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Enter username" name="username">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Email:</label>
                            <div class="col-lg-8">
                                <input type="email" class="form-control" placeholder="Enter email" name="email">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Phone Number:</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control" placeholder="Enter phone number" name="phone">
                            </div>
                        </div>
                        <hr />
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Job Placement:</label>
                            <div class="col-lg-8">
                                <select class="form-select" id="loc_id" name="loc_id">
                                    <option selected disabled>Choose werehouse</option>
                                    @forelse ($outlet as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Roles:</label>
                            <div class="col-lg-8">
                                <select class="form-select" id="roles" name="roles">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Status:</label>
                            <div class="col-lg-8">
                                <select class="form-select" id="status" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
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
            ajax: "{{ route('user.index') }}",
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
                    data: 'user',
                    name: 'user',
                    class: 'py-1',
                },
                {
                    data: 'werehouse',
                    name: 'werehouse',
                    class: 'py-1 text-center',
                },
                {
                    data: 'phone',
                    name: 'phone',
                    class: 'py-1 text-center',
                },
                {
                    data: 'username',
                    name: 'username',
                    class: 'py-1 text-center',
                },
                {
                    data: 'status',
                    name: 'status',
                    class: 'py-1 text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'py-1 text-center',
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
            console.log($(this).serialize());
            e.preventDefault();
            $.ajax({
                url: '{{ route('user.simpan') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah')[0].reset();
                        table.ajax.reload(null, false);
                        hideLoader();
                        $('#exampleModalCenter').modal('hide');
                        showToastSuccess("New user has been added");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while addiing data: " + xhr.responseText);
                }
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('user.hapus', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Delete this user?')) {
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
                        showToastSuccess("User deleted");
                    },
                    error: function(xhr) {
                        hideLoader();
                        showToastError("Error while deleting data: " + xhr.responseText);
                    }
                });
            }
        });
    </script>
@endpush

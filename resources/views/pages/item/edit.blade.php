@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <x-page-header title="Edit Item" module="{{ $data->nama }}">
            <li class="breadcrumb-item">Item Master</li>
            <li class="breadcrumb-item">Item</li>
        </x-page-header>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header p-3">
                    <h4 class="mb-0 card-title">Item Images</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-lg-4 col-form-label">Image 1:</label>
                        <div class="col-lg-8 row">
                            @if ($data->image_master_1 != null)
                                <img src="{{ asset('storage/item/' . $data->image_master_1) }}" class="mb-2"
                                    style="width:40%;" />
                            @endif
                            <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpener">Upload
                                File</button>
                            @if ($data->image_master_1 != null)
                                <span class="f-10 text-danger">Reupload photo for replace previous photo</span>
                            @endif
                        </div>
                    </div>
                    <div class="alert alert-warning text-center mb-0"><b>Note:</b> Photo must be 500 x 500 pixel or ratio.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6 col-xl-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center p-3">
                    <h4 class="mb-0 card-title">General</h4>
                    <button type="submit" class="btn btn-light-primary btn-shadow me-1" name="submit"
                        form="form-edit-general">Save
                        Changes</button>
                </div>
                <div class="card-body">
                    <form class="modal-body" action="" method="post" name="form-edit-general" id="form-edit-general">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tipe" value="general" />
                            <div class="col-12">
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Item Code:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control"
                                            placeholder="Item Code (3 characters only)" name="kode" maxlength="3"
                                            value="{{ $data->kode }}" />
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Item Name:</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" placeholder="Item Name" name="nama"
                                            value="{{ $data->nama }}" />
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Vendor:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="vendor_id">
                                            @foreach ($vendor as $v)
                                                <option @if ($data->vendor_id == $v->id) selected @endif
                                                    value="{{ $v->id }}">
                                                    {{ $v->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0 row">
                                    <label class="col-lg-4 col-form-label">Category:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="category_id">
                                            @foreach ($category as $c)
                                                <option @if ($data->category_id == $c->id) selected @endif
                                                    value="{{ $c->id }}">
                                                    {{ $c->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center p-3">
                    <h4 class="mb-0 card-title">Features</h4>
                    <button type="submit" class="btn btn-light-primary btn-shadow me-1" name="submit"
                        form="form-edit-features">Save
                        Changes</button>
                </div>
                <div class="card-body">
                    <form class="modal-body" action="" method="post" name="form-edit-features"
                        id="form-edit-features">
                        <div class="row">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tipe" value="features" />
                            <div class="col-12">
                                <div class="mb-3 row">
                                    <label class="col-lg-2 col-form-label">Description:</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" name="description">{{ $data->deskripsi }}</textarea>
                                    </div>
                                </div>
                                <div class="mb-0 row">
                                    <label class="col-lg-2 col-form-label">Note:</label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" name="catatan">{{ $data->catatan }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 card-title">Variant</h4>
        <div>
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"
                class="btn btn-primary btn-shadow me-1">Add New Variant</button>
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Search...">
            </div>
        </div>
    </div>
    @php
        $thead = ['Code', 'Variant', 'Value', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    {{-- modal add variant --}}
    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add New Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" method="post" id="form-tambah-varian">
                    <div class="px-4">
                        @method('POST')
                        @csrf
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Variant Code:</label>
                            <div class="col-lg-8">
                                <div class="">
                                    <input type="text" class="form-control" placeholder="Variant Code (3 Characters)"
                                        name="kode_varian" maxlength="3" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Variant Name:</label>
                            <div class="col-lg-8">
                                <div class="">
                                    <input type="text" class="form-control" placeholder="Variant Name"
                                        name="name_varian" />
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-lg-4 col-form-label">Value:</label>
                            <div class="col-lg-8">
                                <div class="">
                                    <input type="text" class="form-control number-separator"
                                        placeholder="Value in rupiah" name="nilai" />
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah-varian">Submit Data</button>
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
    <script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
    <script type="text/javascript">
        $('#form-edit-general').on('submit', function(e) {
            e.preventDefault();
            const id = "{{ $data->id }}";
            var url = "{{ route('item.update', ':id:') }}";
            var url = url.replace(':id:', id);
            $.ajax({
                url: url, // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    showLoader();
                },
                success: function(response) {
                    if (response.success) {
                        hideLoader();
                        showToastSuccess("Data has been updated");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while updating data" + xhr.responseText);
                }
            });
        });
        $('#form-edit-features').on('submit', function(e) {
            e.preventDefault();
            const id = "{{ $data->id }}";
            var url = "{{ route('item.update', ':id:') }}";
            var url = url.replace(':id:', id);
            $.ajax({
                url: url, // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: function() {
                    showLoader();
                },
                success: function(response) {
                    if (response.success) {
                        hideLoader();
                        showToastSuccess("Data has been updated");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while updating data" + xhr.responseText);
                }
            });
        });
        $('#uppyModalOpener').on('click', function(e) {
            e.preventDefault();
            $('#modalEdit').modal('hide');
        });

        //
        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        const id = "{{ $data->id }}";
        var url = "{{ route('item_variant.index', ':id:') }}";
        var url = url.replace(':id:', id);
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
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
                    data: 'kode_varian',
                    name: 'kode_varian',
                    class: 'text-center py-1',
                },
                {
                    data: 'name_varian',
                    name: 'name_varian',
                    class: 'py-1 fw-bold',
                },
                {
                    data: 'nilai',
                    name: 'nilai',
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

        $('#form-tambah-varian').on('submit', function(e) {
            const id = "{{ $data->id }}";
            var url = "{{ route('item_variant.simpan', ':id:') }}";
            var url = url.replace(':id:', id);
            e.preventDefault();
            $.ajax({
                url: url, // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah-varian')[0].reset();
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
            var url = "{{ route('item_variant.hapus', ':id:') }}";
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
    <script type="module">
        // Function for displaying uploaded files
        const onUploadSuccess = (elForUploadedFiles) => (file, response) => {
            const url = response.uploadURL;
            const fileName = file.name;
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = url;
            a.target = '_blank';
            a.appendChild(document.createTextNode(fileName));
            li.appendChild(a);
            document.querySelector(elForUploadedFiles).appendChild(li);
        };

        const productId = {{ $data->id }};
        const uploadUrl = `/item/${productId}/upload-foto`;

        import {
            Uppy,
            Dashboard,
            Webcam,
            XHRUpload,
            DragDrop,
            ProgressBar,
        } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';

        // for popup modal open and upload files
        const uppy = new Uppy({
                debug: true,
                autoProceed: false
            })
            .use(Dashboard, {
                trigger: '#uppyModalOpener'
            })
            .use(Webcam, {
                target: Dashboard
            })
            .use(XHRUpload, {
                endpoint: uploadUrl,
                fieldName: 'file',
                formData: true,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

        uppy.on('success', (fileCount) => {
            console.log(`${fileCount} files uploaded`);
        });
    </script>
@endpush

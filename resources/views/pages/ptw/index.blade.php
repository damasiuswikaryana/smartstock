@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="History" module="Procurement to Warehouse">
        <li class="breadcrumb-item">Procurement</li>
        <li class="breadcrumb-item">Procurement to Warehouse</li>
    </x-page-header>

    <div class="row g-2 align-items-center mb-4 mt-3 justify-content-between p-sm-0">
        <div class="col-12 col-lg-auto">
            <div class="d-flex justify-content-start align-items-center">
                <button type="button"
                    class="btn btn-shadow btn-light-primary d-flex align-items-center justify-content-center mx-1"
                    data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                    <i class="ph-duotone ph-plus-circle me-2"></i>
                    Add New PTW
                </button>

                <button class="btn btn-shadow btn-light-primary d-flex align-items-center justify-content-between mx-1"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false"
                    aria-controls="collapseExample">
                    <div>
                        <i class="ph-duotone ph-funnel icon-search me-2"></i>
                        <span>Data Filter</span>
                    </div>
                    <i data-feather="chevron-down" class="icon-search ms-3"></i>
                </button>
            </div>
        </div>
        <div class="col">
            <div class="d-flex flex-column flex-lg-row gap-2 justify-content-end">
                <div class="">
                </div>
                <div class="">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                        <input type="search" id="search" class="form-control" placeholder="Search here...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-0 mb-3">
        <div class="collapse" id="collapseExample">
            <div class="row">
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-calendar icon-search"></i>
                        <input type="text" class="form-control w-100 datepicker_range" id="daterange" name="daterange"
                            placeholder="Select date range" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $thead = ['PTW Number', 'Date', 'Project', 'PRF', 'PO', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        New Procurement to Warehouse
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-2">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="mb-2 row">
                                    <label class="col-lg-2 col-form-label mb-0">PTW Number: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control fs-5 fw-bold" placeholder="ASTA/XXX/XXX"
                                            name="ptw_no" value="" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Date: <span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <input type="date" class="form-control" placeholder="Arrival Date"
                                            name="ptw_date" value="" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Project: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="project_id" required>
                                            @foreach ($project as $pj)
                                                <option value="{{ $pj->id }}">{{ $pj->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 mb-2">
                                <h4 class="fw-bold mb-1">Purchase Order</h4>
                                <div id="po-container" class="mt-3">
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-po"
                                        class="btn btn-light-primary w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i>
                                        <span>Add Purchase Order</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-12 col-lg-12">
                                <div class="mb-1 row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label">Notes: <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" name="notes" required rows="6"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <p class="mb-0 text-muted"><b>Important</b>: <span class="text-danger">*</span> fields are
                                    required.</p>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btn-submit" form="form-tambah">Submit
                        Data</button>
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
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript">
        function initItemMasterChoices(element) {
            new Choices(element, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search Purchase Order...',
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
                placeholder: true,
                placeholderValue: 'Select Purchase Order'
            });
        }

        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ptw.index') }}",
                data: function(d) {
                    d.range = $('#daterange').val();
                }
            },
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
                    data: 'ptw_number',
                    name: 'ptw_number',
                    class: 'py-lg-1 py-sm-2',
                },
                {
                    data: 'ptw_date',
                    name: 'ptw_date',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'project',
                    name: 'project',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'prf_number',
                    name: 'prf_number',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'po_number',
                    name: 'po_number',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    class: 'text-center py-lg-1 py-sm-2',
                    orderable: false,
                    searchable: false,
                },
            ],
            createdRow: function(row, data, dataIndex) {
                var api = this.api();
                $('td', row).each(function(colIndex) {
                    // Mengambil title langsung dari konfigurasi kolom DataTables
                    var title = api.column(colIndex).header().textContent.trim();
                    $(this).attr('data-label', title);
                });
            }
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });

        $('#daterange').on('change', function() {
            table.ajax.reload();
        });

        flatpickr(document.querySelector('.datepicker_range'), {
            mode: 'range',
            dateFormat: 'Y-m-d',
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
            let button = $('#btn-submit');
            if (button.prop('disabled')) {
                return false;
            }
            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            e.preventDefault();
            $.ajax({
                url: '{{ route('ptw.simpan') }}', // Route untuk simpan data
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
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-submit').html('Submit Data');
                    } else {
                        hideLoader();
                        showToastError(response.message);
                        $('#btn-submit').prop('disabled', false);
                        $('#btn-submit').html('Submit Data');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoader();
                    showToastError("Error: " + xhr.responseText);
                    $('#btn-submit').prop('disabled', false);
                    $('#btn-submit').html('Submit Data');
                }
            });
        });

        let poMasterIndex = 0;
        $('#btn-add-po').on('click', function(e) {
            e.preventDefault();
            let html = `
                <div class="row p-0 mx-0 mb-2 po-item">
                    <div class="col-10 col-lg-11 ps-0">
                        <select data-index="${poMasterIndex}" class="form-control po-master" name="po[${poMasterIndex}][id_po]" id="po-master-${poMasterIndex}" required>
                            <option value="" selected disabled>Select Purchase Order</option>
                            @foreach ($data_po as $po)
                            <option value="{{ $po->id }}">{{ $po->po_no }}</option>
                            @endforeach
                        </select>
                        <div class="detail-container mt-2" id="detail-container-${poMasterIndex}"></div>
                    </div>
                    <div class="col-2 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${poMasterIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-po" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

            $('#po-container').append(html);
            let selectElement = document.getElementById(`po-master-${poMasterIndex}`);
            initItemMasterChoices(selectElement);
            poMasterIndex++;
        });

        $(document).on('change', '.po-master', function() {
            let itemId = $(this).val();
            let index = $(this).data('index');
            $.ajax({
                url: "{{ route('getPoAjax', ':id') }}".replace(':id', itemId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    var po = res.data_po;

                    const itemNames = po.child
                        .map(child => child.varian?.name_varian)
                        .filter(Boolean)
                        .join(', ');

                    const prfBadges = (po.prf_number || '')
                        .split(',')
                        .map(prf => prf.trim())
                        .filter(prf => prf !== '')
                        .map(prf => `<span class="badge bg-primary me-1">${prf}</span>`)
                        .join('');

                    html += `
                        <div class="card">
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <h5>Purchase Order</h5>
                                        ${prfBadges}
                                        <hr class="mb-0 mt-1">
                                    </div>
                                    <div class="col-6">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr>
                                                    <td class="text-muted py-1 border-top-0">PO Number :</td>
                                                    <td class="py-1 border-top-0 text-danger">${po.po_no}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Vendor :</td>
                                                    <td class="py-1">${po.vendor.nama}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Entity :</td>
                                                    <td class="py-1">${po.entitas.entitas_name}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Created by :</td>
                                                    <td class="py-1">${po.created_by.firstname}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr>
                                                    <td class="text-muted py-1 border-top-0">PO Date :</td>
                                                    <td class="py-1 border-top-0">${po.po_date}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Status :</td>
                                                    <td class="py-1">${po.po_status}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Created :</td>
                                                    <td class="py-1">${po.created_at}/A</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted py-1">Items :</td>
                                                    <td class="py-1">${itemNames}/A</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    $('#detail-container-' + index).html(html);
                }
            });
        });

        $(document).on('click', '.btn-delete-po', function() {
            $(this).closest('.po-item').remove();
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').each(function() {
                new Choices(this, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search here...',
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    placeholder: true,
                });
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('ptw.hapus', ':id:') }}";
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
                        if (res.success) {
                            table.ajax.reload(null, false);
                            hideLoader();
                            showToastSuccess("Data has been deleted");
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
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

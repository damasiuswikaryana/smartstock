@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="History" module="Purchase Order">
        <li class="breadcrumb-item">Procurement</li>
        <li class="breadcrumb-item">Purchase Order</li>
    </x-page-header>

    <div class="row g-2 align-items-center mb-4 mt-3 justify-content-between p-sm-0">
        <div class="col-12 col-lg-auto">
            <div class="d-flex justify-content-start align-items-center">
                <button type="button"
                    class="btn btn-shadow btn-light-primary d-flex align-items-center justify-content-center mx-1"
                    data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                    <i class="ph-duotone ph-plus-circle me-2"></i>
                    Add Purchase Order
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
                        <i class="ph-duotone ph-house icon-search"></i>
                        <select class="form-control w-100" id="fl_status">
                            <option value="">All Status</option>
                            <option value="pending">Only Pending</option>
                            <option value="checked">Only Checked</option>
                            <option value="recorded">Only Recorded</option>
                            <option value="approved">Approved</option>
                            <option value="myapproval">Need My Approval</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-calendar icon-search"></i>
                        <input type="text" class="form-control w-100 datepicker_range" id="daterange" name="daterange"
                            placeholder="Select date range" />
                    </div>
                </div>
                <div class="col-12 col-lg-3 text-start mb-2 mb-lg-0">
                    <div class="form-search w-100">
                        <i class="ph-duotone ph-check-circle icon-search"></i>
                        <select class="form-control w-100" id="fl_director">
                            <option value="">All Approval</option>
                            <option value="yes">Need Director Approval</option>
                            <option value="no">Only Finance</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $thead = ['PO Number', 'Date', 'Entity', 'Vendor', 'Items', 'Status', 'DP', 'Options'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        Add Purchase Order
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
                                    <label class="col-lg-2 col-form-label mb-0">PO Number: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control fs-5 fw-bold" placeholder="ASTA/XXX/XXX"
                                            name="po_no" value="" required>
                                    </div>
                                </div>
                                <div class="mb-2 row">
                                    <label class="col-lg-2 col-form-label mb-0">PRF Number: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input class="form-control custom class" id="choices-text-unique-values"
                                            type="text" name="prf_no" value="" placeholder="Input PRF Number"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Date: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <input type="date" class="form-control" placeholder="Date stock in"
                                            name="po_date" value="" required>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Director Approval: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="dir_approval" required>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">

                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Vendor: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="vendor_id" required>
                                            @foreach ($vendor as $vd)
                                                <option value="{{ $vd->id }}">{{ $vd->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Entity: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="entitas_id" required>
                                            @foreach ($entitas as $et)
                                                <option value="{{ $et->id }}">{{ $et->entitas_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 mb-4">
                                <h4 class="fw-bold mb-1">Items</h4>
                                <div id="produk-container">
                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-product"
                                        class="btn btn-light-primary w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i>
                                        <span>Add Item</span>
                                    </a>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <div class="col-lg-12">
                                        <label class="col-form-label">Notes: <span class="text-danger">*</span></label>
                                        <textarea type="text" class="form-control" name="notes" required rows="6"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">PPH: <span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="PPH" name="tax"
                                                value="0" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">PPN: <span class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="PPN" name="ppn"
                                                value="0" required>
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Discount: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-lg-3 pe-1">
                                                <select class="form-control" name="disc_tipe" required>
                                                    <option value="rupiah">Rp.</option>
                                                    <option value="percentage">%</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-9 ps-1">
                                                <input type="number" class="form-control number-separator"
                                                    placeholder="Discount value" name="disc" value="0" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1 row">
                                    <label class="col-lg-4 col-form-label">Down Payment: <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp.</span>
                                            <input type="number" class="form-control number-separator"
                                                placeholder="Down payment in rupiah" name="dp" value="0"
                                                required>
                                        </div>

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
        function formatRupiah(angka) {
            return Number(angka || 0).toLocaleString('id-ID');
        }

        function initItemMasterChoices(element) {
            new Choices(element, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search item...',
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
                placeholder: true,
                placeholderValue: 'Select Item'
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
                url: "{{ route('po.index') }}",
                data: function(d) {
                    d.status = $('#fl_status').val();
                    d.range = $('#daterange').val();
                    d.director = $('#fl_director').val();
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
                    data: 'po_number',
                    name: 'po_number',
                    class: 'py-lg-1 py-sm-2',
                },
                {
                    data: 'po_date',
                    name: 'po_date',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'entitas',
                    name: 'entitas',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'vendor',
                    name: 'vendor',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'items',
                    name: 'items',
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'po_status',
                    name: 'po_status',
                    visible: true,
                    class: 'py-lg-1 py-sm-2 text-center',
                },
                {
                    data: 'po_dp',
                    name: 'po_dp',
                    visible: true,
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

        $('#fl_status, #daterange, #fl_director').on('change', function() {
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
                url: '{{ route('po.simpan') }}', // Route untuk simpan data
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

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('po.hapus', ':id:') }}";
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

        $(document).on('click', '.btn-delete-produk', function() {
            $(this).closest('.produk-item').remove();
        });

        let itemMasterIndex = 0;
        $('#btn-add-product').on('click', function(e) {
            e.preventDefault();
            let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-10 col-lg-11 ps-0">
                        <select data-index="${itemMasterIndex}" class="form-control item-master" name="item[${itemMasterIndex}][id_item]" id="item-master-${itemMasterIndex}" required>
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($categories as $category)
                                <optgroup label="{{ $category->title }}">
                                    @foreach ($category->items as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="variant-container mt-2" id="variant-container-${itemMasterIndex}"></div>
                    </div>
                    <div class="col-2 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${itemMasterIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

            $('#produk-container').append(html);
            let selectElement = document.getElementById(`item-master-${itemMasterIndex}`);
            initItemMasterChoices(selectElement);
            itemMasterIndex++;
        });

        $(document).on('change', '.item-master', function() {
            let itemId = $(this).val();
            let index = $(this).data('index');
            $.ajax({
                url: "{{ route('getVariants', ':id') }}".replace(':id', itemId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    $.each(res.variants, function(i, variant) {
                        let satuanOptions = '';
                        $.each(res.satuans, function(j, satuan) {
                            satuanOptions += `
                            <option value="${satuan.id}">
                                ${satuan.satuan}
                            </option>`;
                        });

                        html += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-12 col-lg-4">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-4 col-lg-2">
                            <select class="form-select"
                                name="item[${index}][variants][${variant.id}][satuan]">
                                ${satuanOptions}
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <input type="text" name="item[${index}][variants][${variant.id}][nilai_variant]" class="form-control number-separator" value="${formatRupiah(variant.nilai)}" placeholder="Harga item" />
                        </div>
                        <div class="col-4 col-lg-1">
                            <input type="number" min="0" class="form-control" name="item[${index}][variants][${variant.id}][qty]" placeholder="Qty" value="0">
                            <input type="hidden" name="item[${index}][variants][${variant.id}][id_variant]" value="${variant.id}">
                        </div>
                        <div class="col-4 col-lg-2">
                            <select class="form-select" name="item[${index}][variants][${variant.id}][pph_variant]">
                                <option value="0">Non-PPh</option>
                                <option value="1">PPh</option>
                            </select>
                        </div>
                    </div>`;
                    });
                    $('#variant-container-' + index).html(html);
                }
            });
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

            var text_Unique_Val = new Choices('#choices-text-unique-values', {
                delimiter: ',',
                paste: false,
                duplicateItemsAllowed: false,
                editItems: true,
                removeItemButton: true
            });
        });
    </script>
@endpush

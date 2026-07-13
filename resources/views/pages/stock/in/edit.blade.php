<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Stock In</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="px-2">
        <form class="row" action="#" method="post" id="form-edit" name="form-edit">
            @csrf
            @method('POST')
            <div class="col-6">
                <h4 class="fw-bold mb-3">Stock Info</h4>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Stock In Number:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Number" name="stock_in_number"
                            value="{{ $data->stock_in_number }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Date:</label>
                    <div class="col-lg-8">
                        <input type="date" class="form-control" placeholder="Date stock in" name="in_date"
                            value="{{ $data->in_date }}">
                    </div>
                </div>
            </div>

            <div class="col-6">
                <h4 class="fw-bold mb-3">Vendor</h4>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Vendor:</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="vendor_id">
                            @foreach ($vendor as $vd)
                                <option @if ($data->vendor_id == $vd->id) selected @endif value="{{ $vd->id }}">
                                    {{ $vd->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Entity:</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="entitas_id">
                            @foreach ($entitas as $et)
                                <option @if ($data->entitas_id == $et->id) selected @endif value="{{ $et->id }}">
                                    {{ $et->entitas_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">PO Number:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Input PO Number" name="po_number"
                            value="{{ $data->po_number }}">
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <h4 class="fw-bold mb-3">Items</h4>
                <div id="produk-container-edit">
                    @foreach ($itemMasters as $itemMaster)
                        <div class="row p-0 mx-0 mb-2 produk-item">
                            <div class="col-11 col-lg-11 ps-0">
                                <input type="text" class="form-control" value="{{ $itemMaster->nama }}" disabled>
                                <div class="variant-container mt-2">
                                    @foreach ($itemMaster->varian as $variant)
                                        @php
                                            $detail = $qtyData->get($variant->id);
                                            $qty = optional($detail)->qty ?? 0;
                                        @endphp
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-1 text-center">
                                                <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control"
                                                    value="{{ $variant->name_varian }}" disabled>
                                            </div>
                                            <div class="col-3">
                                                <input type="hidden" name="items[{{ $variant->id }}][item_varian_id]"
                                                    value="{{ $variant->id }}">

                                                <input type="number" min="0" class="form-control"
                                                    name="items[{{ $variant->id }}][qty]"
                                                    value="{{ $qty }}">
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="col-1 col-lg-1 mx-0 pe-0">
                                <button type="button" class="btn btn-rounded btn-light-danger btn-delete-produk"
                                    style="font-size:20px;">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mb-0 p-2">
                    <a href="#" id="btn-add-product-edit"
                        class="btn btn-light-primary w-100 d-flex justify-content-center align-items-center">
                        <i class="fa fa-plus-circle me-2"></i>
                        <span>Add Item</span>
                    </a>
                </div>
            </div>

            <div class="col-12">
                <h4 class="fw-bold mb-3">Notes and Documentation</h4>
                <div class="mb-1 row">
                    <div class="col-lg-12">
                        <label class="col-form-label">Notes:</label>
                        <textarea type="text" class="form-control" name="notes">{{ $data->note }}</textarea>
                    </div>
                </div>
            </div>
        </form>

        <div class="col-12 mt-1">
            <div class="mb-0 row">
                <div class="col-lg-12">
                    <label class="col-form-label pb-0">Documentation Upload:</label>
                    <p class="text-danger">Upload dokumentasi pendukung seperti berita acara serah terima, foto
                        penyerahan barang, dsb.</p>
                    <form id="dropzoneUpload" class="dropzone">
                        @csrf
                        <div class="fallback">
                            <input name="file" type="file" multiple />
                        </div>
                    </form>
                </div>
            </div>

            <div class="mb-3">
                <div class="grid row g-3 mt-0">
                    @foreach ($document as $doc)
                        <div class="col-xl-2 col-md-4 col-sm-6" id="wpdoc-{{ $doc->id }}">
                            <a class="card-gallery" data-fslightbox="gallery"
                                href="{{ asset('storage/stock_in/' . $doc->filename) }}">
                                <img class="img-fluid" src="{{ asset('storage/stock_in/' . $doc->filename) }}"
                                    alt="Documentation" />
                                <div class="gallery-hover-data card-body justify-content-end">
                                    <div>
                                        <p class="text-white mb-0 text-truncate w-100">{{ $doc->filename }}</p>
                                    </div>
                                </div>
                            </a>
                            <button id="doc-{{ $doc->id }}" type="button" data-id="{{ $doc->id }}"
                                class="mt-1 d-flex align-items-center justify-content-center btn btn-rounded btn-light-danger btn-delete-photo w-100">
                                <i class="ti ti-trash me-1"></i>
                                <span class="fa-12">Delete</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
<script>
    var stockInId = {{ $data->id }};
    new Dropzone(".dropzone", {
        url: "{{ route('stockin.upload', ':id') }}".replace(':id', stockInId),
        paramName: "file",
        maxFiles: 10,
        maxFilesize: 5, // MB
        acceptedFiles: ".jpg,.jpeg,.png,.pdf",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            console.log(response);
        },
        error: function(file, response) {
            console.log(response);
        }
    });

    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('stockin.update', ':id:') }}";
        var url = url.replace(':id:', id);

        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function(res) {
                $('#modalEdit').modal('hide');
                table.ajax.reload(null, false);
                if (res.success) {
                    hideLoader();
                    showToastSuccess("Data has been updated");
                } else {
                    hideLoader();
                    showToastError(res.message);
                }
            },
            error: function() {
                hideLoader();
                showToastError("Error while updating data");
            }
        });
    });

    $(document).on('click', '.btn-delete-produk', function() {
        $(this).closest('.produk-item').remove();
    });

    $(document).on('click', '.btn-delete-photo', function() {
        let id = $(this).data('id');
        var url = "{{ route('stockin.hapusPhoto', ':id:') }}";
        var url = url.replace(':id:', id);

        if (confirm('Delete this photo?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: showLoader(),
                success: function(res) {
                    $("#wpdoc-" + id).remove();
                    hideLoader();
                    showToastSuccess("Document has been deleted");
                },
                error: function() {
                    hideLoader();
                    showToastError("Error while deleting document");
                }
            });
        }
    });

    var itemMasterIndexEdit = 0;
    $('#btn-add-product-edit').on('click', function(e) {
        e.preventDefault();
        let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-11 col-lg-11 ps-0">
                        <select data-index="${itemMasterIndexEdit}" class="form-control item-master" name="item[${itemMasterIndexEdit}][id_item]" required>
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <div class="variant-container mt-2" id="variant-container-edit-${itemMasterIndexEdit}"></div>
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${itemMasterIndexEdit}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

        $('#produk-container-edit').append(html);
        itemMasterIndexEdit++;
    });

    $(document).on('change', '.item-master', function() {
        let itemEditId = $(this).val();
        let indexEdit = $(this).data('index');
        $.ajax({
            url: "{{ route('getVariants', ':id') }}".replace(':id', itemEditId),
            type: "GET",
            success: function(res) {
                let htmlEdit = '';
                $.each(res.variants, function(i, variant) {
                    htmlEdit += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-3">
                            <input type="number" min="0" class="form-control" name="item[${indexEdit}][variants][${variant.id}][qty]" placeholder="Qty">
                            <input type="hidden" name="item[${indexEdit}][variants][${variant.id}][id_variant]" value="${variant.id}">
                        </div>
                    </div>`;
                });
                $('#variant-container-edit-' + indexEdit).html(htmlEdit);
            }
        });
    });
</script>

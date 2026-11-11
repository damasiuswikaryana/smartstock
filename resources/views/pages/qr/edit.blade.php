<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Quotation Request</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="px-2">
        <form class="row" action="#" method="post" id="form-edit" name="form-edit">
            @csrf
            @method('POST')
            <div class="col-12 col-lg-12">
                <div class="mb-2 row">
                    <label class="col-lg-2 col-form-label mb-0">QR Number: <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control fs-5 fw-bold" placeholder="ASTA/XXX/XXX"
                            name="qr_no" value="{{ $data->qr_no }}" required>
                    </div>
                </div>
                <div class="mb-2 row">
                    <label class="col-lg-2 col-form-label mb-0">PRF Number: <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input class="form-control custom class" id="choices-text-unique-values-edit" type="text"
                            name="prf_no" value="{{ $data->prf_number }}" placeholder="Input PRF Number" required />
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Date: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <input type="date" class="form-control" placeholder="Date stock in" name="qr_date"
                            value="{{ $data->qr_date }}" required>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Director Approval: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control select2" name="dir_approval" required>
                            <option @if ($data->director_id != null) selected @endif value="yes">Yes</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Vendor: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control select2" name="vendor_id" required>
                            @foreach ($vendor as $vd)
                                <option @if ($data->vendor_id == $vd->id) selected @endif value="{{ $vd->id }}">
                                    {{ $vd->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Entity: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control select2" name="entitas_id" required>
                            @foreach ($entitas as $et)
                                <option @if ($data->entitas_id == $et->id) selected @endif value="{{ $et->id }}">
                                    {{ $et->entitas_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-3">
                <h4 class="fw-bold mb-3">Items</h4>
                <div id="produk-container-edit">
                    @foreach ($itemMasters as $itemMaster)
                        <div class="row p-0 mx-0 mb-2 produk-item-edit" data-item-master="{{ $itemMaster->id }}">
                            <div class="col-11 col-lg-11 ps-0">
                                <input type="text" class="form-control" value="{{ $itemMaster->nama }}" disabled>
                                <div class="variant-container mt-2">
                                    @foreach ($itemMaster->varian as $variant)
                                        @php
                                            $detail = $qtyData->get($variant->id);
                                            $qty = optional($detail)->qty ?? 0;
                                            $satuan_id = optional($detail)->satuan_id ?? 0;
                                            $nilai = optional($detail)->unit_price ?? 0;
                                        @endphp
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-1 text-center">
                                                <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                                            </div>
                                            <div class="col-12 col-lg-4">
                                                <input type="text" class="form-control"
                                                    value="{{ $variant->name_varian }}" disabled>
                                            </div>
                                            <div class="col-4 col-lg-2">
                                                <select class="form-select" name="items[{{ $variant->id }}][satuan]">
                                                    @foreach ($satuan as $sat)
                                                        <option @if ($satuan_id == $sat->id) selected @endif
                                                            value="{{ $sat->id }}">{{ $sat->satuan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4 col-lg-2">
                                                <input type="text" class="form-control number-separator"
                                                    name="items[{{ $variant->id }}][nilai_variant]"
                                                    value="{{ pecahTanpaRp($nilai) }}">
                                            </div>
                                            <div class="col-4 col-lg-1">
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
                                <button type="button" class="btn btn-rounded btn-light-danger btn-delete-produk-edit"
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

            <div class="col-12 col-lg-12">
                <div class="mb-1 row">
                    <div class="col-lg-12">
                        <label class="col-form-label">Notes: <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="notes" required rows="6">{{ $data->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <p class="mb-0 text-muted"><b>Important</b>: <span class="text-danger">*</span> fields are
                    required.</p>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" id="btn-edit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

<script>
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('qr.update', ':id:') }}";
        var url = url.replace(':id:', id);

        let button = $('#btn-edit');
        if (button.prop('disabled')) {
            return false;
        }
        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

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
                    $('#btn-edit').prop('disabled', false);
                    $('#btn-edit').html('Submit Data');
                } else {
                    hideLoader();
                    showToastError(res.message);
                    $('#btn-edit').prop('disabled', false);
                    $('#btn-edit').html('Submit Data');
                }
            },
            error: function(xhr, status, error) {
                hideLoader();
                showToastError("Error: " + xhr.responseText);
                $('#btn-edit').prop('disabled', false);
                $('#btn-edit').html('Submit Data');
            }
        });
    });

    // handler di-namespace & di-off dulu agar tidak dobel setiap modal edit dibuka
    $(document).off('click.qrEdit').on('click.qrEdit', '.btn-delete-produk-edit', function() {
        $(this).closest('.produk-item-edit').remove();
    });

    var itemMasterIndexEdit = 0;
    $('#btn-add-product-edit').on('click', function(e) {
        e.preventDefault();
        let idx = itemMasterIndexEdit++;
        let html = `
                <div class="row p-0 mx-0 mb-2 produk-item-edit" data-item-master="">
                    <div class="col-11 col-lg-11 ps-0">
                        <select data-index="${idx}" class="form-control item-master-edit" id="item-master-edit-${idx}">
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($categories as $category)
                                <optgroup label="{{ $category->title }}">
                                    @foreach ($category->items as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="variant-container mt-2" id="variant-container-edit-${idx}"></div>
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button type="button" class="btn btn-rounded btn-light-danger btn-delete-produk-edit" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

        $('#produk-container-edit').append(html);
        initItemMasterChoices(document.getElementById(`item-master-edit-${idx}`));
    });

    $(document).off('change.qrEdit').on('change.qrEdit', '.item-master-edit', function() {
        let itemEditId = $(this).val();
        let indexEdit = $(this).data('index');
        let row = $(this).closest('.produk-item-edit');

        // cegah item yang sama dipilih dua kali (variant akan saling menimpa)
        let duplicate = $('#produk-container-edit .produk-item-edit').not(row).filter(function() {
            return String($(this).data('item-master')) === String(itemEditId);
        }).length > 0;
        if (duplicate) {
            showToastError('Item ini sudah ada di daftar. Silakan ubah qty pada item yang sudah ada.');
            row.remove();
            return;
        }
        row.data('item-master', itemEditId);

        $.ajax({
            url: "{{ route('getVariants', ':id') }}".replace(':id', itemEditId),
            type: "GET",
            success: function(res) {
                let htmlEdit = '';
                $.each(res.variants, function(i, variant) {
                    let satuanOptions = '';
                    $.each(res.satuans, function(j, satuan) {
                        satuanOptions += `<option value="${satuan.id}">${satuan.satuan}</option>`;
                    });

                    htmlEdit += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-12 col-lg-4">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-4 col-lg-2">
                            <select class="form-select" name="items[${variant.id}][satuan]">
                                ${satuanOptions}
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <input type="text" class="form-control number-separator" name="items[${variant.id}][nilai_variant]" value="${formatRupiah(variant.nilai)}" placeholder="Harga item">
                        </div>
                        <div class="col-4 col-lg-1">
                            <input type="hidden" name="items[${variant.id}][item_varian_id]" value="${variant.id}">
                            <input type="number" min="0" class="form-control" name="items[${variant.id}][qty]" placeholder="Qty" value="0">
                        </div>
                    </div>`;
                });
                $('#variant-container-edit-' + indexEdit).html(htmlEdit);
            }
        });
    });

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

    new Choices('#choices-text-unique-values-edit', {
        delimiter: ',',
        paste: false,
        duplicateItemsAllowed: false,
        editItems: true,
        removeItemButton: true
    });
</script>

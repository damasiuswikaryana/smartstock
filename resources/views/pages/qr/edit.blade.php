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
                    <label class="col-lg-12 col-form-label mb-0">QR Number: <span class="text-danger">*</span></label>
                    <div class="col-lg-12">
                        <input type="text" class="form-control fs-5 fw-bold" placeholder="ASTA/XXX/XXX"
                            name="qr_no" value="{{ $data->qr_no }}" required>
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
                        <select class="form-control" name="dir_approval" required>
                            <option @if ($data->director_id != null) selected @endif value="yes">Yes</option>
                            <option @if ($data->director_id == null) selected @endif value="no">No</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Vendor: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control" name="vendor_id" required>
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
                        <select class="form-control" name="entitas_id" required>
                            @foreach ($entitas as $et)
                                <option @if ($data->entitas_id == $et->id) selected @endif value="{{ $et->id }}">
                                    {{ $et->entitas_name }}</option>
                            @endforeach
                        </select>
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
                                            $satuan_id = optional($detail)->satuan_id ?? 0;
                                            $nilai = optional($detail)->unit_price ?? 0;
                                        @endphp
                                        <div class="row mb-2 align-items-center">
                                            <div class="col-1 text-center">
                                                <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                                            </div>
                                            <div class="col-12 col-lg-5">
                                                <input type="text" class="form-control"
                                                    value="{{ $variant->name_varian }}" disabled>
                                            </div>
                                            <div class="col-4 col-lg-2">
                                                <input type="text" class="form-control"
                                                    value="{{ $variant->sku_varian }}" disabled>
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
                                                <input type="hidden" name="items[{{ $variant->id }}][item_varian_id]"
                                                    value="{{ $variant->id }}">
                                                <input type="number" min="0" class="form-control"
                                                    name="items[{{ $variant->id }}][qty]"
                                                    value="{{ $qty }}">
                                                <input type="hidden" name="items[{{ $variant->id }}][nilai_variant]"
                                                    value="{{ $nilai }}">
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

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <div class="col-lg-12">
                        <label class="col-form-label">Notes: <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="notes" required rows="6">{{ $data->notes }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">PPH: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="PPH" name="tax"
                                value="{{ $data->tax }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">PPN: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <input type="number" class="form-control" placeholder="PPN" name="ppn"
                                value="{{ $data->ppn }}" required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Discount: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-3 pe-1">
                                <select class="form-control" name="disc_tipe" required>
                                    <option @if ($data->disc != null) selected @endif value="rupiah">Rp.
                                    </option>
                                    <option @if ($data->disc_perc != null) selected @endif value="percentage">%
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-9 ps-1">
                                <input type="number" class="form-control number-separator"
                                    placeholder="Discount value" name="disc"
                                    value="{{ pecahTanpaRp($data->disc) }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Down Payment: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="number" class="form-control number-separator"
                                placeholder="Down payment in rupiah" name="dp"
                                value="{{ pecahTanpaRp($data->dp) }}" required>
                        </div>
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
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script>
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('qr.update', ':id:') }}";
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
                    let satuanOptions = '<option value="">Pilih satuan</option>';

                    $.each(res.satuans, function(j, satuan) {
                        satuanOptions += `
                            <option value="${satuan.id}">
                                ${satuan.satuan}
                            </option>`;
                    });

                    htmlEdit += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-12 col-lg-5">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-4 col-lg-2">
                            <input type="text" class="form-control" value="${variant.sku_varian}" disabled>
                        </div>
                        <div class="col-4 col-lg-2">
                            <select class="form-select"
                                name="item[${indexEdit}][variants][${variant.id}][satuan]">
                                ${satuanOptions}
                            </select>
                        </div>
                        <div class="col-4 col-lg-2">
                            <input type="number" min="0" class="form-control" name="item[${indexEdit}][variants][${variant.id}][qty]" placeholder="Qty">
                            <input type="hidden" name="item[${indexEdit}][variants][${variant.id}][id_variant]" value="${variant.id}">
                            <input type="hidden" name="item[${indexEdit}][variants][${variant.id}][nilai_variant]" value="${variant.nilai}">
                        </div>
                    </div>`;
                });
                $('#variant-container-edit-' + indexEdit).html(htmlEdit);
            }
        });
    });
</script>

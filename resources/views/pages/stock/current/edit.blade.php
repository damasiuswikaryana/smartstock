<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Stock Current</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="px-2">
        <form class="row" action="#" method="post" id="form-edit" name="form-edit">
            @csrf
            @method('POST')
            <div class="col-12">
                <div class="mb-2 row">
                    <label class="col-lg-4 col-form-label">SKU</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control fw-bold" value="{{ $data->item_varian->sku_varian }}"
                            disabled>
                    </div>
                </div>
                <div class="mb-2 row">
                    <label class="col-lg-4 col-form-label">Item</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control fw-bold" value="{{ $data->item_varian->name_varian }}"
                            disabled>
                    </div>
                </div>
                <div class="mb-2 row">
                    <label class="col-lg-4 col-form-label">Werehouse: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control" name="werehouse_id" required>
                            @foreach ($gudang as $wh)
                                <option @if ($data->lokasi_id == $wh->id) selected @endif value="{{ $wh->id }}">
                                    {{ $wh->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-2 row">
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
                <div class="mb-2 row">
                    <label class="col-lg-4 col-form-label">Stock Qty:</label>
                    <div class="col-lg-8">
                        <input type="number" class="form-control" placeholder="Stock current" name="jumlah"
                            value="{{ $data->jumlah }}">
                    </div>
                </div>
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
        var url = "{{ route('stockCurrent.update', ':id:') }}";
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
</script>

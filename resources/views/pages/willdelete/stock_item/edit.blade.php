
<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Data Item</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Kode Item:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Kode Item" name="kode" value="{{ $data->kode }}" maxlength="3" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Nama Item:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Nama Item" name="nama" value="{{ $data->nama }}" />
            </div>
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script>
    $('#form-edit').on('submit', function (e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('stock-item.update', ':id:') }}";
        var url = url.replace(':id:', id);
        
        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function (res) {
                if (res.success) {
                    $('#modalEdit').modal('hide');
                    table.ajax.reload(null, false);
                    hideLoader();
                    showToastSuccess("Berhasil memperbarui data");
                } else {
                    hideLoader();
                    showToastError(res.message);
                }
            },
            error: function () {
                hideLoader();
                showToastError("Terjadi kesalahan saat memperbarui data");
            }
        });
    });
</script>
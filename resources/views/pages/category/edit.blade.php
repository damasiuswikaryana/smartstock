<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Category</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Category:</label>
            <div class="col-lg-8 row">
                <input type="text" class="form-control" placeholder="Category Name" name="title"
                    value="{{ $data->title }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Descrption:</label>
            <div class="col-lg-8 row">
                <textarea class="form-control" name="description">{{ $data->description }}</textarea>
            </div>
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
<script>
    $('#uppyModalOpener').on('click', function(e) {
        e.preventDefault();
        $('#modalEdit').modal('hide');
    });

    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('category.update', ':id:') }}";
        var url = url.replace(':id:', id);

        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function(res) {
                $('#modalEdit').modal('hide');
                if (res.success) {
                    table.ajax.reload(null, false);
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

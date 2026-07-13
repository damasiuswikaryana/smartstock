<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Bank Account</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('PUT')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Bank:</label>
            <div class="col-lg-8">
                <select class="form-control" name="id_bank">
                    @foreach ($banks as $b)
                        <option {{ $b->id == $data->id_bank ? 'selected' : '' }} value="{{ $b->id }}">
                            {{ $b->bank_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Account Name:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Account Name" name="bank_account_name"
                    value="{{ $data->bank_account_name }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Account Number:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Account Number" name="bank_account_number"
                    value="{{ $data->bank_account_number }}" />
            </div>
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script>
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('bank-account.update', ':id:') }}";
        var url = url.replace(':id:', id);

        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function(res) {
                if (res.success) {
                    $('#modalEdit').modal('hide');
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

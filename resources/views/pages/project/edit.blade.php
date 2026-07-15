<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Bank Account</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('PUT')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Project Name:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Project Name" name="name"
                    value="{{ $data->name }}" />
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
            <label class="col-lg-4 col-form-label">Contract Number:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Contract Number" name="no_kontrak"
                    value="{{ $data->no_kontrak }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Date Join:</label>
            <div class="col-lg-8">
                <input type="date" class="form-control" placeholder="Date join" name="date_join"
                    value="{{ $data->date_join }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Terms:</label>
            <div class="col-lg-8">
                <input type="number" class="form-control" placeholder="Contract Terms (month)" name="jangka_waktu"
                    value="{{ $data->jangka_waktu }}" />
                <p class="text-danger f-12">Contract period in months</p>
            </div>
        </div>
        <div class="mb-0 row">
            <label class="col-lg-4 col-form-label">Status:</label>
            <div class="col-lg-8">
                <select class="form-control" name="status">
                    <option @if ($data->status == 'Active') selected @endif value="Active">Active</option>
                    <option @if ($data->status == 'Inactive') selected @endif value="Inactive">Inactive</option>
                </select>
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
        var url = "{{ route('project.update', ':id:') }}";
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

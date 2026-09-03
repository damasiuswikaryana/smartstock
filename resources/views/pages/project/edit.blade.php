<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Client Project</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('PUT')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Project Name: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Project Name" name="name"
                    value="{{ $data->name }}" required />
            </div>
        </div>
        <div class="mb-3 row">
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
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Werehouse: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <select class="form-control" name="werehouse_id" required>
                    @foreach ($gudang as $gd)
                        <option @if ($data->werehouse_id == $gd->id) selected @endif value="{{ $gd->id }}">
                            {{ $gd->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Number: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Contract Number" name="no_kontrak"
                    value="{{ $data->no_kontrak }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Number: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Other Contract Number" name="no_kontrak_2"
                    value="{{ $data->no_kontrak_2 }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Number: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Other Contract Number" name="no_kontrak_3"
                    value="{{ $data->no_kontrak_3 }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Date Join: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="date" class="form-control" placeholder="Date join" name="date_join"
                    value="{{ $data->date_join }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Contract Terms: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="number" class="form-control" placeholder="Contract Terms (month)" name="jangka_waktu"
                    value="{{ $data->jangka_waktu }}" required />
                <p class="text-danger f-12">Contract period in months</p>
            </div>
        </div>
        <div class="mb-0 row">
            <label class="col-lg-4 col-form-label">Status: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <select class="form-control" name="status" required>
                    <option @if ($data->status == 'Active') selected @endif value="Active">Active</option>
                    <option @if ($data->status == 'Inactive') selected @endif value="Inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="mb-0">
            <p class="mb-0 text-muted"><b>Important</b>: <span class="text-danger">*</span> fields
                are
                required.</p>
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

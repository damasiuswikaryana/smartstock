<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Purchase to Warehouse</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="px-2">
        <form class="row" action="#" method="post" id="form-edit-master" name="form-edit-master">
            @csrf
            @method('POST')
            <div class="col-12 col-lg-12">
                <div class="mb-2 row">
                    <label class="col-lg-2 col-form-label mb-0">PTW Number: <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control fs-5 fw-bold" placeholder="ASTA/XXX/XXX"
                            name="ptw_number" value="{{ $data->ptw_number }}" required>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Date: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <input type="date" class="form-control" placeholder="Date arrival" name="ptw_date"
                            value="{{ $data->ptw_date }}" required>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Project: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control select2" name="project_id" required>
                            @foreach ($project as $pj)
                                <option @if ($data->project_id == $pj->id) selected @endif value="{{ $pj->id }}">
                                    {{ $pj->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-1 row">
                    <label class="col-lg-4 col-form-label">Status: <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                        <select class="form-control select2" name="status" required>
                            <option @if ($data->ptw_status == 'Pending') selected @endif value="Pending">Pending</option>
                            <option @if ($data->ptw_status == 'Gudang') selected @endif value="Gudang">Received by
                                Warehouse</option>
                            <option @if ($data->ptw_status == 'Approved') selected @endif value="Approved">Approved</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-12">
                <div class="mb-1 row">
                    <div class="col-lg-12">
                        <label class="col-form-label">Notes: <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control" name="notes" required rows="6">{{ $data->note }}</textarea>
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
    <button type="submit" class="btn btn-primary" id="btn-edit-master" form="form-edit-master">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

<script>
    $('#form-edit-master').on('submit', function(e) {
        let button = $('#btn-edit-master');
        if (button.prop('disabled')) {
            return false;
        }
        button.prop('disabled', true);
        button.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('ptw.update', ':id:') }}";
        var url = url.replace(':id:', id);

        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(res) {
                $('#modalEdit').modal('hide');
                if (res.success) {
                    showToastSuccess("Data has been updated");
                    $('#btn-edit-master').prop('disabled', false);
                    $('#btn-edit-master').html('Submit All Changes');
                } else {
                    showToastError(res.message);
                    $('#btn-edit-master').prop('disabled', false);
                    $('#btn-edit-master').html('Submit All Changes');
                }
            },
            error: function() {
                showToastError("Error while updating data");
                $('#btn-edit-master').prop('disabled', false);
                $('#btn-edit-master').html('Submit All Changes');
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
</script>

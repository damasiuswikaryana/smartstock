<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Vendor Data</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-2">
        @csrf
        @method('POST')
        <div class="row">
            <div class="col-6">
                <h4 class="fw-bold mb-3">Vendor Info</h4>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Name:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Vendor name" name="nama"
                            value="{{ $data->nama }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Code:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Vendor code" name="kode"
                            value="{{ $data->kode }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Address:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Vendor address" name="alamat"
                            value="{{ $data->alamat }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Postal Code:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Postal code" name="kode_pos"
                            value="{{ $data->kode_pos }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Kabupaten:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Kabupaten" name="kabupaten"
                            value="{{ $data->kabupaten }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Province:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Province" name="provinsi"
                            value="{{ $data->provinsi }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Country:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Country" name="negara"
                            value="{{ $data->negara }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Phone:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Vendor phone number / Whatsapp"
                            name="phone" value="{{ $data->phone }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Email:</label>
                    <div class="col-lg-8">
                        <input type="email" class="form-control" placeholder="Vendor email" name="email"
                            value="{{ $data->email }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Website:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Vendor website" name="website"
                            value="{{ $data->website }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Status:</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="status">
                            <option @if ($data->status == 'Active') selected @endif value="Active">Active</option>
                            <option @if ($data->status == 'Inactive') selected @endif value="Inactive">Incative
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <h4 class="fw-bold mb-3">Payment Info</h4>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">NPWP:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="NPWP" name="npwp"
                            value="{{ $data->npwp }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Bank:</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="bank_id">
                            @foreach ($bank as $b)
                                <option @if ($data->bank_id == $b->id) selected @endif value="{{ $b->id }}">
                                    {{ $b->bank_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Bank Account Number:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Bank Account Number"
                            name="bank_account_number" value="{{ $data->bank_account_number }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Bank Account Name:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Bank Account Name"
                            name="bank_account_name" value="{{ $data->bank_account_name }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">DP Availibility:</label>
                    <div class="col-lg-8">
                        <select class="form-control" name="is_dp">
                            <option @if ($data->is_dp == 1) selected @endif value="1">Yes</option>
                            <option @if ($data->is_dp == 0) selected @endif value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="mb-5 row">
                    <label class="col-lg-4 col-form-label">Terms of Payment:</label>
                    <div class="col-lg-8">
                        <input type="number" class="form-control" placeholder="Ex: 30 (in days)"
                            name="termin_pembayaran" value="{{ $data->termin_pembayaran }}">
                    </div>
                </div>

                <h4 class="fw-bold mb-3">Person in Charge Info</h4>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">PIC Name:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="PIC Name" name="pic_name"
                            value="{{ $data->pic_name }}">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label">Position:</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Position" name="pic_jabatan"
                            value="{{ $data->pic_jabatan }}">
                    </div>
                </div>


            </div>

            <div class="col-12">
                <div class="mb-3 row">
                    <label class="col-lg-12 col-form-label pb-1">Catatan:</label>
                    <div class="col-lg-12">
                        <textarea class="form-control" name="catatan">{{ $data->catatan }}</textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script>
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('vendor.update', ':id:') }}";
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

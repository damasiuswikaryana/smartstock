
<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Customer</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Nama Customer:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Nama Customer" name="nama" value="{{ $data->nama }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Jenis Kelamin:</label>
            <div class="col-lg-8">
                <select class="form-control" name="jenis_kelamin">
                    <option {{ $data->jenis_kelamin == 'Pria' ? 'selected' : '' }} value="Pria">Pria</option>
                    <option {{ $data->jenis_kelamin == 'Wanita' ? 'selected' : '' }} value="Wanita">Wanita</option>
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Alamat:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Alamat" name="alamat" value="{{ $data->alamat }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">No. Telepon:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="No. Telepon" name="phone" value="{{ $data->phone }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">E-mail:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="E-mail" name="email" value="{{ $data->email }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Company:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Nama Perusahaan" name="company" value="{{ $data->company }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Jabatan:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" value="{{ $data->jabatan }}" />
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
        var url = "{{ route('customer.update', ':id:') }}";
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
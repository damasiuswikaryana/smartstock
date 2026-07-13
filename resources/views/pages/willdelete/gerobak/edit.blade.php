
<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Data Gerobak</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Outlet:</label>
            <div class="col-lg-8">
                <select name="loc_id" id="loc_id" class="form-control form-select">
                    <!-- <option disabled selected>--Pilih Satu--</option> -->
                    @forelse ($outlet as $item)
                    <option value="{{ $item->id }}" {{ $data->loc_id == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>                                        
                    @empty
                    <option value="">Tidak Ada Data</option>                                        
                    @endforelse
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Pegawai:</label>
            <div class="col-lg-8">
                <select name="user_id" id="user_id" class="form-control form-select">
                    <!-- <option disabled >--Pilih Satu--</option> -->
                    @forelse ($emp as $item)
                    <option value="{{ $item->id }}" {{ $data->user_id == $item->id ? 'selected' : '' }}>{{ $item->firstname.' '.$item->lastname }}</option>                                        
                    @empty
                    <option value="">Tidak Ada Data</option>                                        
                    @endforelse
                </select>

            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">ID Gerobak:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Plat Nomor"
                        name="kode" value="{{ $data->kode }}" />
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="ID Gerobak"
                        name="nama" value="{{ $data->nama }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Koordinat:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Koordinat Latitude"
                        name="lat" value="{{ $data->lat }}" />
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="Koordinat Longitude"
                        name="long" value="{{ $data->long }}"/>
                </div>
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
        var url = "{{ route('gerobak.update', ':id:') }}";
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
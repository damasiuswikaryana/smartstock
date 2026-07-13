<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Werehouse</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Werehouse Name:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan nama outlet" name="nama"
                    value="{{ $data->nama }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Address:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan alamat outlet" name="alamat"
                    value="{{ $data->alamat }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Area:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Kabupaten" name="kabupaten"
                        value="{{ $data->kabupaten }}" />
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="Provinsi" name="provinsi"
                        value="{{ $data->provinsi }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Coordinates:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Koordinat Latitude" name="lat"
                        value="{{ $data->lat }}" />
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="Koordinat Longitude" name="long"
                        value="{{ $data->long }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Werehouse Photo:</label>
            <div class="col-lg-8 row">
                @if ($data->photo != null)
                    <img src="{{ asset('storage/outlet/' . $data->photo) }}" class="mb-2" style="width:40%;" />
                @endif
                <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpener">Upload
                    File</button>
                @if ($data->photo != null)
                    <span class="text-danger f-10 mt-1"><b>Note:</b> Upload new photo to replace previous photo</span>
                @endif
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
        var url = "{{ route('outlet.update', ':id:') }}";
        var url = url.replace(':id:', id);

        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function(res) {
                $('#modalEdit').modal('hide');
                table.ajax.reload(null, false);
                hideLoader();
                showToastSuccess("Data has been updated");
            },
            error: function() {
                hideLoader();
                showToastError("Error while updating data");
            }
        });
    });
</script>
<script type="module">
    // Function for displaying uploaded files
    const onUploadSuccess = (elForUploadedFiles) => (file, response) => {
        const url = response.uploadURL;
        const fileName = file.name;
        const li = document.createElement('li');
        const a = document.createElement('a');
        a.href = url;
        a.target = '_blank';
        a.appendChild(document.createTextNode(fileName));
        li.appendChild(a);
        document.querySelector(elForUploadedFiles).appendChild(li);
    };

    const outletId = {{ $data->id }};
    const uploadUrl = `outlet/${outletId}/upload-foto`;

    import {
        Uppy,
        Dashboard,
        Webcam,
        XHRUpload,
        DragDrop,
        ProgressBar,
    } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';

    // for popup modal open and upload files
    const uppy = new Uppy({
            debug: true,
            autoProceed: false
        })
        .use(Dashboard, {
            trigger: '#uppyModalOpener'
        })
        .use(Webcam, {
            target: Dashboard
        })
        .use(XHRUpload, {
            endpoint: uploadUrl,
            fieldName: 'file',
            formData: true,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

    uppy.on('success', (fileCount) => {
        console.log(`${fileCount} files uploaded`);
    });
</script>

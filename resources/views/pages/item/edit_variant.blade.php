<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Item Variant</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" method="post" id="form-edit-variant">
    <div class="px-4">
        @method('POST')
        @csrf
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Variant SKU:</label>
            <div class="col-lg-8">
                <div class="">
                    <input type="text" class="form-control" placeholder="Input SKU" name="sku_varian" maxlength="20"
                        value="{{ $data->sku_varian }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Variant Name:</label>
            <div class="col-lg-8">
                <div class="">
                    <input type="text" class="form-control" placeholder="Variant Name" name="name_varian"
                        value="{{ $data->name_varian }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Variant Code:</label>
            <div class="col-lg-8">
                <div class="">
                    <input type="text" class="form-control" placeholder="Variant Code (3 Characters)"
                        name="kode_varian" maxlength="3" value="{{ $data->kode_varian }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Value:</label>
            <div class="col-lg-8">
                <div class="">
                    <input type="text" class="form-control number-separator" placeholder="Value in rupiah"
                        name="nilai" value="{{ pecahTanpaRp($data->nilai) }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Image:</label>
            <div class="col-lg-8 row">
                @if ($data->image_varian != null)
                    <img src="{{ asset('storage/variant/' . $data->image_varian) }}" class="mb-2"
                        style="width:40%;" />
                @endif
                <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpenerVariant">Upload
                    File</button>
                @if ($data->image_varian != null)
                    <span class="f-10 text-danger">Reupload photo for replace previous photo</span>
                @endif
            </div>
        </div>
        <div class="alert alert-warning text-center mb-0"><b>Note:</b> Photo must be 500 x 500 pixel or ratio.
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary" form="form-edit-variant">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
<script>
    $('#uppyModalOpenerVariant').on('click', function(e) {
        e.preventDefault();
        $('#modalEdit').modal('hide');
    });
    $('#form-edit-variant').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('item_variant.update', ':id:') }}";
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

    const productId = {{ $data->id }};
    const uploadUrl = `/item_variant/${productId}/upload-foto`;

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
            trigger: '#uppyModalOpenerVariant'
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

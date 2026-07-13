<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Upload Bukti Pembayaran</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body p-0" action="#" method="post" id="form-edit-pembayaran">
    <div class="p-3">
        @csrf
        @method('POST')
        <div class="mb-0 row">
            <div class="col-lg-12">
                <button type="button" class="pc-uppy-btn btn btn-lg btn-light-danger w-100" id="uppyModalOpener">
                    <i class="ti ti-upload me-2"></i>
                    Upload File
                </button>
            </div>
        </div>
    </div>
</form>
<!--<div class="modal-footer p-2">-->
<!--    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
<!--    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>-->
<!--</div>-->

<script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
<script>
    $('#uppyModalOpener').on('click', function(e) {
        e.preventDefault();
        $('#modalEdit').modal('hide');
    });
</script>
<script type="module">
    // Function for displaying uploaded files
    const onUploadSuccess = (elForUploadedFiles) => (file, response) => {
        const url       = response.uploadURL;
        const fileName  = file.name;
        const li        = document.createElement('li');
        const a         = document.createElement('a');
        a.href          = url;
        a.target        = '_blank';
        a.appendChild(document.createTextNode(fileName));
        li.appendChild(a);
        document.querySelector(elForUploadedFiles).appendChild(li);
    };
    
    const pembayaranId = {{ $data->id }};
    const uploadUrl = `/transaction/event/${pembayaranId}/upload-bukti-pembayaran`;
    
    import { Uppy, Dashboard, Webcam, XHRUpload, DragDrop, ProgressBar, } from 'https://releases.transloadit.com/uppy/v3.23.0/uppy.min.mjs';

    // for popup modal open and upload files
    const uppy = new Uppy({ debug: true, autoProceed: false })
        .use(Dashboard, { trigger: '#uppyModalOpener' })
        .use(Webcam, { target: Dashboard })
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
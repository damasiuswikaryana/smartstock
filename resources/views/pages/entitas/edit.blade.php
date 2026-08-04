<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Entity</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('PUT')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Entity Name: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Entity Name" name="name"
                    value="{{ $data->entitas_name }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Address: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Entity Address" name="alamat"
                    value="{{ $data->entitas_alamat }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Company Name: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Company Name" name="company"
                    value="{{ $data->entitas_company }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Email: </label>
            <div class="col-lg-8">
                <input type="email" class="form-control" placeholder="Entity Email" name="email"
                    value="{{ $data->entitas_email }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Phone: </label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Entity Phone" name="phone"
                    value="{{ $data->entitas_phone }}" />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Director: <span class="text-danger">*</span></label>
            <div class="col-lg-8">
                <select class="form-control" name="director" required>
                    <option value="" disabled selected>-- Select User --</option>
                    @foreach ($directors as $dir)
                        <option @if ($data->director_id == $dir->id) selected @endif value="{{ $dir->id }}">
                            {{ $dir->firstname . ' ' . $dir->lastname }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Logo:</label>
            <div class="col-lg-8 row">
                @if ($data->entitas_logo != null)
                    <img src="{{ asset('storage/entitas/' . $data->entitas_logo) }}" class="mb-2"
                        style="width:40%;" />
                @endif
                <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpenerEntitas">Upload
                    File</button>
                @if ($data->entitas_logo != null)
                    <span class="f-10 text-danger">Reupload logo for replace previous logo</span>
                @endif
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

<script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
<script>
    $('#uppyModalOpenerEntitas').on('click', function(e) {
        e.preventDefault();
        $('#modalEdit').modal('hide');
    });
    $('#form-edit').on('submit', function(e) {
        e.preventDefault();
        const id = "{{ $data->id }}";
        var url = "{{ route('entitas.update', ':id:') }}";
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

    const entitasId = {{ $data->id }};
    const uploadUrl = `/entitas/${entitasId}/upload-logo`;

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
            trigger: '#uppyModalOpenerEntitas'
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

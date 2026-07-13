<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Employee ID:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Enter employee ID" name="emp_id"
                    value="{{ $data->emp_id }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Name:</label>
            <div class="col-lg-8 row me-0 pe-0">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="First Name" name="firstname"
                        value="{{ $data->firstname }}">

                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="Last Name" name="lastname"
                        value="{{ $data->lastname }}">

                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Username:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Enter username" name="username"
                    value="{{ $data->username }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Email:</label>
            <div class="col-lg-8">
                <input type="email" class="form-control" placeholder="Enter email" name="email"
                    value="{{ $data->email }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Phone Number:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Enter phone number" name="phone"
                    value="{{ $data->phone }}">
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Photo Profile:</label>
            <div class="col-lg-8">
                @if ($data->photo != null)
                    <img id="imageUppy" src="{{ asset('storage/user/' . $data->photo) }}" class="mb-2"
                        style="width:40%;" /><br>
                @endif
                <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpener">Upload
                    File</button>
                @if ($data->photo != null)
                    <br><span class="f-10 text-danger">Reupload photo for replace previous photo</span>
                @endif
            </div>
        </div>
        <hr />
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Job Placement:</label>
            <div class="col-lg-8">
                <select class="form-select" id="loc_id" name="loc_id">
                    <option selected disabled>Choose werehouse</option>
                    @forelse ($outlet as $item)
                        <option @if ($data->loc_id == $item->id) selected @endif value="{{ $item->id }}">
                            {{ $item->nama }}</option>
                    @empty
                    @endforelse
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Roles:</label>
            <div class="col-lg-8">
                <select class="form-select" id="roles" name="roles">
                    @foreach ($roles as $role)
                        <option @if ($data->roles->first()?->id == $role->id) selected @endif value="{{ $role->id }}">
                            {{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Status:</label>
            <div class="col-lg-8">
                <select class="form-select" id="status" name="status">
                    <option @if ($data->status == 'Active') selected @endif value="Active">Active</option>
                    <option @if ($data->status == 'Inactive') selected @endif value="Inactive">Inactive</option>
                </select>
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
        var url = "{{ route('user.update', ':id:') }}";
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

    const userId = {{ $data->id }};
    const uploadUrl = `user/${userId}/upload-foto`;

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

    uppy.on('complete', (result) => {
        const response = result.successful[0].response.body;
        uppy.getPlugin('Dashboard').closeModal();

        // Tampilkan lagi modal edit
        setTimeout(() => {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        }, 300);

        const img = document.getElementById('imageUppy');
        img.src = `/storage/user/${response.filename}`;
    });
</script>

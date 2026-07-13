<link rel="stylesheet" href="{{ asset('assets/css/plugins/uppy.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Data Produk</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="#" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Nama Produk:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select name="kategori" class="form-select select2">
                        @foreach(App\Models\Category::all() as $item)
                            <option value="{{ $item->id }}" {{ $data->cat_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control" placeholder="ID Gerobak"
                        name="nama" value="{{ $data->nama }}" />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Detail Produk:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <input type="text" class="form-control" placeholder="Satuan"
                        name="satuan" value="{{ $data->satuan }}" />
                </div>
                <div class="col-12 col-lg-6 pe-0">
                    <input type="text" class="form-control number-separator" placeholder="Harga Jual"
                        name="harga" value="{{ pecahTanpaRp($data->harga) }}"/>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Produk Varian:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select name="is_varian" id="is_varian" class="form-select select2">
                        <option {{ $data->is_varian == 1 ? 'selected' : '' }} value="1">Ya</option>
                        <option {{ $data->is_varian == 0 ? 'selected' : '' }} value="0">Tidak</option>
                    </select>
                </div>
                <div class="col-12 col-lg-6">
                    <select name="product_master_id" id="product_master_id" class="form-select select2" style="{{ $data->is_varian == 1 ? 'display:block;' : 'display:none;' }}">
                        @foreach ($data_product as $product)
                        <option {{ $data->product_master_id == $product->id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Tampilkan di Gerobak:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select name="showin_gerobak" class="form-select select2">
                        <option @if ($data->showin_gerobak == 1) selected @endif value=1>Ya</option>
                        <option @if ($data->showin_gerobak == 0) selected @endif value=0>Tidak</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Tampilkan di Outlet:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select name="showin_outlet" class="form-select select2">
                        <option @if ($data->showin_outlet == 1) selected @endif value=1>Ya</option>
                        <option @if ($data->showin_outlet == 0) selected @endif value=0>Tidak</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Foto Produk:</label>
            <div class="col-lg-8 row">
                @if ($data->photo != null)
                <img src="{{ asset('storage/produk/'.$data->photo) }}" class="mb-2" style="width:40%;" />
                @endif
                <button type="button" class="pc-uppy-btn btn btn-light-primary" id="uppyModalOpener">Upload File</button>
                @if ($data->photo != null)
                <span class="text-danger">Upload photo untuk mengganti foto sebelumnya</span>
                @endif
            </div>
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/uppy.min.js') }}"></script>
<script>
    $('#uppyModalOpener').on('click', function(e) {
        e.preventDefault();
        $('#modalEdit').modal('hide');
    });
    
    $('#is_varian').on('change', function() {
        var selected = $(this).val();
        if (selected === '0') { $('#product_master_id').hide(); } 
        else { $('#product_master_id').show(); }
    });
    
    $('#form-edit').on('submit', function (e) {
        e.preventDefault();
        const id    = "{{ $data->id }}";
        var url     = "{{ route('product.update', ':id:') }}";
        var url     = url.replace(':id:', id);
        
        $.ajax({
            url: url,
            type: 'PUT',
            data: $(this).serialize(),
            beforeSend: showLoader(),
            success: function (res) {
                $('#modalEdit').modal('hide');
                if (res.success) {
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
    
    const productId = {{ $data->id }};
    const uploadUrl = `product/${productId}/upload-foto`;
    
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
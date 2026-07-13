@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Initation" module="Add Initation Stock">
        <li class="breadcrumb-item">Stock</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6">
            <button type="button" class="btn btn-shadow btn-light-primary me-2 d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i
                    class="ph-duotone ph-plus-circle icon-search me-2"></i> Add Initation Stock</button>
        </div>
    </div>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Add Stock Initation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-tambah">
                    <div class="px-2">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Werehouse:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="lokasi_id">
                                            @foreach ($lokasi as $loc)
                                                <option value="{{ $loc->id }}">{{ $loc->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label">Entity:</label>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="entitas_id">
                                            @foreach ($entitas as $ent)
                                                <option value="{{ $ent->id }}">{{ $ent->entitas_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <h4 class="fw-bold mb-3">Items</h4>
                                <div id="produk-container">

                                </div>
                                <div class="row mb-0 p-2">
                                    <a href="#" id="btn-add-product"
                                        class="btn btn-light-primary w-100 d-flex justify-content-center align-items-center">
                                        <i class="fa fa-plus-circle me-2"></i>
                                        <span>Add Item</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" form="form-tambah">Submit Data</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $('#form-tambah').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '{{ route('stockinit.simpan') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        $('#form-tambah')[0].reset();
                        hideLoader();
                        $('#exampleModalCenter').modal('hide');
                        showToastSuccess("Data has been added");
                    } else {
                        hideLoader();
                        showToastError(response.message);
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showToastError("Error while adding data");
                }
            });
        });

        $(document).on('click', '.btn-delete-produk', function() {
            $(this).closest('.produk-item').remove();
        });

        let itemMasterIndex = 0;
        $('#btn-add-product').on('click', function(e) {
            e.preventDefault();
            let html = `
                <div class="row p-0 mx-0 mb-2 produk-item">
                    <div class="col-11 col-lg-11 ps-0">
                        <select data-index="${itemMasterIndex}" class="form-control item-master" name="item[${itemMasterIndex}][id_item]" required>
                            <option value="" selected disabled>Select Item</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <div class="variant-container mt-2" id="variant-container-${itemMasterIndex}"></div>
                    </div>
                    <div class="col-1 col-lg-1 mx-0 pe-0">
                        <button id="btn-delete-${itemMasterIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                </div>
                `;

            $('#produk-container').append(html);
            itemMasterIndex++;
        });

        $(document).on('change', '.item-master', function() {
            let itemId = $(this).val();
            let index = $(this).data('index');
            $.ajax({
                url: "{{ route('getVariants', ':id') }}".replace(':id', itemId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    $.each(res.variants, function(i, variant) {
                        html += `
                    <div class="row mb-2 align-items-center">
                        <div class="col-1 text-center">
                            <i class="fs-3 ph-duotone ph-arrow-elbow-down-right"></i>
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control" value="${variant.sku_varian}" disabled>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" value="${variant.name_varian}" disabled>
                        </div>
                        <div class="col-3">
                            <input type="number" min="0" class="form-control" name="item[${index}][variants][${variant.id}][qty]" placeholder="Qty" value="0">
                            <input type="hidden" name="item[${index}][variants][${variant.id}][id_variant]" value="${variant.id}">
                        </div>
                    </div>`;
                    });
                    $('#variant-container-' + index).html(html);
                }
            });
        });
    </script>
@endpush

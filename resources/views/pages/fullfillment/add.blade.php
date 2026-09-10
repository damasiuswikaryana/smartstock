@extends('layouts.main')

@section('content')
    <x-page-header title="Project Requirements" module="Project Requirements">
        <li class="breadcrumb-item">Contract Fullfillment</li>
    </x-page-header>

    <div class="d-flex justify-content-start gap-3 align-items-center mb-4 mt-3">
        <a href="javascript:void(0);" class="btn btn-lg btn-primary btn-shadow" style="border-radius:50px;">Project
            Requirement</a>
        <a href="{{ route('fullfillment.detail', $data->id) }}" class="btn btn-lg btn-light-primary btn-shadow"
            style="border-radius:50px;">Contract Fullfillment</a>
    </div>

    <section class="">
        <div class="row">
            <div class="col-12 col-lg-12 mb-4">
                <div class="row g-4">
                    <div class="col-md-12">
                        <ol class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Status
                                </div>
                                <div class="ms-0 me-auto fw-bold col-8">
                                    {{ $data->status }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Project
                                </div>
                                <div class="ms-0 me-auto fw-bold col-8">
                                    {{ $data->name }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Entity
                                </div>
                                <div class="ms-0 me-auto col-8">
                                    {{ $data->entitas->entitas_name }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Werehouse
                                </div>
                                <div class="ms-0 me-auto fw-bold col-8">
                                    {{ $data->lokasi->nama }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Contract
                                </div>
                                <div class="ms-0 me-auto fw-bold col-8">
                                    <code>{{ $data->no_kontrak }}</code>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Start
                                </div>
                                <div class="ms-0 me-auto col-8">
                                    {{ tanggalIndo($data->date_join) }}
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-0 me-auto col-4">
                                    Period
                                </div>
                                <div class="ms-0 me-auto col-8">
                                    {{ $data->jangka_waktu }} months
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="mb-0">Add More Items</h4>
                    </div>
                    <div class="card-body py-2">
                        <form action="{{ route('fullfillment.storeItem', $data->id) }}" method="POST" class=""
                            id="form-tambah-item">
                            @csrf
                            @method('POST')
                            <div id="items-container">

                            </div>
                            <div class="row mb-0 p-2 pt-0 justify-content-center">
                                <a href="#" id="btn-add-product"
                                    class="btn btn-light-dark w-auto d-flex justify-content-center align-items-center">
                                    <i class="fa fa-plus-circle me-2"></i>
                                    <span>Add More Item</span>
                                </a>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer p-2">
                        <button type="submit" class="btn btn-light-primary w-100" form="form-tambah-item">Save All</button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header d-flex align-items-center justify-content-between py-3">
                        <h4 class="mb-0">Item Requirements</h4>
                    </div>
                    <div class="card-body py-2 px-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-sm mb-0">
                                <tbody>
                                    @forelse($data->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <div class="d-inline-block">
                                                        <h6 class="m-b-0">{{ $item->itemMaster->nama }}</h6>
                                                        <p class="m-b-0">{{ $item->itemMaster->category->title }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code style="font-size:13px;" class="mb-0">x
                                                    {{ $item->req_qty }}</code>
                                            </td>
                                            @php
                                                $subtotalContract = $item->req_qty * $item->req_nominal;
                                                $subtotalCompany = $item->req_qty * $item->req_nominal_company;
                                            @endphp
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotalContract) }}</p>
                                                <p class="mb-0 text-muted">
                                                    <small>{{ '@' . rupiah($item->req_nominal) }}</small>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotalCompany) }}</p>
                                                <p class="mb-0 text-muted">
                                                    <small>{{ '@' . rupiah($item->req_nominal_company) }}</small>
                                                </p>
                                            </td>
                                            <td class="text-end">
                                                <button data-id="{{ $item->id }}"
                                                    class="btn avtar avtar-xs btn-light-danger btn-delete"><i
                                                        class="ti ti-x"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <div class="d-inline-block align-middle">
                                                    <div class="d-inline-block">
                                                        <h6 class="m-b-0">No Items</h6>
                                                        <p class="m-b-0">No items added to this project requirement.</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    <tr>
                                        <td>Total items:</td>
                                        <td>
                                            <p class="text-danger fw-bold mb-0">{{ $totalQty }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-bold">{{ rupiah($grandTotalContract) }}</p>
                                            <p class="mb-0">Total Budget Contract</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-bold">{{ rupiah($grandTotalCompany) }}</p>
                                            <p class="mb-0">Total Budget Company</p>
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script type="text/javascript">
        function formatRupiah(angka) {
            return Number(angka || 0).toLocaleString('id-ID');
        }

        function initItemMasterChoices(element) {
            new Choices(element, {
                searchEnabled: true,
                searchPlaceholderValue: 'Search item...',
                itemSelectText: '',
                shouldSort: false,
                allowHTML: true,
                placeholder: true,
                placeholderValue: 'Select Item'
            });
        }

        let itemMasterIndex = 0;
        $('#btn-add-product').on('click', function(e) {
            e.preventDefault();
            let html = `
                <div class="mb-2 row align-items-center contract-item">
                    <div class="col-6 mb-2">
                        <label class="col-form-label">Category:</label>
                        <div class="">
                            <select data-index="${itemMasterIndex}" class="form-control select-category select2" name="item[${itemMasterIndex}][category_id]" id="category-id-${itemMasterIndex}"
                                required>
                                <option value="" disabled selected>-- Select Category --</option>
                                @foreach ($category as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <label class="col-lg-4 col-form-label">Items:</label>
                        <div class="">
                            <select class="form-control" name="item[${itemMasterIndex}][item_master_id]" id="item_master_id-${itemMasterIndex}" required>
                                <option value="">-- Select Category --</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="">
                            <input type="number" class="form-control" placeholder="Qty" name="item[${itemMasterIndex}][qty]"
                                required />
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="">
                            <input type="text" class="form-control number-separator"
                                placeholder="Value per item (in rupiah)" name="item[${itemMasterIndex}][harga]" required />
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="">
                            <input type="text" class="form-control number-separator"
                                placeholder="Value per item (in rupiah)" name="item[${itemMasterIndex}][harga_company]" required />
                        </div>
                    </div>
                    <div class="col-1 text-center">
                        <button id="btn-delete-${itemMasterIndex}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>
                    <hr class="mt-3">
                </div>
                `;

            $('#items-container').append(html);
            let selectElement = document.getElementById(`category-id-${itemMasterIndex}`);
            initItemMasterChoices(selectElement);
            itemMasterIndex++;
        });

        $(document).on('change', '.select-category', function() {
            let catId = $(this).val();
            let index = $(this).data('index');
            $.ajax({
                url: "{{ route('getItembyCategory', ':id') }}".replace(':id', catId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    $.each(res.items, function(i, row) {
                        html += `<option value='${row.id}'>${row.nama}</option>`;
                    });
                    $('#item_master_id-' + index).html(html);
                }
            });
        });

        $(document).on('click', '.btn-delete-produk', function() {
            $(this).closest('.contract-item').remove();
        });

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').each(function() {
                new Choices(this, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search here...',
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    placeholder: true,
                });
            });
        });

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            var url = "{{ route('fullfillment.hapus', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Delete this data?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function(res) {
                        if (res.success) {
                            hideLoader();
                            showToastSuccess("Data has been deleted");
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function() {
                        hideLoader();
                        showToastError("Error while deleting data");
                    }
                });
            }
        });
    </script>
@endpush

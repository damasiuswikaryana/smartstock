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
            <div class="col-12 mb-4">
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

            <div class="col-5">
                <div class="card">
                    <div class="card-header py-3">
                        <h4 class="mb-0">Add More Items</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('fullfillment.storeItem', $data->id) }}" method="POST" class=""
                            id="form-tambah-item">
                            @csrf
                            @method('POST')
                            <div class="mb-3 row">
                                <label class="col-lg-3 col-form-label">Category:</label>
                                <div class="col-lg-9">
                                    <div class="">
                                        <select class="form-control" name="category_id" id="category_id" required>
                                            <option value="" disabled selected>-- Selecet Category --</option>
                                            @foreach ($category as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-3 col-form-label">Items:</label>
                                <div class="col-lg-9">
                                    <div class="">
                                        <select class="form-control" name="item_master_id" id="item_master_id" required>
                                            <option value="">-- Selecet Category --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-lg-3 col-form-label">Quantity:</label>
                                <div class="col-lg-9">
                                    <div class="">
                                        <input type="number" class="form-control" placeholder="Quantity" name="qty"
                                            required />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0 row">
                                <label class="col-lg-3 col-form-label">Value:</label>
                                <div class="col-lg-9">
                                    <div class="">
                                        <input type="text" class="form-control number-separator"
                                            placeholder="Value per item (in rupiah)" name="harga" required />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer p-2">
                        <button type="submit" class="btn btn-light-primary w-100" form="form-tambah-item">Add Item</button>
                    </div>
                </div>
            </div>

            <div class="col-7">
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
                                                <code class="mb-0">x {{ $item->req_qty }}</code>
                                                <p class="mb-0 text-muted">
                                                    <small>{{ '@' . rupiah($item->req_nominal) }}</small>
                                                </p>
                                            </td>
                                            @php
                                                $subtotal = $item->req_qty * $item->req_nominal;
                                            @endphp
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotal) }}</p>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn avtar avtar-xs btn-light-danger"><i
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
                                            <p class="mb-0 fw-bold">{{ rupiah($grandTotal) }}</p>
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
    <script type="text/javascript">
        $(document).on('change', '#category_id', function() {
            let catId = $(this).val();
            $.ajax({
                url: "{{ route('getItembyCategory', ':id') }}".replace(':id', catId),
                type: "GET",
                success: function(res) {
                    let html = '';
                    $.each(res.items, function(i, row) {
                        html += `<option value='${row.id}'>${row.nama}</option>`;
                    });
                    $('#item_master_id').html(html);
                }
            });
        });
    </script>
@endpush

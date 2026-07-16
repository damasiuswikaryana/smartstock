@extends('layouts.main')

@section('content')
    <x-page-header title="Fullfillment Detail" module="Contract Fullfillment Detail">
        <li class="breadcrumb-item">Contract Fullfillment</li>
    </x-page-header>

    <div class="d-flex justify-content-start gap-3 align-items-center mb-4 mt-3">
        <a href="{{ route('fullfillment.add', $data->id) }}" class="btn btn-lg btn-light-primary btn-shadow"
            style="border-radius:50px;">Project
            Requirement</a>
        <a href="javascript:void();" class="btn btn-lg btn-primary btn-shadow" style="border-radius:50px;">Contract
            Fullfillment</a>
    </div>

    <section class="">
        <div class="row">
            <h4 class="mb-3">
                @if ($data->werehouse_id != null)
                    {{ $data->lokasi->nama }}
                    <br><small class="text-muted fw-lighter">{{ $data->entitas->entitas_name }}</small>
                @else
                    {{ 'No Werehouse Connected' }}
                @endif
            </h4>
            <div class="col-12 mb-4">
                <div class="card table-card">
                    <div class="card-body py-2 px-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Reality</th>
                                        <th>Cost Budget</th>
                                        <th>Cost Purchase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data->items as $item)
                                        @php
                                            $stock = $stockSummary->get($item->item_master_id);
                                        @endphp
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
                                            <td>
                                                <p class="mb-0">{{ $stock?->reality_qty ?? 0 }}</p>
                                                @if ($stock->reality_qty - $stock->qty_out != 0)
                                                    <p class="mb-0 text-muted">
                                                        <small>In stock: {{ $stock->reality_qty - $stock->qty_out }}</small>
                                                    </p>
                                                @endif
                                            </td>
                                            @php
                                                $subtotalBudget = $item->req_qty * $item->req_nominal;
                                            @endphp
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotalBudget) }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ rupiah($stock?->purchase_cost ?? 0) }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No items found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <h4 class="mb-3">Item Mutations</h4>
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-body p-3">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-sin-tab" data-bs-toggle="pill" href="#pills-sin"
                                    role="tab" aria-controls="pills-sin" aria-selected="true">In</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-sout-tab" data-bs-toggle="pill" href="#pills-sout"
                                    role="tab" aria-controls="pills-sout" aria-selected="false">Out</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-stransfer-tab" data-bs-toggle="pill" href="#pills-stransfer"
                                    role="tab" aria-controls="pills-stransfer" aria-selected="false">Transfer</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-sin" role="tabpanel"
                                aria-labelledby="pills-home-tab">
                                <p class="mb-0">Comoing soon</p>
                            </div>
                            <div class="tab-pane fade" id="pills-sout" role="tabpanel" aria-labelledby="pills-sout-tab">
                                <p class="mb-0">Comoing soon</p>
                            </div>
                            <div class="tab-pane fade" id="pills-stransfer" role="tabpanel"
                                aria-labelledby="pills-contact-tab">
                                <p class="mb-0">Comoing soon</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript"></script>
@endpush

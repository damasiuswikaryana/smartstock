@extends('layouts.main')

@push('css')
    <style>
        .text-add {
            color: #00b569 !important;
        }
    </style>
@endpush

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
                                        <th>Budget Contract</th>
                                        <th>Budget Company</th>
                                        <th>Cost Purchase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalBudgetContract = 0;
                                        $totalBudgetCompany = 0;
                                        $totalCostBudget = 0;
                                    @endphp
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
                                                <code style="font-size:13px;" class="mb-0">x {{ $item->req_qty }}</code>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $stock?->reality_qty ?? 0 }}</p>
                                                @if ($stock != null)
                                                    @if ($stock->reality_qty - $stock->qty_out != 0)
                                                        <p class="mb-0 text-muted">
                                                            <small>In stock:
                                                                {{ $stock->reality_qty - $stock->qty_out }}</small>
                                                        </p>
                                                    @endif
                                                @endif
                                            </td>
                                            @php
                                                $subtotalBudgetContract = $item->req_qty * $item->req_nominal;
                                                $subtotalBudgetCompany = $item->req_qty * $item->req_nominal_company;

                                                $totalBudgetContract = $totalBudgetContract + $subtotalBudgetContract;
                                                $totalBudgetCompany = $totalBudgetCompany + $subtotalBudgetCompany;
                                                $totalCostBudget = $totalCostBudget + $stock?->purchase_cost;
                                            @endphp
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotalBudgetContract) }}</p>
                                                <p class="mb-0 text-muted">
                                                    <small>{{ '@' . rupiah($item->req_nominal) }}</small>
                                                </p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ rupiah($subtotalBudgetCompany) }}</p>
                                                <p class="mb-0 text-muted">
                                                    <small>{{ '@' . rupiah($item->req_nominal_company) }}</small>
                                                </p>
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
                                <thead>
                                    <tr>
                                        <th colspan="3">Total</th>
                                        <th>{{ rupiah($totalBudgetContract) }}</th>
                                        <th>{{ rupiah($totalBudgetCompany) }}</th>
                                        <th>{{ rupiah($totalCostBudget) }}</th>
                                    </tr>
                                </thead>
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
                                aria-labelledby="pills-sin-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Date</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th>Quantity</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_in as $item_in)
                                                <tr>
                                                    <td>
                                                        <p class="mb-0">{{ tanggalIndo3($item_in->created_at) }}</p>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6 class="m-b-0">{{ $item_in->item_varian->name_varian }}
                                                                </h6>
                                                                <p class="text-muted m-b-0">
                                                                    {{ $item_in->item_varian->sku_varian }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <code class="mb-0">{{ $item_in->source_type }}</code>
                                                    </td>
                                                    <td>
                                                        <i class="f-20 ph-duotone ph-arrow-fat-lines-right text-add"></i>
                                                        <p class="mb-0">{{ $item_in->gudangTarget->nama }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="mb-0 text-add fw-bold">+{{ $item_in->jumlah }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0">{{ $item_in->keterangan }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-sout" role="tabpanel" aria-labelledby="pills-sout-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Date</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th>Quantity</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_out as $item_out)
                                                <tr>
                                                    <td>
                                                        <p class="mb-0">{{ tanggalIndo3($item_out->created_at) }}</p>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6 class="m-b-0">
                                                                    {{ $item_out->item_varian->name_varian }}
                                                                </h6>
                                                                <p class="text-muted m-b-0">
                                                                    {{ $item_out->item_varian->sku_varian }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <i class="f-20 ph-duotone ph-arrow-fat-lines-right text-danger"></i>
                                                        <p class="mb-0">{{ $item_out->gudangAsal->nama }}</p>

                                                    </td>
                                                    <td class="text-center">
                                                        <code class="mb-0">{{ $item_out->target_type }}</code>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="mb-0 text-danger fw-bold">-{{ $item_out->jumlah }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0">{{ $item_out->keterangan }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-stransfer" role="tabpanel"
                                aria-labelledby="pills-transfer-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover table-borderless table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Date</th>
                                                <th>Source</th>
                                                <th>Destination</th>
                                                <th>Quantity</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data_trf as $item_transfer)
                                                <tr>
                                                    <td>
                                                        <p class="mb-0">{{ tanggalIndo3($item_transfer->created_at) }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-block align-middle">
                                                            <div class="d-inline-block">
                                                                <h6 class="m-b-0">
                                                                    {{ $item_transfer->item_varian->name_varian }}
                                                                </h6>
                                                                <p class="text-muted m-b-0">
                                                                    {{ $item_transfer->item_varian->sku_varian }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <i
                                                            class="f-20 ph-duotone ph-arrow-fat-lines-right text-danger"></i>
                                                        <p class="mb-0">{{ $item_transfer->gudangAsal->nama }}</p>

                                                    </td>
                                                    <td>
                                                        <i class="f-20 ph-duotone ph-arrow-fat-lines-right text-add"></i>
                                                        <p class="mb-0">{{ $item_transfer->gudangTarget->nama }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="mb-0 text-add fw-bold">{{ $item_transfer->jumlah }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0">{{ $item_transfer->keterangan }}</p>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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

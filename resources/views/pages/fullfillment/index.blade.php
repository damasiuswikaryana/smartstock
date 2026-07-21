@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Contract List" module="Contract Fullfillment">
        <li class="breadcrumb-item">Contract Fullfillment</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-12 text-end">
        </div>
    </div>

    <section>
        <div class="row">
            @foreach ($pekerjaan as $data)
                <div class="col-4">
                    <div class="card user-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">{{ $data->name }}</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical f-18"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a class="dropdown-item"
                                            href="{{ route('fullfillment.detail', $data->id) }}">Detail</a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <code>{{ $data->no_kontrak }}</code>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="row g-1">
                                                <div class="col-6">
                                                    <h6 class="mb-0">Contract Items</h6>
                                                    <p class="text-muted mb-0"><small>All items in contract</small></p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    @php
                                                        $totalQty = $data->items->sum('req_qty');
                                                    @endphp
                                                    <h6 class="mb-1">{{ $totalQty }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="row g-1">
                                                <div class="col-10">
                                                    <h6 class="mb-0">Items In</h6>
                                                    <p class="text-muted mb-0"><small>All items in to werehouse as
                                                            stock</small></p>
                                                </div>
                                                <div class="col-2 text-end">
                                                    <h6 class="mb-1">
                                                        @php
                                                            $realityQty =
                                                                $stockSummary->get($data->id)?->reality_qty ?? 0;
                                                        @endphp
                                                        {{ $realityQty }}</h6>
                                                </div>
                                                <div class="col-12">
                                                    @php
                                                        $percentage =
                                                            $totalQty > 0 ? round(($realityQty / $totalQty) * 100) : 0;
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-2">
                                                            <div class="progress" style="height: 8px">
                                                                <div class="progress-bar bg-primary"
                                                                    style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h6 class="mb-0">{{ $percentage }}%</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="row g-1">
                                                <div class="col-10">
                                                    <h6 class="mb-0">Items Completed</h6>
                                                    <p class="text-muted mb-0"><small>All items issued and distributed to
                                                            employee</small></p>
                                                </div>
                                                <div class="col-2 text-end">
                                                    <h6 class="mb-1">
                                                        @php
                                                            $realityQty_out =
                                                                $stockSummary->get($data->id)?->reality_qty_out ?? 0;
                                                        @endphp
                                                        {{ $realityQty_out }}</h6>
                                                </div>
                                                <div class="col-12">
                                                    @php
                                                        $percentage =
                                                            $totalQty > 0
                                                                ? round(($realityQty_out / $totalQty) * 100)
                                                                : 0;
                                                    @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 me-2">
                                                            <div class="progress" style="height: 8px">
                                                                <div class="progress-bar bg-success"
                                                                    style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <h6 class="mb-0">{{ $percentage }}%</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item px-0 pb-0">
                                    <a href="{{ route('fullfillment.add', $data->id) }}"
                                        class="btn btn-light-primary w-100 mb-0">Add Items</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
@endpush

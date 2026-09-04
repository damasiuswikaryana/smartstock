@extends('layouts.main')

@section('content')
    <x-page-header title="Detail" module="Procurement to Warehouse">
        <li class="breadcrumb-item">Procurement</li>
        <li class="breadcrumb-item">Procurement to Warehouse</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        @if ($data->ptw_status == 'Pending')
        @else
            <div class="alert alert-info w-100 mb-0">
                <h4 class="mb-0">Important</h4>
                <p class="pb-0 mb-0">Data that has been approved cannot be changed or deleted.</p>
            </div>
        @endif
    </div>

    <section>
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0 d-flex align-items-center">
                        <code class="">PTW: {{ $data->ptw_number }}</code>
                    </h3>
                    <button id="btnDownload" class="btn btn-light-secondary d-flex align-items-center me-3 me-sm-0"
                        type="button">
                        <i class="ph-duotone ph-download icon-search me-2"></i>
                        <span>Download</span></button>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <ol class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        PRF Number
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        @foreach ($data->child->unique('po_id') as $child)
                                            @foreach (explode(',', $child->poMaster->prf_number) as $prf)
                                                <span class="badge bg-primary me-1 f-14">
                                                    {{ trim($prf) }}
                                                </span>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        PO Number
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        @foreach ($data->child->unique('po_id') as $child)
                                            <span class="badge bg-light-secondary me-1 f-14">
                                                {{ trim($child->poMaster->po_no) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Date
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ tanggalIndo($data->ptw_date) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Project
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->project->name }}
                                    </div>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Created
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        <p class="fw-medium mb-0">{{ tanggalIndoWaktuLidgkap($data->created_at) }}
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Status
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="po_status">
                                        @if ($data->ptw_status == 'Pending')
                                            <span class="f-14 badge bg-light-dark">Pending</span>
                                        @else
                                            <span class="f-14 badge bg-light-success text-green">Approved</span>
                                        @endif
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">Items</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <ol class="list-group list-group-numbered">
                                @foreach ($data->child as $child)
                                    <li class="list-group-item d-flex justify-content-between align-items-center row">
                                        <div class="col-6">
                                            <div class="fw-bold">{{ $child->varian->name_varian }}</div>
                                            {{ $child->varian->sku_varian }}<br>
                                        </div>
                                        <div class="col-1">
                                            <label class="fw-bold mb-1">PRF</label>
                                            <input type="number" class="form-control" value="{{ $child->prf_jum }}">
                                        </div>
                                        <div class="col-1">
                                            <label class="fw-bold mb-1">PO</label>
                                            <h3>{{ $child->poMaster }}</h3>
                                        </div>
                                        <div class="col-3">
                                            <label class="fw-bold mb-1">Note</label>
                                            <input type="text" class="form-control" value=""
                                                placeholder="Add additional notes here...">
                                        </div>
                                    </li>
                                @endforeach
                            </ol>

                            <ol class="list-group mt-3">
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript">
        $('#btnDownload').on('click', function() {
            let id = {{ $data->id }};
            let url = "{{ route('ptw.downloadPtw', ['id' => ':id']) }}".replace(':id', id);
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.click();
        });
    </script>
@endpush

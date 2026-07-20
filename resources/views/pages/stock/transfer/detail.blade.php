@extends('layouts.main')

@section('content')
    <x-page-header title="Detail" module="Stock Transfer Detail">
        <li class="breadcrumb-item">Stock</li>
        <li class="breadcrumb-item">Stock Transfer</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        @if ($data->status == 'Pending')
            <div class="col-12 d-flex justify-content-between align-items-center">
                <button type="button" id="approval-btn" class="btn btn-shadow btn-primary me-2 d-flex align-items-center">
                    <i class="ph-duotone ph-check-circle icon-search me-2"></i> Approve Stock Transfer
                </button>
            </div>
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
                <div class="card-header py-3">
                    <h4 class="mb-0 d-flex align-items-center">
                        <code>TRANSFER : {{ $data->stock_transfer_number }}</code>
                        <i class="ms-3 ph-duotone ph-arrow-fat-lines-right text-danger"></i>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <ol class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Date
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ tanggalIndo($data->transfer_date) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Project
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->pekerjaan->name }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Entity
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->entitas->entitas_name }}
                                    </div>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Source
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->gudangAsal->nama }}
                                        <p class="fw-medium mb-0">{{ tanggalIndoWaktuLidgkap($data->created_at) }} by
                                            {{ $data->createdBy->firstname . ' ' . $data->createdBy->lastname }}</p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Target
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->gudangTarget->nama }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Status
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="stock_status">
                                        @if ($data->status == 'Pending')
                                            <span class="f-14 badge bg-light-dark">Pending</span>
                                        @else
                                            <span class="f-14 badge bg-success">Approved</span>
                                        @endif
                                        @if ($data->approved_by != null)
                                            <p class="fw-medium mb-0">{{ tanggalIndoWaktuLidgkap($data->approved_date) }}
                                                by
                                                {{ $data->approvedBy->firstname . ' ' . $data->approvedBy->lastname }}</p>
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
                    <h4 class="mb-0">Items Out</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <ol class="list-group list-group-numbered">
                                @foreach ($data->child as $child)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $child->varian->name_varian }}</div>
                                            {{ $child->varian->sku_varian }}
                                        </div>
                                        <span class="f-14 badge bg-danger rounded-pill">x {{ $child->qty }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">Notes and Documentation</h4>
                </div>
                <div class="card-body">
                    <p class="fw-bold mb-1 text-danger">Notes:</p>
                    <p>{{ $data->note ? $data->note : '-' }}</p>

                    <div class="grid row g-3">
                        @foreach ($document as $doc)
                            <div class="col-xl-2 col-md-4 col-sm-6" id="wpdoc-{{ $doc->id }}">
                                <a class="card-gallery" data-fslightbox="gallery"
                                    href="{{ asset('storage/stock_transfer/' . $doc->filename) }}">
                                    <img class="img-fluid" src="{{ asset('storage/stock_transfer/' . $doc->filename) }}"
                                        alt="Documentation" />
                                    <div class="gallery-hover-data card-body justify-content-end">
                                        <div>
                                            <p class="text-white mb-0 text-truncate w-100">{{ $doc->filename }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).on('click', '#approval-btn', function() {
            let id = {{ $data->id }};
            var url = "{{ route('stocktransfer.approve', ':id:') }}";
            var url = url.replace(':id:', id);

            if (confirm('Approve this data?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: showLoader(),
                    success: function(res) {
                        if (res.success) {
                            $('#stock_status').html(
                                '<span class="f-14 badge bg-success">Approved</span><p class="fw-medium mb-0">' +
                                res.approve + '</p>');
                            hideLoader();
                            showToastSuccess("Approval success");
                        } else {
                            hideLoader();
                            showToastError(res.message);
                        }
                    },
                    error: function() {
                        hideLoader();
                        showToastError("Error while approving data");
                    }
                });
            }
        });
    </script>
@endpush

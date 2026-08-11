@extends('layouts.main')

@section('content')
    <x-page-header title="Detail" module="Purchase Order Detail">
        <li class="breadcrumb-item">Procurement</li>
        <li class="breadcrumb-item">Purchase Order</li>
    </x-page-header>

    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        @if ($data->po_status == 'Pending' || $data->po_status == 'Checked' || $data->po_status == 'Recorded')
            <div class="col-12 d-flex justify-content-start align-items-center">
                @if ($data->checked_date == null)
                    @hasanyrole('keuangan|masteradmin|admin')
                        <button type="button" id="checked-btn" class="btn btn-shadow btn-primary me-2 d-flex align-items-center">
                            <i class="ph-duotone ph-check-circle icon-search me-2"></i> Process to Checked
                        </button>
                    @endhasanyrole
                @endif
                @if ($data->checked_date != null && $data->adminInput_date == null)
                    @hasanyrole('adminkeuangan|masteradmin|admin')
                        <button type="button" id="recorded-btn"
                            class="btn btn-shadow btn-primary me-2 d-flex align-items-center">
                            <i class="ph-duotone ph-check-circle icon-search me-2"></i> Process to Recorded
                        </button>
                    @endhasanyrole
                @endif
                @if ($data->director_id != null)
                    @if ($data->adminInput_date != null && $data->checked_date != null && $data->director_date == null)
                        @hasanyrole('director|masteradmin|admin')
                            <button type="button" id="approval-btn"
                                class="btn btn-shadow btn-primary me-2 d-flex align-items-center">
                                <i class="ph-duotone ph-check-circle icon-search me-2"></i> Approve Purchase Order
                            </button>
                        @endhasanyrole
                    @endif
                @endif
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
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 d-flex align-items-center">
                        <code class="text-dark">PO NUMBER : {{ $data->po_no }}</code>
                    </h4>
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
                                        Date
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ tanggalIndo($data->po_date) }}
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
                                        Vendor
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->vendor->nama }}
                                    </div>
                                </li>

                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Created
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6">
                                        {{ $data->createdBy->firstname . ' ' . $data->createdBy->lastname }}
                                        <p class="fw-medium mb-0">{{ tanggalIndoWaktuLidgkap($data->created_at) }}
                                        </p>
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Financial Check
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="checked_status">
                                        @if ($data->checked_date == null)
                                            <span class="f-14 badge bg-light-dark">Waiting for check ...</span>
                                        @else
                                            <span class="f-14 badge bg-light-success text-green">Checked</span>
                                        @endif
                                        @if ($data->checked_by != null)
                                            <p class="fw-medium mb-0">
                                                @if ($data->checked_date != null)
                                                    {{ tanggalIndoWaktuLidgkap($data->checked_date) }}
                                                @endif by
                                                {{ $data->checkedBy->firstname . ' ' . $data->checkedBy->lastname }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Director Approval
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="director_status">
                                        @if ($data->director_id != null)
                                            @if ($data->director_date == null)
                                                <span class="f-14 badge bg-light-dark">Waiting for approval
                                                    ...</span>
                                            @else
                                                <span class="f-14 badge bg-light-success text-green">Approved</span>
                                            @endif
                                            <p class="fw-medium mb-0">
                                                @if ($data->director_date != null)
                                                    {{ tanggalIndoWaktuLidgkap($data->director_date) }}
                                                @endif by
                                                {{ $data->directorBy->firstname . ' ' . $data->directorBy->lastname }}
                                            </p>
                                        @else
                                            <span class="f-14 badge bg-light-dark">No Approval Needed</span>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Financial Record
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="recorded_status">
                                        @if ($data->adminInput_date == null)
                                            <span class="f-14 badge bg-light-dark">Waiting for record ...</span>
                                        @else
                                            <span class="f-14 badge bg-light-success text-green">Recorded</span>
                                        @endif
                                        @if ($data->adminInput_by != null)
                                            <p class="fw-medium mb-0">
                                                @if ($data->adminInput_date != null)
                                                    {{ tanggalIndoWaktuLidgkap($data->adminInput_date) }}
                                                @endif by
                                                {{ $data->adminInputBy->firstname . ' ' . $data->adminInputBy->lastname }}
                                            </p>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-0 me-auto col-6">
                                        Status
                                    </div>
                                    <div class="ms-0 me-auto col-6" id="po_status">
                                        @if ($data->po_status == 'Pending')
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
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $child->varian->name_varian }}</div>
                                            SKU: {{ $child->varian->sku_varian }}<br>
                                            {{ rupiah($child->unit_price) }}
                                        </div>
                                        <span class="f-14 badge bg-primary rounded-pill">x {{ $child->qty }}</span>
                                    </li>
                                @endforeach
                            </ol>

                            <ol class="list-group mt-3">
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        Subtotal
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end">
                                        {{ rupiah($subtotal) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        PPH
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end">
                                        {!! $data->tax . '%' . ' &nbsp; (' . rupiah($tax_amount) . ')' !!}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        PPN
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end">
                                        {!! $data->ppn . '%' . ' &nbsp; (' . rupiah($ppn_amount) . ')' !!}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        <i class="me-2 ph-duotone ph-arrow-right"></i> Total After Tax
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end text-danger">
                                        {{ rupiah($total_after_tax) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        Discount
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end text-green">
                                        {{ $data->disc_perc != null ? $data->disc_perc . '%' : '' }}
                                        {!! ' &nbsp; (' . rupiah($disc_amount) . ')' !!}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        <i class="me-2 ph-duotone ph-arrow-right"></i> Total After Discount
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end text-green">
                                        {{ rupiah($total_after_disc) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        Down Payment
                                    </div>
                                    <div class="ms-0 me-auto col-6 text-end">
                                        {{ rupiah($data->dp) }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start py-2">
                                    <div class="ms-0 me-auto col-6">
                                        Total
                                    </div>
                                    <div class="ms-0 me-auto fw-bold col-6 text-end fs-4">
                                        {{ rupiah($total_after_dp) }}
                                    </div>
                                </li>
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
        $(document).on('click', '#recorded-btn', function() {
            let id = {{ $data->id }};
            var url = "{{ route('po.recorded', ':id:') }}";
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
                            $('#recorded_status').html(
                                '<span class="f-14 badge bg-light-success text-green">Recorded</span><p class="fw-medium mb-0">' +
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

        $(document).on('click', '#checked-btn', function() {
            let id = {{ $data->id }};
            var url = "{{ route('po.checked', ':id:') }}";
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
                            $('#checked_status').html(
                                '<span class="f-14 badge bg-light-success text-green">Checked</span><p class="fw-medium mb-0">' +
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

        $(document).on('click', '#approval-btn', function() {
            let id = {{ $data->id }};
            var url = "{{ route('po.approved', ':id:') }}";
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
                            $('#director_status').html(
                                '<span class="f-14 badge bg-light-success text-green">Approved</span><p class="fw-medium mb-0">' +
                                res.approve + '</p>');
                            $('#po_status').html(
                                '<span class="f-14 badge bg-light-success text-green">Approved</span>'
                            );
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

        $('#btnDownload').on('click', function() {
            let id = {{ $data->id }};
            let url = "{{ route('po.downloadPo', ['id' => ':id']) }}".replace(':id', id);
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.click();
        });
    </script>
@endpush

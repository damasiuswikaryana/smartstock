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
                    <div class="d-flex justify-content-between align-items-center">
                        <button id="btnDownload" class="btn btn-light-secondary d-flex align-items-center me-2"
                            type="button">
                            <i class="ph-duotone ph-download icon-search me-2"></i>
                            <span>Download</span></button>

                        <a href="{{ route('ptw.ubah', $data->id) }}" data-bs-toggle="modal" data-bs-target="#modalEdit"
                            data-bs-placement="top" title="Edit"
                            class="btn btn-light-secondary d-flex align-items-center btn-edit" type="button">
                            <i class="ph-duotone ph-pencil icon-search me-2"></i>
                            <span>Edit PTW</span></a>
                    </div>
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
                                        @elseif ($data->ptw_status == 'Gudang')
                                            <span class="f-14 badge bg-light-primary">Received by Warehouse</span>
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
        {{-- purchase order --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">Purchase Order</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <ol class="list-group list-group-numbered">
                                @foreach ($data->child->unique('po_id') as $child)
                                    <li class="list-group-item d-flex justify-content-between align-items-center row"
                                        id="wp-po-{{ $child->poMaster->id }}">
                                        <div class="col-6">
                                            <a class="text-danger"
                                                href="{{ route('po.modalDetail', $child->poMaster->id) }}"
                                                data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-placement="top"
                                                title="Edit"><code>{{ $child->poMaster->po_no }}</code></a><br>
                                            {{ tglIndo4($child->poMaster->po_date) }}<br>
                                            {{ '(' . $child->poMaster->child->count('id_item_varian') . ') items' }}<br>
                                        </div>
                                        <div class="col-3">
                                            <label class="fw-bold mb-1">Vendor</label>
                                            <h5>{{ $child->poMaster->vendor->nama }}</h5>
                                        </div>
                                        <div class="col-2 text-end">
                                            <button id="btn-delete-{{ $child->poMaster->id }}" type="button"
                                                class="btn btn-rounded btn-light-danger btn-delete-po"
                                                style="font-size:20px;" data-id="{{ $child->poMaster->id }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    <form action="{{ route('ptw.addPo', $data->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row align-items-center mt-3">
                            <div class="col-md-10">
                                <select class="form-control select2" name="po_id">
                                    @foreach ($po as $dpo)
                                        <option value="{{ $dpo->id }}">{{ $dpo->po_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="w-100 btn btn-primary">Add Another PO</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- items --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header py-3">
                    <h4 class="mb-0">Items</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <form action="#" method="POST" id="form-edit-items">
                            @csrf
                            @method('POST')
                            <div class="col-md-12">
                                <ol class="list-group list-group-numbered">
                                    @foreach ($data->child as $child)
                                        <input type="hidden" name="items[{{ $child->id }}][child_id]"
                                            value="{{ $child->id }}">
                                        <li data-poid="{{ $child->poMaster->id }}"
                                            class="list-group-item d-flex justify-content-between align-items-center row">
                                            <div class="col-6">
                                                <a class="text-danger"
                                                    href="{{ route('po.modalDetail', $child->poMaster->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalDetail"
                                                    data-bs-placement="top"
                                                    title="Edit"><code>{{ $child->poMaster->po_no }}</code></a><br>
                                                <div class="fw-bold">{{ $child->varian->name_varian }}</div>
                                                {{ $child->varian->sku_varian }}<br>
                                            </div>
                                            <div class="col-1">
                                                <label class="fw-bold mb-1">PRF</label>
                                                <input name="items[{{ $child->id }}][prf_jum]" type="number"
                                                    class="form-control" value="{{ $child->prf_jum }}">
                                            </div>
                                            <div class="col-1">
                                                <label class="fw-bold mb-1">PO</label>
                                                <h3>{{ $child->qty_po ?? 0 }}</h3>
                                            </div>
                                            <div class="col-3">
                                                <label class="fw-bold mb-1">Note</label>
                                                <input name="items[{{ $child->id }}][note]" type="text"
                                                    class="form-control" value="{{ $child->note }}"
                                                    placeholder="Add additional notes here...">
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                            <div class="col-md-12 text-end mt-3">
                                <button type="submit" id="btn-submit-item" class="btn btn-primary">Submit All
                                    Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modalEdit" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modalEditTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

    <div id="modalDetail" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="modalDetailTitle">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

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

        $("#modalEdit").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        $("#modalDetail").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("href"));
        });

        $(document).on('click', '.btn-delete-po', function() {
            let id = $(this).data('id');
            var url = "{{ route('ptw.hapusPo', ':id:') }}";
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
                            $('#wp-po-' + id).remove();
                            $('li[data-poid="' + id + '"]').remove();
                            hideLoader();
                            showToastSuccess("Data has been deleted");
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

        $('#form-edit-items').on('submit', function(e) {
            let button = $('#btn-submit-item');
            if (button.prop('disabled')) {
                return false;
            }
            button.prop('disabled', true);
            button.html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            e.preventDefault();
            $.ajax({
                url: '{{ route('ptw.updateItem') }}', // Route untuk simpan data
                method: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(response) {
                    if (response.success) {
                        hideLoader();
                        showToastSuccess("Data has been added");
                        $('#btn-submit-item').prop('disabled', false);
                        $('#btn-submit-item').html('Submit All Changes');
                    } else {
                        hideLoader();
                        showToastError(response.message);
                        $('#btn-submit-item').prop('disabled', false);
                        $('#btn-submit-item').html('Submit All Changes');
                    }
                },
                error: function(xhr, status, error) {
                    hideLoader();
                    showToastError("Error: " + xhr.responseText);
                    $('#btn-submit-item').prop('disabled', false);
                    $('#btn-submit-item').html('Submit All Changes');
                }
            });
        });

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
    </script>
@endpush

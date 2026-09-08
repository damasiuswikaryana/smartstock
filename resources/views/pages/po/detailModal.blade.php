<div class="modal-header">
    <div>
        <h5>Purchase Order</h5>
        @foreach (explode(',', $data->prf_number) as $prf)
            <span class="badge bg-primary me-1 f-14">
                {{ trim($prf) }}
            </span>
        @endforeach
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row gy-3">
        <div class="col-6">
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted py-2 border-top-0">PO Number</td>
                            <td class="text-muted py-2 border-top-0">:</td>
                            <td class="py-2 border-top-0 text-danger">{{ $data->po_no }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Vendor</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">{{ $data->vendor->nama }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Entity</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">{{ $data->entitas->entitas_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Created by</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">
                                {{ $data->createdBy->firstname . ' ' . $data->createdBy->lastname }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-6">
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted py-2 border-top-0">PO Date</td>
                            <td class="text-muted py-2 border-top-0">:</td>
                            <td class="py-2 border-top-0">{{ tanggalIndo($data->po_date) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Status</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">
                                @if ($data->po_status == 'Pending')
                                    <span class="f-14 badge bg-light-dark">Pending</span>
                                @else
                                    <span class="f-14 badge bg-light-success text-green">Approved</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Created</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">{{ tanggalIndoWaktuLidgkap($data->created_at) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-2">Items</td>
                            <td class="text-muted py-2">:</td>
                            <td class="py-2">{{ $data->child->count('item_varian_id') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12">
            <ol class="list-group list-group-numbered">
                @foreach ($data->child as $child)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $child->varian->name_varian }}</div>
                            {{ $child->varian->sku_varian }}<br>
                            {{ rupiah($child->unit_price) }}
                            @if ($child->pph)
                                <span class="badge bg-secondary rounded-pill">PPH</span>
                            @endif
                        </div>
                        <div>
                            <span class="f-14 badge bg-primary rounded-pill me-2">x
                                {{ $child->qty }}</span>
                            <span class="f-14 badge bg-light-secondary rounded-pill">
                                {{ rupiah($child->unit_price * $child->qty) }}</span>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
<div class="modal-footer p-2">
    <a href="{{ route('po.detail', $data->id) }}" target="_blank" class="btn btn-secondary">More Information <i
            class="ms-1 ph-duotone ph-arrow-square-out"></i></a>
</div>

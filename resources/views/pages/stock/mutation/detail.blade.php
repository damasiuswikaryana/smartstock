<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Detail Mutation</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="px-2">
        <div class="row g-4">
            <div class="col-md-12">
                <ol class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Type
                        </div>
                        <div class="ms-0 me-auto col-6">
                            <span class="fs-6 badge bg-light-secondary">Stock {{ $data->tipe }}</span>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Source
                        </div>
                        <div class="d-flex align-items-center ms-0 me-auto col-6">
                            @if ($data->source_id == null)
                                <span>{{ $data->source_type }}</span>
                            @else
                                <span>{{ namaLokasi($data->source_id) }}</span>
                            @endif
                            <i class="fs-5 ms-3 ti ti-arrow-bar-right"></i>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Destination
                        </div>
                        <div class="d-flex align-items-center ms-0 me-auto col-6">
                            <i class="fs-5 me-3 ti ti-arrow-bar-to-right"></i>
                            @if ($data->target_id == null)
                                {{ $data->target_id }}
                            @else
                                {{ namaLokasi($data->target_id) }}
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Entity
                        </div>
                        <div class="ms-0 me-auto col-6">
                            {{ $data->entitas->entitas_name }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Item
                        </div>
                        <div class="ms-0 me-auto fw-bold col-6">
                            {{ $data->item_varian->name_varian }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Quantity
                        </div>
                        <div class="ms-0 me-auto fw-bold col-6">
                            {{ $data->jumlah }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Note
                        </div>
                        <div class="ms-0 me-auto col-6">
                            {{ $data->keterangan }}
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div class="ms-0 me-auto col-6">
                            Date
                        </div>
                        <div class="ms-0 me-auto col-6">
                            {{ tanggalIndoWaktuLidgkap($data->created_at) }}
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>

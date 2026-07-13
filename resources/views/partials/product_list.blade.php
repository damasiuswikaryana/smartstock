@forelse ($data_product as $dp)
<div class="col-4">
    <div class="card product-card" data-id="{{ $dp->id }}">
        <div class="card-img-top">
            @if ($dp->photo)
                <img src="{{ asset('storage/produk/'.$dp->photo) }}" class="img-prod img-fluid" />
            @else
                <img src="{{ asset('storage/produk/img_1.jpg') }}" class="img-prod img-fluid">
            @endif
        </div>
        <div class="card-body">
            <p class="prod-content mb-0 text-muted">{{ $dp->nama }}</p>
            <small class="text-muted">{{ $dp->category->title ?? '-' }}</small>
            <div class="d-flex align-items-center justify-content-between mt-2 mb-3 flex-wrap gap-1">
                <h4 class="mb-0 text-truncate"><b>{{ rupiah($dp->harga) }}</b></h4>
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12 text-center text-muted py-4">
    Produk tidak ditemukan
</div>
@endforelse
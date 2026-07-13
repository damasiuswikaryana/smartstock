@extends('layouts.main')

@push('css')
<style>
  .product-card { cursor: pointer; }
</style>
@endpush

@section('content')
    <x-page-header title="Edit Transaksi Outlet" module="Edit Transaksi - {{ $data->id }}" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Outlet</li>
        <li class="breadcrumb-item"><a href="{{ route('transaction.outlet.listToday', $data_outlet->id) }}">List Transaksi</a></li>
    </x-page-header>
    
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row">
                @foreach ($data_product as $dp)
                <div class="col-4">
                    <div class="card product-card" data-id="{{ $dp->id }}">
                      <div class="card-img-top">
                          @if ($dp->photo != null)
                          <img src="{{ asset('storage/produk/'.$dp->photo) }}" alt="{{ $dp->nama }}" class="img-prod img-fluid" />
                          @else
                          <img src="{{ asset('storage/produk/img_1.jpg') }}" alt="{{ $dp->nama }}" class="img-prod img-fluid">
                          @endif
                      </div>
                      <div class="card-body">
                        <p class="prod-content mb-0 text-muted">{{ $dp->nama }}</p>
                        <small class="text-muted">{{ $dp->category->title }}</small>
                        <div class="d-flex align-items-center justify-content-between mt-2 mb-3 flex-wrap gap-1">
                          <h4 class="mb-0 text-truncate"><b>{{ rupiah($dp->harga) }}</b></h4>
                        </div>
                      </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <form action="{{ route('transaction.outlet.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card">
              <div class="card-header bg-secondary">
                <h4 class="mb-0 text-white">Daftar Pesanan</h4>
              </div>   
              <ul class="list-group list-group-flush border-top-0" id="cart-list">
                <li class="list-group-item p-2">
                  <div class="d-block">
                    <div class="form-floating mb-0">
                        <input type="text" class="form-control" required id="namaCustomer" name="namaCustomer" placeholder="Nama Customer" style="font-size: 20px; font-weight: bold;" value="{{ $data->trans_outlet_nama }}">
                        <label for="namaCustomer">Nama Customer</label>
                    </div>
                  </div>
                </li>
                @if (session('cart') && count(session('cart')) > 0)
                    @php 
                    $total = 0; 
                    @endphp
                    @foreach (session('cart') as $id => $item)
                    @php $subtotal = $item['product_price'] * $item['product_quantity']; @endphp
                    <li class="list-group-item py-2 cart-item" data-id="{{ $id }}">
                      <div class="d-flex align-items-start">
                        <div class="flex-grow-1 me-2">
                          <h6 class="mb-0">{{ $item['product_name'] }}</h6>
                          <p class="my-1 quantity-text">{{ rupiah($item['product_price']) }} x ({{ $item['product_quantity'] }})</p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="minus avtar avtar-s btn-link-secondary" data-id="{{ $id }}">
                                <i class="fas fa-minus-circle f-18 text-danger"></i>
                            </a>
                        </div>
                      </div>
                    </li>
                    @php $total += $subtotal; @endphp
                    @endforeach
                @endif
                <li class="list-group-item">
                  <div class="d-flex align-items-start">
                    <div class="flex-grow-1 me-2">
                      <h6 class="mb-0">Total Pembayaran</h6>
                      <h3 class="my-1" id="cart-total-label">{{ rupiah($total ?? 0) }}</h3>
                      <input type="hidden" id="cart-total-input" name="totalTrans" value="{{ $total ?? 0 }}">
                      <p id="kembali" class="mb-0"></p>
                    </div>
                  </div>
                </li>
                <li class="list-group-item p-2">
                  <div class="d-block">
                    <div class="form-floating mb-0">
                        <input type="text" class="form-control" required id="keterangan" name="keterangan" placeholder="Keterangan" value="{{ $data->trans_keterangan }}">
                        <label for="keterangan">Keterangan</label>
                    </div>
                  </div>
                </li>
                <li class="list-group-item p-2">
                  <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                      <div class="input-group">
                        <div class="input-group-text">
                          <input class="form-check-input" type="radio" value="Cash" name="method" aria-label="Radio button for following text input" id="cash" @if ($data->trans_outlet_metode == "Cash") checked @endif>
                        </div>
                        <input id="input-cash" name="bayar" type="text" class="form-control number-separator" placeholder="Bayar Cash"
                        @if ($data->trans_outlet_metode == "Cash") value="{{ pecahTanpaRp($data->trans_outlet_bayar) }}" @endif>
                      </div>    
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-3">
                  <div class="d-flex align-items-start">
                    <div class="flex-grow-1 me-2">
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="radio" name="method" value="Qris" id="qris" @if ($data->trans_outlet_metode == "Qris") checked @endif>
                        <label class="form-check-label w-100" for="qris"> Bayar Qris </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item p-2">
                  <div class="d-flex align-items-start">
                    <button type="submit" class="btn btn-primary w-100">Edit Transaksi</button>
                  </div>
                </li>
              </ul>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.product-card').on('click', function() {
                var productId = $(this).data('id');
                var url = "{{ route('transaction.outlet.cart.add', ':id') }}".replace(':id', productId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: 1 // default quantity
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        hideLoader();
                        showToastSuccess(res.message);
                        if (res.success) {
                            let $item = $('.cart-item[data-id="' + productId + '"]');
                            if ($item.length === 0) {
                                let newItem = `
                                    <li class="list-group-item py-2 cart-item" data-id="${productId}">
                                      <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                          <h6 class="mb-0">${res.product_name}</h6>
                                          <p class="my-1 quantity-text">${res.product_price} x (1)</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                          <a href="javascript:void(0);" class="minus avtar avtar-s btn-link-secondary" data-id="${productId}">
                                            <i class="fas fa-minus-circle f-18 text-danger"></i>
                                          </a>
                                        </div>
                                      </div>
                                    </li>
                                `;
                                let $lastItem = $('.cart-item').last();
                                if ($lastItem.length > 0) {
                                    $lastItem.after(newItem);
                                } else {
                                    $('#namaCustomer').closest('li').after(newItem);
                                }
                            } else {
                                $item.find('p.my-1').text(`${res.product_price} x (${res.new_quantity})`);
                            }
                            // Update total
                            $('#cart-total-label').text(res.total);
                            let formatted       = res.total;
                            let clean           = formatted.replace(/[^0-9]/g, '');
                            let numericValue    = parseInt(clean);
                            $('#cart-total-input').val(numericValue);
                        } else {
                            hideLoader();
                            showToastError("Hasil berbeda");
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        showToastError("Gagal menambahkan produk");
                    }
                });
            });
            
            $(document).on('click', '.minus', function (e) {
                e.preventDefault();
                let productId = $(this).data('id');
                let $item = $('.cart-item[data-id="' + productId + '"]');
            
                $.ajax({
                    url: "{{ route('transaction.outlet.cart.update') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: productId,
                        action: 'decrease'
                    },
                    beforeSend: showLoader(),
                    success: function (res) {
                        hideLoader();
                        if (res.removed) {
                            $item.remove(); // hilangkan dari DOM
                        } else if (res.updated) {
                            $item.find('.quantity-text').text(res.updated.price + ' x (' + res.updated.quantity + ')');
                        }
                        $('#cart-total-label').text(res.total);
                        let formatted       = res.total;
                        let clean           = formatted.replace(/[^0-9]/g, '');
                        let numericValue    = parseInt(clean);
                        $('#cart-total-input').val(numericValue);
                        
                    },
                    error: function () {
                        hideLoader();
                        alert('Gagal');
                    }
                });
            });
        });
        $('#input-cash').on('click', function() {
            $('#cash').prop('checked', true);
            $('#kembali').show();
        });
        $('#qris').on('click', function() {
            $('#kembali').hide();
            $('#input-cash').val('');
        });
        $('#input-cash').on('input', function() {
            var uangCashStr = $(this).val().replace(/\./g, '');
            var uangCash = parseFloat(uangCashStr) || 0;
            var totalTransaksi = parseFloat($('#cart-total-input').val()) || 0;
            var kembalian = uangCash - totalTransaksi;
            if (kembalian < 0) {
                $('#kembali').text('Uang kurang Rp ' + Math.abs(kembalian).toLocaleString('id-ID'));
            } else if (kembalian == 0) {
                $('#kembali').text('Uang pas. Makasih ya!');
            } else {
                $('#kembali').text('Kembali: Rp ' + kembalian.toLocaleString('id-ID'));
            }
        });
    </script>
    
@endpush

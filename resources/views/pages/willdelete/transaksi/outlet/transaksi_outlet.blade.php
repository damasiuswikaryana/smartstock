@extends('layouts.main')

@push('css')
<style>
  .product-card { cursor: pointer; }
  .search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #999;
    pointer-events: none; /* supaya klik tetap ke input */
  }
</style>
@endpush

@section('content')
    <x-page-header title="Transaksi Outlet Baru" module="{{ $data_outlet->nama }} - {{ tanggalIndo(date('Y-m-d')) }}" >
        <li class="breadcrumb-item">Transaksi</li>
        <li class="breadcrumb-item">Transaksi Outlet</li>
        <li class="breadcrumb-item"><a href="{{ route('transaction.outlet.listToday', $data_outlet->id) }}">List Transaksi</a></li>
    </x-page-header>
    
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="row" id="product_container">
                @include('partials.product_list', ['data_product' => $data_product])
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <form action="{{ route('transaction.outlet.store', $data_outlet->id) }}" method="POST">
            @csrf
            @method('POST')
            <div class="card">
                <div class="card-header py-3 px-4">
                    <h4 class="card-title mb-0">Filter Produk</h4>
                </div>
                <div class="card-body p-3">
                    <div class="mb-2 position-relative">
                      <input type="text" placeholder="Cari produk berdasarkan nama ..." class="form-control" id="filter_nama" style="padding-left: 35px;" />
                      <i class="ph-duotone ph-magnifying-glass search-icon"></i>
                    </div>
                    <div class="">
                      <select class="form-control select2" id="filter_category">
                          <option value="">Semua Kategori</option>
                          @foreach($data_category as $c)
                          <option value="{{ $c->id }}">{{ $c->title }}</option>
                          @endforeach
                      </select>
                    </div>
                </div>
            </div>
            <div class="card">
              <div class="card-header bg-secondary">
                <h4 class="mb-0 text-white">Daftar Pesanan</h4>
              </div>
              <ul class="list-group list-group-flush border-top-0" id="cart-list">
                <li class="list-group-item p-2">
                  <div class="d-block">
                    <div class="form-floating mb-0">
                        <input type="text" class="form-control" required id="namaCustomer" name="namaCustomer" placeholder="Nama Customer" style="font-size: 20px; font-weight: bold;">
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
                        <input type="text" class="form-control" required id="keterangan" name="keterangan" placeholder="Keterangan">
                        <label for="keterangan">Keterangan</label>
                    </div>
                  </div>
                </li>
                <li class="list-group-item p-2">
                  <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                      <div class="input-group">
                        <div class="input-group-text">
                          <input class="form-check-input" type="radio" value="Cash" name="method" aria-label="Radio button for following text input" id="cash">
                        </div>
                        <input id="input-cash" name="bayar" type="text" class="form-control number-separator" placeholder="Bayar Cash">
                      </div>    
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-3">
                  <div class="d-flex align-items-start">
                    <div class="flex-grow-1 me-2">
                      <div class="form-check mb-0">
                        <input class="form-check-input" type="radio" name="method" value="Qris" id="qris">
                        <label class="form-check-label w-100" for="qris"> Bayar Qris </label>
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item p-2">
                  <div class="d-flex align-items-start">
                    <button type="submit" class="btn btn-danger w-100">Submit Transaksi</button>
                  </div>
                </li>
              </ul>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.product-card', function () {
                var productId = $(this).data('id');
                var url = "{{ route('transaction.outlet.cart.add', ':id') }}".replace(':id', productId);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: 1 // default quantity
                    },
                    beforeSend: function() {
                        showLoader();
                    },
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
                    beforeSend: function() {
                        showLoader();
                    },
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
        // load product
        function loadProduct() {
            $.ajax({
                url: "{{ route('transaction.outlet.get.product') }}",
                type: "GET",
                data: {
                    filter_nama: $('#filter_nama').val(),
                    filter_category: $('#filter_category').val()
                },
                beforeSend: function() {
                    showLoader();
                },
                success: function(res) {
                    $('#product_container').html(res);
                },
                error: function() {
                    $('#product_container').html(`
                        <div class="col-12 text-center text-danger py-4">
                            Gagal memuat produk
                        </div>
                    `);
                },
                complete: function() {
                    hideLoader();
                }
            });
        }
        $('#filter_nama').on('keyup', debounce(loadProduct, 400));
        $('#filter_category').on('change', loadProduct);
        // Anti spam query
        function debounce(func, delay) {
            let timer;
            return function () {
                clearTimeout(timer);
                timer = setTimeout(func, delay);
            };
        }
    </script>
    
@endpush

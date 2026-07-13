<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />

<div class="modal-header">
    <h5 class="modal-title" id="modalEditTitle">Edit Event</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form class="modal-body" action="{{ route('transaction.event.update', $data->id) }}" method="post" id="form-edit">
    <div class="px-4">
        @csrf
        @method('POST')
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Outlet:</label>
            <div class="col-lg-8">
                <select class="form-control" name="id_outlet" id="id_outlet">
                    <option value="" disabled>Pilih Outlet</option>
                    @foreach ($outletData as $outlet)
                    <option @if ($outlet->id == $data->id_outlet) selected @endif value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">No. Invoice:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan nomor invoice" name="no_invoice" value="{{ $data->no_invoice }}" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Nama Event:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan nama event" name="event_nama" value="{{ $data->event_nama }}" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Lokasi Event:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan lokasi event" name="event_lokasi" value="{{ $data->event_lokasi }}" required>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Tanggal Event:</label>
            <div class="col-lg-8 row pe-0">
                <div class="col-12 col-lg-4">
                    <input type="text" class="form-control" placeholder="Pilih Tanggal" id="event_date_edit" name="event_date" value="{{ converttanggal($data->event_date) }}" required />
                </div>
                <div class="col-12 col-lg-4 mx-0">
                    <input type="time" class="form-control" placeholder="Waktu awal event" id="waktu_awal" name="waktu_awal" value="{{ $data->event_waktu_awal }}" />
                </div>
                <div class="col-12 col-lg-4 mx-0 pe-0">
                    <input type="time" class="form-control" placeholder="Waktu akhir event" id="waktu_akhir" name="waktu_akhir" value="{{ $data->event_waktu_akhir }}" />
                </div>
            </div>
            <div class="col-lg-8">
                
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Tenggat Waktu Invoice:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Pilih Tanggal" id="due_date_edit" name="due_date" value="{{ converttanggal($data->due_date) }}" required />
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Customer:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select class="form-control" name="id_customer" id="id_customer_edit" required>
                        <option value="" selected disabled>Pilih Customer</option>
                        @foreach ($customer as $cus)
                        <option {{ $cus->id == $data->id_customer ? 'selected' : '' }} data-company="{{ $cus->company }}" value="{{ $cus->id }}">{{ $cus->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-6 mx-0 pe-0">
                    <input type="text" class="form-control" placeholder="Nama Perusahaan" id="company_edit" name="company" value="{{ $data->company }}" required />
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Discount:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select class="form-control" name="discount" id="discount_edit" required>
                        <option {{ $data->discount != null ? 'selected' : '' }} value="percent">Percent</option>
                        <option {{ $data->discount_amount != null ? 'selected' : '' }} value="flat">Flat</option>
                        <option {{ $data->discount_amount == null && $data->discount == null ? 'selected' : '' }} value="no">Tidak Menggunakan Discount</option>
                    </select>
                </div>
                <div class="col-12 col-lg-6 mx-0 pe-0">
                    <input type="text" class="form-control" placeholder="Jumlah Diskon" id="discount_value_edit" name="discount_value" 
                        @if ($data->discount != null)
                        value="{{ $data->discount }}"
                        @elseif ($data->discount_amount != null)
                        value="{{ $data->discount_amount }}"
                        @else
                        value=""
                        @endif
                    />
                    <small id="taxes_value_editHelp" class="form-text text-danger">Kosongkan jika tidak ada diskon.</small>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Taxes:</label>
            <div class="col-lg-8 row">
                <div class="col-12 col-lg-6">
                    <select class="form-control" name="taxes" id="taxes_edit" required>
                        <option {{ $data->taxes != null ? 'selected' : '' }} value="percent">Percent</option>
                        <option {{ $data->taxes_amount != null ? 'selected' : '' }} value="flat">Flat</option>
                        <option {{ $data->taxes_amount == null && $data->taxes == null ? 'selected' : '' }} value="no">Tidak Menggunakan Pajak</option>
                    </select>
                </div>
                <div class="col-12 col-lg-6 mx-0 pe-0">
                    <input type="text" class="form-control" placeholder="Nominal Pajak" id="taxes_value_edit" name="taxes_value"
                        @if ($data->taxes != null)
                        value="{{ $data->taxes }}"
                        @elseif ($data->taxes_amount != null)
                        value="{{ $data->taxes_amount }}"
                        @else
                        value=""
                        @endif
                    />
                    <small id="taxes_value_editHelp" class="form-text text-danger">Kosongkan jika tidak ada biaya pajak.</small>
                </div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Nomor Rekening:</label>
            <div class="col-lg-8">
                <select class="form-control" name="id_bank_account">
                    @foreach ($bankAccounts as $ba)
                    <option {{ $ba->id == $data->id_bank_account ? 'selected' : '' }} value="{{ $ba->id }}">{{ $ba->bank->bank_name.': '.$ba->bank_account_name.' - '.$ba->bank_account_number }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-lg-4 col-form-label">Catatan:</label>
            <div class="col-lg-8">
                <input type="text" class="form-control" placeholder="Masukan catatan" name="event_note" value="{{ $data->event_note }}">
            </div>
        </div>
        <div class="mb-0 row">
            <label class="col-lg-4 col-form-label">Produk:</label>
            <div class="col-lg-8">
                <div id="produk-container-edit">
                    @foreach ($data->eventProduct as $key => $transProd)
                    <div class="row p-0 mx-0 mb-2 produk-item">
                        <div class="col-6 col-lg-6 ps-0">
                            <select class="form-control" name="produk[{{ $key }}][id_product]" required>
                                <option value="" selected disabled>Pilih Produk</option>
                                @foreach ($product as $p)
                                    <option {{ $transProd->id_product == $p->id ? 'selected' : '' }} value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-5 col-lg-5 mx-0 pe-0">
                            <input type="number" class="form-control" placeholder="Jumlah produk" name="produk[{{ $key }}][jumlah]" value="{{ $transProd->jumlah }}" required />
                        </div>
                        <div class="col-1 col-lg-1 mx-0 pe-0">
                            <button id="btn-delete-{{ $key }}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row mb-0 p-2">
                    <a href="#" id="btn-add-product-edit" class="btn btn-light-success w-100 d-flex justify-content-center align-items-center">
                        <i class="fa fa-plus-circle me-2"></i> 
                        <span>Tambah Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal-footer p-2">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary" form="form-edit">Update Data</button>
</div>

<script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script>
    function formatDateToYMD(dateString) {
        const parts = dateString.split('/'); // ["06", "11", "2025"]
        const month = parts[0];
        const day = parts[1];
        const year = parts[2];
        return `${year}-${month}-${day}`; // "2025-11-06"
    }
    
    (function () {
      const d_week = new Datepicker(document.querySelector('#event_date_edit'), {
        buttonClass: 'btn',
        autohide: true,
      });
      const d_due = new Datepicker(document.querySelector('#due_date_edit'), {
        buttonClass: 'btn',
        autohide: true,
      });
    })();
    
    $(document).ready(function () {
        $('#id_customer_edit').on('change', function () {
            var selectedOption = $(this).find('option:selected');
            var companyName = selectedOption.data('company') || '';
            $('#company_edit').val(companyName);
        });
        
        $(document).on('click', '.btn-delete-produk', function() {
            $(this).closest('.produk-item').remove();
        });
        
        let produkIndexEdit = {{ $data->eventProduct->count() }};
        $('#btn-add-product-edit').on('click', function (e) {
            e.preventDefault();
            let html = `
            <div class="row p-0 mx-0 mb-2 produk-item">
                <div class="col-6 col-lg-6 ps-0">
                    <select class="form-control" name="produk[${produkIndexEdit}][id_product]" required>
                        <option value="" selected disabled>Pilih Produk</option>
                        @foreach ($product as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-5 col-lg-5 mx-0 pe-0">
                    <input type="number" class="form-control" placeholder="Jumlah produk" name="produk[${produkIndexEdit}][jumlah]" required />
                </div>
                <div class="col-1 col-lg-1 mx-0 pe-0">
                    <button id="btn-delete-${produkIndexEdit}" type="button" class="btn btn-rounded btn-light-danger btn-delete-produk" style="font-size:20px;">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>
            `;
            $('#produk-container-edit').append(html);
            produkIndexEdit++;
        });
        $('#form-edit').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);
            let formData = form.serialize();
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    showLoader();
                },
                success: function (res) {
                    if (res.success) {
                        hideLoader();
                        $('#modalEdit').modal('hide');
                        table.ajax.reload(null, false);
                        showToastSuccess(res.message);
                        $('#produk-container').empty();
                        produkIndex = 0;
                    } else {
                        hideLoader();
                        showToastError("Error:" + res.message);
                        console.log(res.message);
                    }
                },
                error: function (xhr) {
                    hideLoader();
                    showToastError("Error:" + xhr.responseText);
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
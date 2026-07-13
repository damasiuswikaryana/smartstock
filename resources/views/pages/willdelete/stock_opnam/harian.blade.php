@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Stock Opnam Harian" module="Management Stock Opnam" >
        <li class="breadcrumb-item">Stock Opnam</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-8 d-flex">
            @if (auth()->user()->is_admin)
            <div class="col-4 me-2">          
                <div class="input-group date">
                  <select id="selectOutlet" class="mb-0 form-select" style="padding-top: 0.62rem; padding-bottom: 0.62rem;">
                    @foreach ($data_outlet_all as $outlet)    
                    <option {{ $outlet->id == $data_outlet->id ? 'selected' : '' }} value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                    @endforeach
                  </select>  
                </div>
            </div>
            @endif
            <div class="col-4 me-2">
                <div class="input-group date">
                    <input type="text" class="form-control" placeholder="Pilih Tanggal" value="{{ converttanggal($tanggal) }}" id="date_transHarian" style="padding-top: 0.62rem; padding-bottom: 0.62rem;" />
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <div class="col-3 me-2">
                <a href="{{ route('stock-opnam.harian.ekspor', $tanggal) }}" class="btn btn-shadow btn-success w-100" data-bs-toggle="tooltip" title="Download ke Excel">
                    <i class="ph-duotone ph-download-simple icon-search"></i> Download Excel
                </a>
            </div>
            <div class="col-1">
                <a href="{{ route('stock-opnam.harian.add_stockOpnam') }}" class="btn btn-shadow btn-secondary" data-bs-toggle="tooltip" title="Tambah Stock Opnam">
                    <i class="ph-duotone ph-plus icon-search"></i>
                </a>
            </div>
        </div>
        <div class="col-4 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
            </div>
        </div>
    </div>

    @php
        $thead = ['No', 'Tanggal', 'Item', 'Jumlah & Satuan', 'Terpakai', 'Petugas'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
    <script type="text/javascript">
        $('#selectOutlet').on('change', function () {
            showLoader();
            const outletId  = $(this).val();
            const url       = new URL(window.location.href);
            url.searchParams.set('outlet', outletId);
            setTimeout(function () {
                window.location.href = url.toString();
            }, 1000);
        });
        
        function formatDateToYMD(dateString) {
            const parts = dateString.split('/'); // ["06", "11", "2025"]
            const month = parts[0];
            const day = parts[1];
            const year = parts[2];
            return `${year}-${month}-${day}`; // "2025-11-06"
        }
        
        (function () {
          const d_week = new Datepicker(document.querySelector('#date_transHarian'), {
            buttonClass: 'btn',
            autohide: true,
          });
        })();
        
        $('#date_transHarian').on('changeDate', function () {
            const selectedDate = $(this).val(); // Format: YYYY-MM-DD
            const formatedSelectedDate = formatDateToYMD(selectedDate);
            if (selectedDate) {
                // const ido = "";
                var route = "{{ route('stock-opnam.harian.index', ':tgl') }}";
                route = route.replace(':tgl', formatedSelectedDate);
                showLoader(),
                window.location.href = route;
            }
        });
        
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : "{{ route('stock-opnam.harian.index', $tanggal) }}?outlet={{ request('outlet') }}",
                data: { type: 'harian' }
            },
            scrollY: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
              leftColumns: 1,
              rightColumns: 1
            },
            lengthMenu: [30, 60, 90, 120, 150],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
            columns: [
                { data: null, name: 'no', class: 'text-center', orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'tanggal',      name: 'tanggal', class: 'text-start' },
                { data: 'item',         name: 'item', class: 'text-start' },
                { data: 'jumsat',       name: 'jumsat', class: 'text-center' },
                { data: 'terpakai',     name: 'terpakai', class: 'text-center' },
                { data: 'petugas',      name: 'petugas', class: 'text-start' },
            ]
        });

        $('#search').keyup(function() {
            table.search($(this).val()).draw();
        });
        
        table.on('draw', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipTriggerList1 = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            tooltipTriggerList1.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

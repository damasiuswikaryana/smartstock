@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />
@endpush

@section('content')
    <x-page-header title="Management Shift" module="Management Shift" >
        <li class="breadcrumb-item">Management</li>
        <li class="breadcrumb-item">Shift</li>
    </x-page-header>
    
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div class="col-6" id="btn-shift">
            @if ($buka_shift)
            <button type="button" class="btn btn-shadow btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="ph-duotone ph-sign-in icon-search me-2"></i> Buka Shift Baru</button>
            @else
            <button type="button" class="btn btn-shadow btn-danger me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalOut"><i class="ph-duotone ph-sign-out icon-search me-2"></i> Tutup Shift</button>
            @endif
        </div>
        <div class="col-6 text-end">
            <div class="form-search">
                <i class="ph-duotone ph-magnifying-glass icon-search"></i>
                <input type="search" id="search" class="form-control" placeholder="Cari data disini...">
            </div>
        </div>
    </div>
    
    @php
        $thead = ['No', 'Nama Karyawan', 'Outlet', 'Tanggal', 'Shift', 'Buka Shift', 'Tutup Shift', 'Aksi'];
    @endphp
    <x-datatable :thead=$thead :filter="null">
    </x-datatable>

    <div id="exampleModalCenter" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalCenterTitle">Mulai Shift di {{ auth()->user()->lokasi->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="post" id="form-in">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="px-4">
                            <input type="hidden" name="id_outlet" value="{{ auth()->user()->loc_id }}" />
                            <div class="col-12 text-center mb-3">
                                <h1 id="jam"></h1>
                            </div>
                            <div class="col-12 text-center">
                                <h3>Halo, {{ auth()->user()->firstname }} {{ auth()->user()->lastname }} !</h3>
                                <h4 class="text-muted">{{ auth()->user()->lokasi->nama }}</h4>
                                <p>Saatnya kamu mulai shift hari ini. Jangan lupa semangat dan senyum yah! ☺️</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="submit" class="w-100 btn btn-primary" form="form-in">Buka Shift Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div id="modalOut" class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modalOutTitle">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalCenterTitle">Akhiri Shift di {{ auth()->user()->lokasi->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" action="#" method="post" id="form-out">
                    <div class="px-4">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="id_outlet" value="{{ auth()->user()->loc_id }}" />
                        <div class="col-12 text-center mb-3">
                            <h1 id="jam"></h1>
                        </div>
                        <div class="col-12 text-center">
                            <h3>Halo, {{ auth()->user()->firstname }} {{ auth()->user()->lastname }} !</h3>
                            <h4 class="text-muted">{{ auth()->user()->lokasi->nama }}</h4>
                            <p>Pastikan kamu sudah input stock opnam dan kegiatan hari ini ya! ☺️</p>
                        </div>
                        <div class="col-12 mt-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Kegiatan</th>
                                        <th class="fw-bold">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Input Pemasukan</td>
                                        <td class="d-flex justify-content-between align-items-center">
                                            @if ($sudahInputPemasukan)
                                            <div><i class="fa fa-check text-success"></i> Sudah</div>
                                            @else
                                            <div><i class="fa fa-times text-danger"></i> Belum</div>
                                            <a href="{{ route('endshift.index') }}" class="btn btn-danger">Input Sekarang</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($tampilChecklist)
                                    <tr>
                                        <td>Checklist Tutup Toko</td>
                                        <td class="d-flex justify-content-between align-items-center">
                                            @if ($sudahInputChecklist)
                                            <div><i class="fa fa-check text-success"></i> Sudah</div>
                                            @else
                                            <div><i class="fa fa-times text-danger"></i> Belum</div>
                                            <a href="{{ route('endshift.index') }}" class="btn btn-danger">Input Sekarang</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
                <div class="modal-footer p-2">
                    <button type="submit" class="w-100 btn btn-danger" form="form-out"
                    {{ $sudahInputPemasukan && $sudahInputChecklist ? '' : 'disabled' }}
                    >Tutup Shift Sekarang</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        function updateJam() {
            const now = new Date();
            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');
            $('#jam').text(`${jam}:${menit}:${detik}`);
        }
        
        $(document).ready(function() {
            updateJam();
            setInterval(updateJam, 1000);
        });
        
        let table = $('#myTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('shift.index') }}",
            scrollY: '300px',
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
              leftColumns: 1,
              rightColumns: 1
            },
            lengthMenu: [10, 20, 30, 40, 50, 100],
            "dom": '<"my-0"t><"d-flex justify-content-between align-items-center mx-3 mb-2"<"d-flex justify-content-start mx-2" <"me-2 pt-2"l>><"pt-2"p>>',
            order: [
                [0, 'asc']
            ],
            columns: [
                { data: null, name: 'no',  orderable: false, searchable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama',         name: 'nama', },
                { data: 'outlet',       name: 'outlet', class: 'text-start', },
                { data: 'tanggal',      name: 'tanggal', class: 'text-start', },
                { data: 'shift',        name: 'shift', class: 'text-center', },
                { data: 'in',           name: 'in', class: 'text-center', },
                { data: 'out',          name: 'out', class: 'text-center', },
                { data: 'action',       name: 'action', class: 'text-center', orderable: false, searchable: false, },
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
        
        $('#form-in').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: '{{ route("shift.in") }}', // Route untuk simpan data
              method: 'POST',
              data: $(this).serialize(),
              beforeSend: showLoader(),
              success: function(response) {
                if (response.success) {
                    $('#form-in')[0].reset();
                    table.ajax.reload(null, false);
                    hideLoader();
                    $('#exampleModalCenter').modal('hide');
                    showToastSuccess("Data berhasil ditambahkan");
                    $("#btn-shift").html(`
                        <button type="button" class="btn btn-shadow btn-danger me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalOut"><i class="ph-duotone ph-sign-out icon-search me-2"></i> Tutup Shift</button>
                    `);
                } else {
                    hideLoader();
                    showToastError(response.message);
                }
              },
              error: function(xhr) {
                hideLoader();
                showToastError("Gagal menambahkan data");
              }
            });
        });
        
        $('#form-out').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
              url: '{{ route("shift.out") }}', // Route untuk simpan data
              method: 'POST',
              data: $(this).serialize(),
              beforeSend: showLoader(),
              success: function(response) {
                if (response.success) {
                    $('#form-out')[0].reset();
                    table.ajax.reload(null, false);
                    hideLoader();
                    $('#modalOut').modal('hide');
                    showToastSuccess("Berhasil clock out shift");
                    $("#btn-shift").html(`
                        <button type="button" class="btn btn-shadow btn-warning me-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"><i class="ph-duotone ph-sign-in icon-search me-2"></i> Buka Shift Baru</button>
                    `);
                } else {
                    hideLoader();
                    showToastError(response.message);
                }
              },
              error: function(xhr) {
                hideLoader();
                showToastError("Gagal clock out shift");
              }
            });
        });
    </script>
    
@endpush

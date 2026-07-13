@push('css')
    <link href="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.3/b-3.1.1/b-html5-3.1.1/fc-5.0.1/fh-4.0.1/r-3.0.2/sl-2.0.4/datatables.min.css" rel="stylesheet">
    <style>
        .table td,
        .table th {
            vertical-align: middle !important;
        }
    </style>
@endpush

<div class="card mb-0" style="">
    @if ($filter != null)
    <div class="card-header d-flex justify-content-between align-items-center p-3">
        <div class="d-flex gap-2 align-items-center">
            {{ $filter }}
        </div>
    </div>
    @endif

    <div class="card-body" style="padding: 0 !important; ">
        <div class="table-responsive">
            <table class="table table-hover border-bottom table-sm mb-0 data-table" id="{{ $id ?? 'myTable' }}">
                <thead>
                    <tr>
                        @foreach ($thead as $item)
                            <th class="text-center">{{ $item }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    {{ $slot }}
                </tbody>
                <tfoot>
                    <tr>
                        @foreach ($thead as $th)
                            <th></th>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-2.1.3/b-3.1.1/b-html5-3.1.1/fc-5.0.1/fh-4.0.1/r-3.0.2/sl-2.0.4/datatables.min.js"></script>
@endpush

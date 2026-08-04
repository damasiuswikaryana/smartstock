@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fixedColumns.bootstrap5.min.css') }}" />

    <style>
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .big-checkbox {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .big-label {
            font-size: 1.2rem;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <x-page-header title="Roles Management" module="Roles Management">
        <li class="breadcrumb-item">Master Data</li>
        <li class="breadcrumb-item">Roles</li>
    </x-page-header>

    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="card ">
                <div class="card-body">
                    <form action="{{ route('roles.index') }}" method="get" id="form-role">
                        <div class="mb-3 row">
                            <div class="col-lg-12 me-0 pe-0">
                                <h5>Role</h5>
                                <select id="role_name" name="role_name" class="form-select select2">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ $role_name == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <form action="#" method="post" id="form-edit">
                        @csrf
                        @method('POST')
                        <div class="row">
                            @foreach ($menus = \App\Helpers\MenuHeader::getMenuWithoutRole() as $item)
                                @if (isset($item['children']))
                                    <div class="col-12">
                                        <div class="checkbox-group">
                                            <input type="checkbox" class="big-checkbox menu-checkbox"
                                                value="{{ $item['permission'] }}" name="menu[]" autocomplete="off"
                                                {{ $this_role->hasPermissionTo($item['permission']) ? 'checked' : '' }}>

                                            <label class="big-label">{{ $item['title'] }}</label>
                                        </div>
                                        @foreach ($item['children'] as $subchild)
                                            <div class="checkbox-group">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="checkbox" class="big-checkbox menu-checkbox"
                                                    value="{{ $subchild['permission'] }}" name="menu[]" autocomplete="off"
                                                    {{ $this_role->hasPermissionTo($subchild['permission']) ? 'checked' : '' }}>

                                                <label class="big-label">{{ $subchild['title'] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="checkbox-group">
                                            <input type="checkbox" class="big-checkbox menu-checkbox"
                                                value="{{ $item['permission'] }}" name="menu[]" autocomplete="off"
                                                {{ $this_role->hasPermissionTo($item['permission']) ? 'checked' : '' }}>

                                            <label class="big-label">{{ $item['title'] }}</label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                </div>
                <div class="card-footer">
                    <button type="button" id="btn-checkall" class="btn btn-secondary">Check All</button>
                    <button type="submit" class="btn btn-primary float-end" form="form-edit">Save Changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/plugins/dataTables.fixedColumns.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#role_name').on('change', function() {
                $('#form-role').submit();
            });

            let allChecked = false; // status awal

            $("#btn-checkall").on("click", function() {
                allChecked = !allChecked;
                $(".menu-checkbox").prop("checked", allChecked);
                $(this).text(allChecked ? "Uncheck All" : "Check All");
            });

        });


        $('#form-edit').on('submit', function(e) {
            e.preventDefault();
            const role_name = "{{ $this_role->name }}";
            var url = "{{ route('roles.rolesUpdate', ':role_name:') }}";
            var url = url.replace(':role_name:', role_name);

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: showLoader(),
                success: function(res) {
                    hideLoader();
                    showToastSuccess("Success update roles");
                },
                error: function() {
                    hideLoader();
                    showToastError("Error while updating data.");
                }
            });
        });
    </script>
@endpush

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 56px;
        display: block;
        width: 100%;
        padding: 0.7rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.25;
        color: #5b6b79;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-color: #ffffff;
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        border: 1px solid #dbe0e5;
        border-radius: 8px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 35px;
        position: absolute;
        top: 5px;
        right: 10px;
        width: 20px;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush



<select class="form-select mb-3" name="{{ $id }}" id="{{ $id }}">
    {{ $slot }}
</select>
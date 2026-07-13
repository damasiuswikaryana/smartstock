@extends('layouts.main')

@section('title')
    - Dashboard
@endsection

@push('css')
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <x-page-header title="Dashboard" module="Dashboard">
            </x-page-header>
            <p class="mb-0">asd</p>
        </div>
    </div>
@endsection

@push('js')
    <script></script>
@endpush

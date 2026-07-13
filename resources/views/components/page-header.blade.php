<div class="page-header ms-0 mb-0 p-0">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                    {{ $slot ?? '' }}
                    <li class="breadcrumb-item" aria-current="page">{{ $title }}</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">{{ $module }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

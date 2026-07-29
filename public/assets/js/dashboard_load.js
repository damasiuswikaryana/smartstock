function loadStockIn() {
    $.ajax({
        url: "/dashboard-get-stock-in",
        type: "GET",
        data: {
            warehouse_id: $("#wh_so_in").val(),
        },
        beforeSend: function () {
            $("#ct_si").addClass("d-none");
            $("#pc_si").removeClass("d-none");
        },
        success: function (response) {
            $("#val_si").text(response.stockIn);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_si").addClass("d-none");
            $("#ct_si").removeClass("d-none");
        },
    });
}

function loadStockOut() {
    $.ajax({
        url: "/dashboard-get-stock-out",
        type: "GET",
        data: {
            warehouse_id: $("#wh_so_out").val(),
        },
        beforeSend: function () {
            $("#ct_so").addClass("d-none");
            $("#pc_so").removeClass("d-none");
        },
        success: function (response) {
            $("#val_so").text(response.stockOut);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_so").addClass("d-none");
            $("#ct_so").removeClass("d-none");
        },
    });
}

function loadStockTrf() {
    $.ajax({
        url: "/dashboard-get-stock-trf",
        type: "GET",
        data: {
            warehouse_id: $("#wh_so_trf").val(),
        },
        beforeSend: function () {
            $("#ct_strf").addClass("d-none");
            $("#pc_strf").removeClass("d-none");
        },
        success: function (response) {
            $("#val_strf").text(response.stockTrf);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_strf").addClass("d-none");
            $("#ct_strf").removeClass("d-none");
        },
    });
}

function loadClients() {
    $.ajax({
        url: "/dashboard-get-clients",
        type: "GET",
        data: {
            entitas_id: $("#entitas_id").val(),
        },
        beforeSend: function () {
            $("#ct_clients").addClass("d-none");
            $("#pc_clients").removeClass("d-none");
        },
        success: function (response) {
            $("#val_clients").text(response.clients);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_clients").addClass("d-none");
            $("#ct_clients").removeClass("d-none");
        },
    });
}

function loadContractFullfillment() {
    $.ajax({
        url: "/dashboard-get-contract-fullfillment",
        type: "GET",
        beforeSend: function () {
            $("#ct_cf").addClass("d-none");
            $("#pc_cf").removeClass("d-none");
        },
        success: function (response) {
            $("#val_cf").html(
                response.completedProject +
                    '/<small class="text-muted">' +
                    response.totalProject +
                    "</small>",
            );
            $("#cf_percentage").text(response.percentage + "%");
            $("#cf_progress")
                .css("width", response.percentage + "%")
                .attr("aria-valuenow", response.percentage);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_cf").addClass("d-none");
            $("#ct_cf").removeClass("d-none");
        },
    });
}

function loadTopItems() {
    $.ajax({
        url: "/dashboard-top-items",
        type: "GET",
        data: {
            lokasi_id: $("#wh_most_item").val(),
        },
        beforeSend: function () {
            $("#ct_item_most").addClass("d-none");
            $("#pc_item_most").removeClass("d-none");
        },
        success: function (response) {
            chartTopItems.updateOptions({
                xaxis: {
                    categories: response.categories,
                },
            });
            chartTopItems.updateSeries([
                {
                    data: response.series,
                },
            ]);
            let html = "";
            response.items.forEach(function (item, index) {
                html += `
                <div class="bg-body ${index == 0 ? "mt-3" : "mt-1"} py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-start">
                        <i class="ph-duotone ph-circle f-12 me-2" style="color:${chartColors[index % chartColors.length]}"></i>
                        <div>
                            <p class="mb-0">
                                ${item.name_varian}
                            </p>
                            <p class="mb-0 mt-0 text-muted"><small>${item.sku_varian}</small></p>
                        </div>
                    </div>
                    <h5 class="mb-0 ms-1">
                        ${item.total_qty}
                    </h5>
                </div>`;
            });
            $("#top-items-detail").html(html);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_item_most").addClass("d-none");
            $("#ct_item_most").removeClass("d-none");
        },
    });
}

function loadCategories() {
    $.ajax({
        url: "/dashboard-categories",
        type: "GET",
        beforeSend: function () {
            $("#ct_categories").addClass("d-none");
            $("#pc_categories").removeClass("d-none");
        },
        success: function (response) {
            chartCategories.updateOptions({
                labels: response.categories,
                colors: chartColorsCategory.slice(
                    0,
                    response.categories.length,
                ),
            });
            chartCategories.updateSeries(response.series);
            let html = "";
            response.items.forEach(function (item, index) {
                html += `
                <div class="bg-body ${index == 0 ? "mt-3" : "mt-1"} py-2 px-3 rounded d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center justify-content-start">
                        <i class="ph-duotone ph-circle f-12 me-2" style="color:${chartColorsCategory[index % chartColorsCategory.length]}"></i>
                        <div>
                            <p class="mb-0">
                                ${item.title}
                            </p>
                        </div>
                    </div>
                    <h5 class="mb-0 ms-1">
                        ${item.total_item}
                    </h5>
                </div>`;
            });
            $("#categories_detail").html(html);
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
        },
        complete: function () {
            $("#pc_categories").addClass("d-none");
            $("#ct_categories").removeClass("d-none");
        },
    });
}

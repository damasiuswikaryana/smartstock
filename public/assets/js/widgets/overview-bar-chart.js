"use strict";
var chartTopItems;
const chartColors = [
    "#F44236", // merah
    "#04A9F5", // biru
    "#673AB7", // ungu
    "#4CAF50", // hijau
    "#FFC107", // kuning
];
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        var options_itemMost = {
            chart: { type: "bar", height: 150, sparkline: { enabled: true } },
            colors: chartColors,
            plotOptions: {
                bar: { borderRadius: 2, columnWidth: "80%", distributed: true },
            },
            series: [
                {
                    data: [],
                },
            ],
            xaxis: {
                categories: [],
            },
            tooltip: {
                fixed: { enabled: false },
                x: { show: false },
                y: {
                    title: {
                        formatter: function (seriesName) {
                            return "";
                        },
                    },
                },
                marker: { show: false },
            },
        };
        chartTopItems = new ApexCharts(
            document.querySelector("#overview-bar-chart"),
            options_itemMost,
        );
        chartTopItems.render();
        loadTopItems();
    }, 500);
});

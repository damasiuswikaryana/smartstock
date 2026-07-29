"use strict";
var chartCategories;
const chartColorsCategory = [
    "#F44236",
    "#04A9F5",
    "#673AB7",
    "#4CAF50",
    "#FFC107",
    "#FF9800",
    "#26C6DA",
    "#EC407A",
    "#8BC34A",
    "#7E57C2",
    "#FF7043",
    "#78909C",
];
document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: {
            height: 200,
            type: "donut",
        },
        series: [],
        labels: [],
        colors: chartColorsCategory,
        fill: {
            opacity: 1,
        },
        legend: {
            show: false,
        },
        plotOptions: {
            pie: {
                donut: {
                    size: "65%",
                    labels: {
                        show: true,
                        name: {
                            show: true,
                        },
                        value: {
                            show: true,
                        },
                        total: {
                            show: true,
                            label: "Total",
                        },
                    },
                },
            },
        },
        dataLabels: {
            enabled: false,
        },
        responsive: [
            {
                breakpoint: 575,
                options: {
                    chart: {
                        height: 250,
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: "65%",
                                labels: {
                                    show: false,
                                },
                            },
                        },
                    },
                },
            },
            {
                breakpoint: 1182,
                options: {
                    chart: {
                        height: 190,
                    },
                },
            },
        ],
    };
    chartCategories = new ApexCharts(
        document.querySelector("#categories-chart"),
        options,
    );
    chartCategories.render();
});

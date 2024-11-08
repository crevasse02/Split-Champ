$(document).ready(function () {
    let areaChart; // Variable for the main bar chart
    let conversionChart; // Variable for the horizontal bar chart
    let selectedRow; // Variable to keep track of the selected row

    // Function to select the first row on initial load
    function selectFirstRow() {
        const firstRow = $(".experiment-row").first();
        if (firstRow.length) {
            firstRow.addClass("selected-row");
            selectedRow = firstRow;
            const eksperimenId = firstRow.data("eksperimen-id");
            fetchData(eksperimenId);
        }
    }

    // Function to fetch data and render charts
    function fetchData(eksperimenId) {
        fetch(`/api/get-variant/${eksperimenId}`)
            .then((response) => response.json())
            .then((data) => {
                if (!data || !data.variants || data.variants.length === 0) {
                    if (areaChart) areaChart.destroy();
                    if (conversionChart) conversionChart.destroy();
                    $("#areaChart").html("<p>Data Not Found</p>");
                    $("#conversionChart").html("<p>Data Not Found</p>");
                    return;
                } else {
                    $("#areaChart").html("");
                    $("#conversionChart").html("");
                }

                // Prepare series data for the main bar chart
                const seriesData = [
                    {
                        name: "Button Clicks",
                        data: [...data.variants.map((variant) => variant.button_click || 0), null],
                    },
                    {
                        name: "Form Submits",
                        data: [...data.variants.map((variant) => variant.form_submit || 0), null],
                    },
                    {
                        name: "Slug Views (Variants)",
                        data: [...data.variants.map((variant) => variant.view || 0), null],
                    },
                    {
                        name: "Domain View Count",
                        data: Array(data.variants.length).fill(null).concat(data.experiment_view_count || 0),
                    },
                ];

                if (areaChart) areaChart.destroy();
                areaChart = new ApexCharts(document.querySelector("#areaChart"), {
                    chart: { type: "bar", toolbar: { show: false } },
                    plotOptions: { bar: { borderRadius: 10, dataLabels: { position: "top" } } },
                    series: seriesData,
                    xaxis: {
                        categories: [...data.variants.map((variant) => variant.variant_name), "Domain View Count"],
                    },
                    yaxis: { title: { text: "Count" } },
                    dataLabels: {
                        enabled: true,
                        formatter: (val) => (val ? val.toString() : ""),
                        style: { fontSize: "16px" },
                    },
                    tooltip: { y: { formatter: (val) => `${val} interactions` } },
                    legend: { position: "right" },
                });
                areaChart.render();

                // Prepare data for the horizontal bar chart showing conversion rates
                const conversionRates = data.variants.map((variant) => {
                    const viewCount = variant.view || 1;
                    const totalConversions = (variant.button_click || 0) + (variant.form_submit || 0);
                    return (totalConversions / viewCount) * 100;
                });

                if (conversionChart) conversionChart.destroy();
                conversionChart = new ApexCharts(document.querySelector("#conversionChart"), {
                    chart: {
                        type: "bar",
                        toolbar: { show: false },
                        height: '300px',
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 5,
                            dataLabels: {
                                position: "center",
                            },
                        },
                    },
                    series: [{
                        name: "Conversion Rate",
                        data: conversionRates,
                    }],
                    xaxis: {
                        categories: data.variants.map((variant) => variant.variant_name),
                        title: {
                            text: "Conversion Rate (%)",
                        },
                    },
                    yaxis: {
                        title: {
                            text: "Variants",
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: (val) => `${val.toFixed(1)}%`,
                        style: {
                            fontSize: '12px',
                            colors: ['#fff'],
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: (val) => `${val.toFixed(1)}% conversion rate`,
                        },
                    },
                    legend: {
                        show: false, // Hide legend as each bar is labeled
                    },
                });
                conversionChart.render();
            })
            .catch((error) => console.error("Error fetching data:", error));
    }

    $(".experiment-row").on("click", function () {
        const eksperimenId = $(this).data("eksperimen-id");

        if (selectedRow) selectedRow.removeClass("selected-row");
        $(this).addClass("selected-row");
        selectedRow = $(this);

        fetchData(eksperimenId);
    });

    selectFirstRow();
});

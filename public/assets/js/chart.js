$(document).ready(function () {
    let areaChart; // Declare variable to hold the chart instance
    let selectedRow; // Variable to keep track of the selected row

    // Function to select the first row on initial load
    function selectFirstRow() {
        const firstRow = $(".experiment-row").first(); // Get the first row
        if (firstRow.length) {
            firstRow.addClass("selected-row"); // Add class to the first row
            selectedRow = firstRow; // Update the selected row variable
            const eksperimenId = firstRow.data("eksperimen-id"); // Get its eksperimenId
            fetchData(eksperimenId); // Fetch data for the first row
        }
    }

    // Function to fetch data and render the chart
    function fetchData(eksperimenId) {
        // Fetch data from the API endpoint
        fetch(`/api/get-variant/${eksperimenId}`)
            .then((response) => response.json())
            .then((data) => {
                // Check if `variants` data is empty
                const domainName = $('#domain_name').html();

                // console.log(domainName)
                if (!data || !data.variants || data.variants.length === 0) {
                    // Clear the area chart and display "Data Not Found" message
                    if (areaChart) {
                        areaChart.destroy(); // Destroy any existing chart instance
                    }
                    $("#areaChart").html("<p>Data Not Found</p>"); // Display message
                    return; // Exit the function
                } else {
                    $("#areaChart").html(""); // Clear the chart container
                }

                // Set up series data for each conversion type
                const seriesData = [
                    {
                        name: "Button Clicks",
                        data: [
                            ...data.variants.map(
                                (variant) => variant.button_click || 0
                            ),
                            null,
                        ], // Add null for last bar
                    },
                    {
                        name: "Form Submits",
                        data: [
                            ...data.variants.map(
                                (variant) => variant.form_submit || 0
                            ),
                            null,
                        ], // Add null for last bar
                    },
                    {
                        name: "Slug Views (Variants)",
                        data: [
                            ...data.variants.map(
                                (variant) => variant.view || 0
                            ),
                            null,
                        ], // Add null for last bar
                    },
                    {
                        name: "Domain View Count",
                        data: Array(data.variants.length)
                            .fill(null)
                            .concat(data.experiment_view_count || 0), // Fill nulls, add count at end
                    },
                ];

                // Destroy existing chart if it exists
                if (areaChart) {
                    areaChart.destroy();
                }

                // Initialize and render the bar chart
                areaChart = new ApexCharts(
                    document.querySelector("#areaChart"),
                    {
                        chart: {
                            type: "bar",
                            toolbar: {
                                show: false, // Hide toolbar if not needed
                            },
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                dataLabels: {
                                    position: "top", // top, center, bottom
                                },
                            },
                        },
                        series: seriesData,
                        xaxis: {
                            categories: [
                                ...data.variants.map(
                                    (variant) => variant.variant_name
                                ),
                                domainName, // Add as separate x-axis category
                            ],
                        },
                        yaxis: {
                            title: {
                                text: "Count",
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: (val) => (val ? val.toString() : ""),
                            style: {
                                fontSize: "16px",
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: (val) => `${val} interactions`, // Customize tooltip
                            },
                        },
                        legend: {
                            position: "right",
                        },
                    }
                );

                areaChart.render();
            })
            .catch((error) => console.error("Error fetching data:", error));
    }

    // Listen for clicks on each experiment row
    $(".experiment-row").on("click", function () {
        const eksperimenId = $(this).data("eksperimen-id"); // Get eksperimenId using jQuery

        // Change background color of the selected row
        if (selectedRow) {
            selectedRow.removeClass("selected-row"); // Remove class from previously selected row
        }
        $(this).addClass("selected-row"); // Add class to the clicked row
        selectedRow = $(this); // Update the selected row

        fetchData(eksperimenId); // Fetch data for the clicked row
    });

    // Select the first row on initial load
    selectFirstRow();
});

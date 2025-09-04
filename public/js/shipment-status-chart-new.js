// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Nunito"),
     '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

fetch("/shipment-status-summary-new")
     .then((response) => response.json())
     .then((data) => {
          const labels = data.map((item) => item.status);
          const counts = data.map((item) => item.count);

          const ctx = document.getElementById("shipmentStatusChart").getContext("2d");
          const myChart = new Chart(ctx, {
               type: "doughnut", // Ubah menjadi 'doughnut' atau 'pie'
               data: {
                    labels: labels,
                    datasets: [
                         {
                              label: "Total Shipments",
                              data: counts,
                              backgroundColor: [
                                   "rgba(255, 99, 132, 1)", // Solid pink
                                   "rgba(255, 206, 86, 1)", // Solid yellow
                                   "rgba(75, 192, 192, 1)" // Solid teal
                              ],
                              borderColor: [
                                   "rgba(255, 99, 132, 1)",
                                   "rgba(255, 206, 86, 1)",
                                   "rgba(75, 192, 192, 1)"
                              ],
                              borderWidth: 1
                         }
                    ]
               },
               options: {
                    responsive: true,
                    maintainAspectRatio: false, // Optional, if you want to allow the chart to resize without maintaining aspect ratio
                    plugins: {
                         legend: {
                              position: "top"
                         },
                         tooltip: {
                              callbacks: {
                                   label: function (tooltipItem) {
                                        return tooltipItem.label + ": " + tooltipItem.raw;
                                   }
                              }
                         }
                    }
               }
          });
     });

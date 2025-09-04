// Set new default font family and font color to mimic Bootstrap's default styling
(Chart.defaults.global.defaultFontFamily = "Nunito"),
     '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = "#858796";

fetch("/monthly-shipments-new")
     .then((response) => response.json())
     .then((data) => {
          // Konversi data bulan menjadi nama bulan
          const months = [
               "Jan",
               "Feb",
               "Mar",
               "Apr",
               "May",
               "Jun",
               "Jul",
               "Aug",
               "Sep",
               "Oct",
               "Nov",
               "Dec"
          ];
          const labels = data.map((item) => months[item.month - 1]);
          const totalShipments = data.map((item) => item.total_pengiriman);
          const totalKoli = data.map((item) => item.total_koli);
          const totalBerat = data.map((item) => item.total_kg);

          // const totalKoli = data.map((item) => {
          //      let orderDetails = [];

          //      // Pastikan order_details berbentuk array dengan JSON.parse()
          //      if (typeof item.order_details === "string") {
          //           try {
          //                orderDetails = JSON.parse(item.order_details);
          //           } catch (error) {
          //                console.error("JSON parsing error:", error);
          //           }
          //      }

          //      // Jika berhasil di-parse dan berbentuk array, lakukan reduce
          //      return Array.isArray(orderDetails)
          //           ? orderDetails.reduce((acc, cur) => acc + (cur.jumlah || 0), 0)
          //           : 0;
          // });

          // const totalBerat = data.map((item) => {
          //      let orderDetails = [];

          //      // Pastikan order_details berbentuk array dengan JSON.parse()
          //      if (typeof item.order_details === "string") {
          //           try {
          //                orderDetails = JSON.parse(item.order_details);
          //           } catch (error) {
          //                console.error("JSON parsing error:", error);
          //           }
          //      }

          //      // Jika berhasil di-parse dan berbentuk array, lakukan reduce
          //      return Array.isArray(orderDetails)
          //           ? orderDetails.reduce((acc, cur) => acc + (cur.berat || 0), 0)
          //           : 0;
          // });




          const ctx = document.getElementById("shipmentChart").getContext("2d");
          const myLineChart = new Chart(ctx, {
               type: "line",
               data: {
                    labels: labels,
                    datasets: [
                         {
                              label: "Total Shipments",
                              lineTension: 0.3,
                              backgroundColor: "rgba(78, 115, 223, 0.05)", // Biru muda
                              borderColor: "rgba(78, 115, 223, 1)", // Biru tua
                              pointRadius: 3,
                              pointBackgroundColor: "rgba(78, 115, 223, 1)",
                              pointBorderColor: "rgba(78, 115, 223, 1)",
                              pointHoverRadius: 3,
                              pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                              pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                              pointHitRadius: 10,
                              pointBorderWidth: 2,
                              data: totalShipments
                         },
                         {
                              label: "Total Koli",
                              lineTension: 0.3,
                              backgroundColor: "rgba(46, 204, 113, 0.05)", // Hijau muda
                              borderColor: "rgba(46, 204, 113, 1)", // Hijau tua
                              pointRadius: 3,
                              pointBackgroundColor: "rgba(46, 204, 113, 1)",
                              pointBorderColor: "rgba(46, 204, 113, 1)",
                              pointHoverRadius: 3,
                              pointHoverBackgroundColor: "rgba(46, 204, 113, 1)",
                              pointHoverBorderColor: "rgba(46, 204, 113, 1)",
                              pointHitRadius: 10,
                              pointBorderWidth: 2,
                              data: totalKoli
                         },
                         {
                              label: "Total KG",
                              lineTension: 0.3,
                              backgroundColor: "rgba(231, 76, 60, 0.05)", // Merah muda
                              borderColor: "rgba(231, 76, 60, 1)", // Merah tua
                              pointRadius: 3,
                              pointBackgroundColor: "rgba(231, 76, 60, 1)",
                              pointBorderColor: "rgba(231, 76, 60, 1)",
                              pointHoverRadius: 3,
                              pointHoverBackgroundColor: "rgba(231, 76, 60, 1)",
                              pointHoverBorderColor: "rgba(231, 76, 60, 1)",
                              pointHitRadius: 10,
                              pointBorderWidth: 2,
                              data: totalBerat
                         }
                    ]
               },
               options: {
                    maintainAspectRatio: false,
                    layout: {
                         padding: {
                              left: 10,
                              right: 25,
                              top: 25,
                              bottom: 0
                         }
                    },
                    scales: {
                         xAxes: [
                              {
                                   time: {
                                        unit: "date"
                                   },
                                   gridLines: {
                                        display: false,
                                        drawBorder: false
                                   },
                                   ticks: {
                                        maxTicksLimit: 12
                                   }
                              }
                         ],
                         yAxes: [
                              {
                                   ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function (value, index, values) {
                                             return number_format(value) + " "; // Hilangkan simbol $
                                        }
                                   },
                                   gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                   }
                              }
                         ]
                    },
                    legend: {
                         display: false
                    },
                    tooltips: {
                         backgroundColor: "rgb(255,255,255)",
                         bodyFontColor: "#858796",
                         titleMarginBottom: 10,
                         titleFontColor: "#6e707e",
                         titleFontSize: 14,
                         borderColor: "#dddfeb",
                         borderWidth: 1,
                         xPadding: 15,
                         yPadding: 15,
                         displayColors: false,
                         intersect: false,
                         mode: "index",
                         caretPadding: 10,
                         callbacks: {
                              label: function (tooltipItem, chart) {
                                   var datasetLabel =
                                        chart.datasets[tooltipItem.datasetIndex].label || "";
                                   return datasetLabel + ": " + number_format(tooltipItem.yLabel); // Hilangkan simbol $
                              }
                         }
                    }
               }
          });

          // Fungsi untuk memformat angka
          function number_format(number, decimals, dec_point, thousands_sep) {
               number = (number + "").replace(",", "").replace(" ", "");
               var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
                    dec = typeof dec_point === "undefined" ? "." : dec_point,
                    s = "",
                    toFixedFix = function (n, prec) {
                         var k = Math.pow(10, prec);
                         return "" + Math.round(n * k) / k;
                    };
               // Fix for IE parseFloat(0.55).toFixed(0) = 0;
               s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
               if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(\d{3})+(?!\d))/g, sep);
               }
               if ((s[1] || "").length < prec) {
                    s[1] = s[1] || "";
                    s[1] += new Array(prec - s[1].length + 1).join("0");
               }
               return s.join(dec);
          }
     });
<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Performa Pengiriman</h1>
    </div>
    <div class="row mb-3">

        <!-- Area Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4 border-success">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between border-success">
                    <h6 class="m-0 font-weight-bold text-black">Laporan Performa Pengiriman</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <form class="form-inline">
                        <div class="input-group mb-2 mr-sm-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Bulan</div>
                            </div>
                            <input type="text" class="form-control" id="month" name="month">
                        </div>
                        <button type="button" id="btnFilter" class="btn btn-primary mb-2 mr-2">View</button>
                    </form>
                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <div id="noDataMessage"></div>
                            <div class="table-responsive">
                                <table class="table table-sm" id="performanceTable">
                                    <thead>
                                        <tr>
                                            <th scope="col">Status</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Persentase</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="text-center">
                                <button id="downloadBtn" class="btn btn-primary btn-sm" title="Download Chart"><i class="fa fa-download"></i></button>
                            </div>
                            <canvas id="myChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    flatpickr("#month", {
        plugins: [new monthSelectPlugin()],
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            },
            months: {
                shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            }
        },
        dateFormat: "F Y", // Format bulan dan tahun
        defaultDate: new Date(), // Menampilkan bulan dan tahun saat ini
    });
    $(document).ready(function() {
        let myChart = null;
        get_data();

        $('#btnFilter').on('click', function() {
            get_data();
        });
        $('#downloadBtn').on('click', function() {
            downloadChart();
        });

        function get_data() {
            var bulan = $('#month').val();
            var date = moment(bulan, "MMMM YYYY");
            if (!date.isValid()) {
                console.error('Invalid date:', bulan);
                $('#noDataMessage').text('Format bulan atau tahun tidak valid.').show();
                return;
            }

            var filterBulan = date.format("MM");
            var filterTahun = date.format("YYYY");
            $.ajax({
                url: "<?= base_url('laporan/get_data_performance'); ?>",
                method: "GET",
                data: {
                    bulan: filterBulan,
                    tahun: filterTahun
                },
                success: function(response) {
                    let data = response;
                    createChart(data.chart_data);
                    $('#performanceTable tbody').empty();

                    let totalCount = data.chart_data.reduce((sum, item) => sum + parseInt(item.count), 0);

                    data.chart_data.forEach(function(item) {
                        const count = parseInt(item.count);
                        const percentage = totalCount > 0 ? ((count / totalCount) * 100).toFixed(2) : 0;

                        $('#performanceTable tbody').append(`
                        <tr>
                            <td>${item.status}</td>
                            <td>${item.count}</td>
                            <td>${percentage}%</td>
                        </tr>
                    `);
                    });
                },
                error: function() {
                    $('#noDataMessage').text('Terjadi kesalahan saat mengambil data.').show();
                }
            });
        }

        function createChart(data) {

            var ctx = document.getElementById('myChart').getContext('2d');

            // Hancurkan chart lama jika sudah ada
            if (myChart !== null) {
                myChart.destroy();
            }

            // Filter data to include only those with a count greater than 0
            var filteredData = data.filter(function(item) {
                return item.count > 0;
            });

            var labels = filteredData.map(function(item) {
                return item.status;
            });

            var dataValues = filteredData.map(function(item) {
                return item.count;
            });

            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Task Status',
                        data: dataValues,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Task Status Distribution',
                            font: {
                                size: 18
                            }
                        },
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                return value + ' ' + context.chart.data.labels[context.dataIndex]; // Display the count
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }

        function downloadChart() {
            var link = document.createElement('a');
            link.href = document.getElementById('myChart').toDataURL('image/png');
            link.download = 'chart.png';
            link.click();
        }
    });
</script>


<?= $this->endSection(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    @include('manager.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tooltip.js/1.3.1/tooltip.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy.css">
    <style>
        /* Ubah warna teks untuk angka tanggal */
        .fc-daygrid-day-number {
            color: #2d3436 !important;
        }
        .fc-col-header-cell-cushion {
            color: #2d3436 !important;
        }
        .fc-day-today {
            background-color: #b2bec3 !important; /* Ganti dengan warna yang diinginkan */
        }
        .modal-header {
            background-color: #2d3436;
            color: #ffffff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
        }
        .modal-body {
            background-color: #f7f7f7;
            padding: 20px; /* Tambahkan padding di sini */
        }
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,.5);
            margin-top: 10px;
        }
        .modal-title {
            font-weight: bold;
            color: #ffffff;
        }
        .modal-body ul {
            padding-left: 20px;
        }
        .modal-body ul li {
            margin-bottom: 10px;
        }
        .calendar-container {
            position: relative;
        }
        .calendar-modal {
            display: none; /* Initially hidden */
        }
        .calendar-modal.show {
            display: block;
        }
        .closeCustomOrderModal {
            background-color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        @include('manager.sidebar')

        <div class="main">
            @include('manager.navbar')

            <main class="content">
                <div class="p-0 container-fluid">
                    @include('manager.chart')
            </main>

            <footer class="footer">
                @include('manager.footer')
            </footer>
        </div>
    </div>

    @include('manager.js')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy-bundle.iife.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('bestSellingProductsChart').getContext('2d');
            var topProducts = @json($topProducts);
            var productNames = topProducts.map(function(product) { return product.product_name; });
            var productQuantities = topProducts.map(function(product) { return product.total_quantity; });

            var bestSellingProductsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Jumlah Produksi',
                        data: productQuantities,
                        backgroundColor: ['#2d3436', '#636e72', '#b2bec3'],
                        borderColor: ['#2d3436', '#636e72', '#b2bec3'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },    
                        title: {
                            display: true,
                            text: 'Produksi Terbanyak'
                        }
                    }
                }
            });
            productNames.forEach(function(name, index) {
                document.getElementById('legend-item-' + index).textContent = name + ' ' + productQuantities[index];
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var monthlyOrders = @json($monthlyOrders);
            var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            var data = [];

            for (var i = 1; i <= 12; i++) {
                data.push(monthlyOrders[i] || 0);
            }

            new Chart(document.getElementById("chartjs-dashboard-bar"), {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "This year",
                        backgroundColor: '#2d3436',
                        borderColor: '#2d3436',
                        hoverBackgroundColor: '#2d3436',
                        hoverBorderColor: '#2d3436',
                        data: data,
                        barPercentage: .75,
                        categoryPercentage: .5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            stacked: false,
                            ticks: {
                                stepSize: 20
                            }
                        }],
                        xAxes: [{
                            stacked: false,
                            gridLines: {
                                color: "transparent"
                            }
                        }]
                    }
                }
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var orders = @json($orders);
        var events = orders.map(function(order) {
            return {
                title: order.product_name,
                start: order.deadline,
                backgroundColor: '#2d3436',
                borderColor: '#2d3436',
                extendedProps: {
                    progress: order.sedang_progress // Akses sedang_progress yang dikirim dari controller
                },
                description: 'Pesanan: ' + order.product_name + '\nProgress: ' + order.sedang_progress
            };
        });

        var calendarEl = document.getElementById('datetime-dashboard');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            events: events,
            dateClick: function(info) {
                var clickedDate = info.dateStr;
                var ordersOnDate = events.filter(function(event) {
                    return event.start === clickedDate;
                });

                var orderDetails = ordersOnDate.map(function(order) {
                    return `
                        <div class="order-details" data-id="${order.extendedProps.progress}">
                            <p>Nama Produk: ${order.title}</p>
                            <p>Sedang Diproduksi: ${order.extendedProps.progress}</p>
                        </div>
                    `;
                }).join('');

                document.getElementById('orderDetailsContent').innerHTML = orderDetails;
                document.getElementById('customOrderModal').style.display = 'block';
            }
        });

        calendar.render();

        document.getElementById('closeCustomOrderModal').addEventListener('click', function() {
            document.getElementById('customOrderModal').style.display = 'none';
        });
    });
</script>
</body>

</html>
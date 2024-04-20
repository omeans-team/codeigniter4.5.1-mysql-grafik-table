<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .kpi-heading {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .kpi-value {
            font-size: 1.2em;
            font-weight: bold;
            color: green;
        }

        .kpi-label {
            font-size: 1em;
            color: gray;
        }
    </style>
</head>

<body>
    <h1>Dashboard</h1>
    <div class="kpi-heading">Ketentuan Leadtime</div>
    <div class="kpi-label">Jika actual > deadline maka Late</div>
    <div class="kpi-label">Jika actual <= deadline makan Ontime</div>

            <div class="kpi-heading">KPI Sales</div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Task List</th>
                        <th>KPI</th>
                        <th>Karyawan</th>
                        <th>Deadline</th>
                        <th>Aktual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kpi_marketing as $row) : ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['tasklist']; ?></td>
                            <td><?= $row['kpi']; ?></td>
                            <td><?= $row['karyawan']; ?></td>
                            <td><?= $row['deadline']; ?></td>
                            <td><?= $row['aktual']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Tabel baru ditambahkan di bawah ini -->
            <div class="kpi-heading">Soal 1</div>
            <table>
                <thead>
                    <tr>
                        <th rowspan="2">Nama</th>
                        <th colspan="4">Sales</th>
                        <th colspan="4">Report</th>
                        <th rowspan="2">KPI</th>
                    </tr>
                    <tr>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Pencapaian</th>
                        <th>Actual Bobot</th>
                        <th>Target</th>
                        <th>Actual</th>
                        <th>Pencapaian</th>
                        <th>Actual Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rule_story as $row) : ?>
                        <tr>
                            <td><?= $row['karyawan']; ?></td>
                            <td><?= $row['jumlah_sales']; ?></td>
                            <td><?= $row['jumlah_aktual_sales']; ?></td>
                            <td><?= $row['pencapaian_sales_pct']; ?> %</td>
                            <td><?= $row['actual_bobot_sales']; ?> %</td>
                            <td><?= $row['jumlah_report']; ?></td>
                            <td><?= $row['jumlah_aktual_report']; ?></td>
                            <td><?= $row['pencapaian_report_pct']; ?> %</td>
                            <td><?= $row['actual_bobot_report']; ?> %</td>
                            <td><?= $row['kpi_persentase']; ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <canvas id="myChart"></canvas>


            <!-- Tabel baru ditambahkan di bawah ini -->
            <div class="kpi-heading">Soal 2</div>
            <table>
                <thead>
                    <tr>
                        <th>KPI</th>
                        <th>Jumlah Ontime</th>
                        <th>Jumlah Late</th>
                        <th>Persentase Ontime</th>
                        <th>Persentase Late</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasklist as $row) : ?>
                        <tr>
                            <td><?= $row['kpi']; ?></td>
                            <td><?= $row['jumlah_on_time']; ?></td>
                            <td><?= $row['jumlah_late']; ?></td>
                            <td><?= $row['persentase_on_time']; ?> %</td>
                            <td><?= $row['persentase_late']; ?> %</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <canvas id="myChart2"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($rule_story, 'karyawan')); ?>,
                        datasets: [{
                            label: 'KPI',
                            data: <?= json_encode(array_column($rule_story, 'kpi_persentase')); ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
            <script>
                var ctx2 = document.getElementById('myChart2').getContext('2d');
                var myChart2 = new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode(array_column($tasklist, 'kpi')); ?>,
                        datasets: [{
                                label: 'Jumlah Ontime',
                                data: <?= json_encode(array_column($tasklist, 'jumlah_on_time')); ?>,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Jumlah Late',
                                data: <?= json_encode(array_column($tasklist, 'jumlah_late')); ?>,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
</body>

</html>
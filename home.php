<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="head-title">
    <div class="left">
        <h1>Dashboard</h1>
    </div>
</div>

<ul class="box-info">
    <li>
        <i class='bx bxs-group'></i>
        <span class="text">
            <h3><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM user")); ?></h3>
            <p>Total Pengguna</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-graduation'></i>
        <span class="text">
            <h3><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM siswa")); ?></h3>
            <p>Total Siswa</p>
        </span>
    </li>
    <li>
        <i class='bx bxs-dollar-circle'></i>
        <span class="text">
            <h3><?php
                $query_saldo = "
                    SELECT SUM(IF(jenis_transaksi = 'setoran', jumlah, -jumlah)) AS total_saldo 
                    FROM transaksi_tabungan
                ";
                $result_saldo = mysqli_query($koneksi, $query_saldo);
                $data_saldo = mysqli_fetch_assoc($result_saldo);
                echo 'Rp ' . number_format($data_saldo['total_saldo'], 0, ',', '.' );
            ?></h3>
            <p>Total Tabungan</p>
        </span>
    </li>
</ul>

<div class="table-data">
    <!-- Tabel Daftar Siswa dengan Saldo Terbanyak -->
    <div class="order">
        <div class="head">
            <h3>Leaderboard</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Saldo Tabungan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $no = 1;
                    $sql = "
                        SELECT s.id_siswa, s.nama_siswa,
                            SUM(CASE WHEN t.jenis_transaksi = 'setoran' THEN t.jumlah 
                                     WHEN t.jenis_transaksi = 'penarikan' THEN -t.jumlah 
                                     ELSE 0 END) AS saldo
                        FROM siswa s
                        JOIN transaksi_tabungan t ON s.id_siswa = t.id_siswa
                        GROUP BY s.id_siswa, s.nama_siswa
                        ORDER BY saldo DESC
                        LIMIT 5
                    ";
                    $result = $koneksi->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td><img src='img/people.jpg' alt='Foto " . $row["nama_siswa"] . "' style='float:left; margin-right:15px;'>" . $row["nama_siswa"] . "</td>";
                            echo "<td>Rp " . number_format($row["saldo"], 0, ',', '.') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Grafik Transaksi Harian/Mingguan/Bulanan dan Setoran Penarikan -->
    <div class="chart-container">
        <div class="chart">
            <div class="head">
                <h3>Grafik Transaksi</h3>
            </div>
            <canvas id="transactionChart"></canvas>
        </div>

        <div class="chart">
            <div class="head">
                <h3>Setoran dan Penarikan</h3>
            </div>
            <canvas id="setoranPenarikanChart"></canvas>
        </div>
    </div>

    <!-- Grafik Kelas dengan Siswa Terbanyak -->
    <div class="chart">
        <div class="head">
            <h3>Grafik Kelas</h3>
        </div>
        <canvas id="kelasChart"></canvas>
    </div>
</div>

<?php
// Query data transaksi 30 hari terakhir untuk grafik
$dataPoints = [];
$query_transaksi = "
    SELECT DATE(tanggal_transaksi) AS tanggal, 
           SUM(IF(jenis_transaksi = 'setoran', jumlah, -jumlah)) AS total_transaksi 
    FROM transaksi_tabungan 
    WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    GROUP BY DATE(tanggal_transaksi)
    ORDER BY tanggal
";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);

if ($result_transaksi->num_rows > 0) {
    while ($row = $result_transaksi->fetch_assoc()) {
        $dataPoints[] = [
            'tanggal' => $row['tanggal'],
            'total_transaksi' => $row['total_transaksi']
        ];
    }
}

// Query untuk grafik setoran dan penarikan
$setoranPenarikanData = [];
$query_setoran_penarikan = "
    SELECT DATE(tanggal_transaksi) AS tanggal,
           SUM(CASE WHEN jenis_transaksi = 'setoran' THEN jumlah ELSE 0 END) AS total_setoran,
           SUM(CASE WHEN jenis_transaksi = 'penarikan' THEN jumlah ELSE 0 END) AS total_penarikan
    FROM transaksi_tabungan
    WHERE tanggal_transaksi >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    GROUP BY DATE(tanggal_transaksi)
    ORDER BY tanggal
";
$result_setoran_penarikan = mysqli_query($koneksi, $query_setoran_penarikan);

if ($result_setoran_penarikan->num_rows > 0) {
    while ($row = $result_setoran_penarikan->fetch_assoc()) {
        $setoranPenarikanData[] = [
            'tanggal' => $row['tanggal'],
            'setoran' => $row['total_setoran'],
            'penarikan' => $row['total_penarikan']
        ];
    }
}

// Query untuk grafik Kelas dengan Siswa Terbanyak
$kelasData = [];
$query_kelas = "
    SELECT kelas, COUNT(id_siswa) AS jumlah_siswa 
    FROM siswa 
    GROUP BY kelas 
    ORDER BY jumlah_siswa DESC
    LIMIT 5
";
$result_kelas = mysqli_query($koneksi, $query_kelas);

if ($result_kelas->num_rows > 0) {
    while ($row = $result_kelas->fetch_assoc()) {
        $kelasData[] = [
            'kelas' => $row['kelas'],
            'jumlah_siswa' => $row['jumlah_siswa']
        ];
    }
}
?>

<script>
    var transactionData = <?php echo json_encode($dataPoints); ?>;
    const labels = transactionData.map(data => data.tanggal);
    const dataPoints = transactionData.map(data => data.total_transaksi);

    // Grafik Transaksi
    const ctx = document.getElementById('transactionChart').getContext('2d');
    const transactionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Transaksi (Rp)',
                data: dataPoints,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Total Transaksi (Rp)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Setoran dan Penarikan
    var setoranPenarikanData = <?php echo json_encode($setoranPenarikanData); ?>;
    const setoranPenarikanLabels = setoranPenarikanData.map(data => data.tanggal);
    const totalSetoran = setoranPenarikanData.map(data => data.setoran);
    const totalPenarikan = setoranPenarikanData.map(data => data.penarikan);

    const ctxSetoranPenarikan = document.getElementById('setoranPenarikanChart').getContext('2d');
    const setoranPenarikanChart = new Chart(ctxSetoranPenarikan, {
        type: 'line',
        data: {
            labels: setoranPenarikanLabels,
            datasets: [{
                label: 'Total Setoran (Rp)',
                data: totalSetoran,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }, {
                label: 'Total Penarikan (Rp)',
                data: totalPenarikan,
                fill: false,
                borderColor: 'rgba(255, 99, 132, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Jumlah (Rp)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Grafik Kelas dengan Siswa Terbanyak
    var kelasData = <?php echo json_encode($kelasData); ?>;
    const kelasLabels = kelasData.map(data => data.kelas);
    const kelasJumlahSiswa = kelasData.map(data => data.jumlah_siswa);

    const ctxKelas = document.getElementById('kelasChart').getContext('2d');
    const kelasChart = new Chart(ctxKelas, {
        type: 'pie',
        data: {
            labels: kelasLabels,
            datasets: [{
                data: kelasJumlahSiswa,
                backgroundColor: ['#FF6347', '#FFD700', '#8A2BE2', '#32CD32', '#FF1493'],
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

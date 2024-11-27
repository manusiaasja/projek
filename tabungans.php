<?php
// Cek apakah user sudah login dan role-nya siswa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit;
}

// Mendapatkan id_siswa dari session
$id_siswa = $_SESSION['id_siswa'];

// Query untuk mendapatkan biodata siswa
$query_biodata = "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'";
$result_biodata = mysqli_query($koneksi, $query_biodata);
$biodata = mysqli_fetch_assoc($result_biodata);

// Query untuk mendapatkan detail transaksi tabungan siswa, termasuk nomor referensi
$query_transaksi = "SELECT *, nomor_referensi FROM transaksi_tabungan WHERE id_siswa = '$id_siswa'";
$result_transaksi = mysqli_query($koneksi, $query_transaksi);

// Query untuk menghitung total saldo siswa
$query_saldo = "SELECT 
    SUM(IF(jenis_transaksi = 'setoran', jumlah, 0)) - 
    SUM(IF(jenis_transaksi = 'penarikan', jumlah, 0)) AS total_saldo
    FROM transaksi_tabungan WHERE id_siswa = '$id_siswa'";
$result_saldo = mysqli_query($koneksi, $query_saldo);
$saldo = mysqli_fetch_assoc($result_saldo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tabungan Siswa</title>
    <link rel="stylesheet" href="style.css"> <!-- Menggunakan file style.css -->
    <style>
        /* Hanya memengaruhi halaman ini */
        #detail-tabungan-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #detail-tabungan-container h2, #detail-tabungan-container h3 {
            color: #333;
        }

        #detail-tabungan-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
        }

        #detail-tabungan-container th, #detail-tabungan-container td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        #detail-tabungan-container th {
            background-color: #3b82f6;
            color: white;
            text-align: center;
        }

        #detail-tabungan-container tr:nth-child(even) {
            background-color: #f1f5f9;
        }

        #detail-tabungan-container tr:hover {
            background-color: #e2e8f0;
        }

        #detail-tabungan-container .total-saldo {
            font-size: 1.2em;
            color: #3b82f6;
            text-align: right;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            #detail-tabungan-container {
                padding: 10px;
            }

            #detail-tabungan-container table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div id="detail-tabungan-container">
    <h2>Biodata Siswa</h2>
    <table>
        <tr>
            <td>Nama Siswa</td>
            <td><?php echo htmlspecialchars($biodata['nama_siswa']); ?></td>
        </tr>
        <tr>
            <td>NISN</td>
            <td><?php echo htmlspecialchars($biodata['nisn']); ?></td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td><?php echo htmlspecialchars($biodata['kelas']); ?></td>
        </tr>
        <tr>
            <td>Jurusan</td>
            <td><?php echo htmlspecialchars($biodata['jurusan']); ?></td>
        </tr>
    </table>

    <h3 class="total-saldo">Total Saldo: Rp <?php echo number_format($saldo['total_saldo'], 0, ',', '.'); ?></h3>

    <h2>Detail Tabungan</h2>
    <table>
        <tr>
            <th>No</th>
            <th>Jenis Transaksi</th>
            <th>Jumlah</th>
            <th>Tanggal Transaksi</th>
            <th>Nomor Referensi</th> <!-- Tambahkan kolom nomor referensi -->
        </tr>
        <?php
        $no = 1;
        while ($transaksi = mysqli_fetch_assoc($result_transaksi)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($transaksi['jenis_transaksi']) . "</td>";
            echo "<td>Rp " . number_format($transaksi['jumlah'], 0, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($transaksi['tanggal_transaksi']) . "</td>";
            echo "<td>" . htmlspecialchars($transaksi['nomor_referensi']) . "</td>"; // Tampilkan nomor referensi
            echo "</tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

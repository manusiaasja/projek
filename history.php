<?php
if (!isset($_SESSION['user'])) {
    header('location:login.php');
}

// Mengambil daftar siswa untuk dropdown
$sql_siswa = "SELECT id_siswa, nama_siswa FROM siswa ORDER BY nama_siswa ASC";
$result_siswa = $koneksi->query($sql_siswa);

// Mengambil transaksi berdasarkan siswa yang dipilih
$selected_siswa = isset($_POST['id_siswa']) ? $_POST['id_siswa'] : '';

$sql_transaksi = "SELECT t.id_transaksi, t.jenis_transaksi, t.jumlah, t.tanggal_transaksi, t.nomor_referensi
                  FROM transaksi_tabungan t
                  WHERE t.id_siswa = '$selected_siswa'
                  ORDER BY t.tanggal_transaksi DESC";
$result_transaksi = $koneksi->query($sql_transaksi);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <style>
        /* Gaya tampilan isolasi */
        .transaksi-container {
            padding: 20px;
        }

        .transaksi-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .transaksi-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .transaksi-table th, .transaksi-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .transaksi-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .transaksi-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .transaksi-table tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            font-size: 16px;
            color: #888;
        }

        .form-group {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group select, .form-group input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="transaksi-container">
    <h1 class="transaksi-title">Riwayat Transaksi Siswa</h1>

    <!-- Form untuk memilih siswa -->
    <div class="form-group">
        <form action="" method="POST">
            <label for="id_siswa">Pilih Siswa: </label>
            <select name="id_siswa" id="id_siswa" required>
                <option value="">-- Pilih Siswa --</option>
                <?php
                if ($result_siswa->num_rows > 0) {
                    while ($row_siswa = $result_siswa->fetch_assoc()) {
                        echo "<option value='" . $row_siswa['id_siswa'] . "'"
                            . ($selected_siswa == $row_siswa['id_siswa'] ? ' selected' : '') . ">"
                            . $row_siswa['nama_siswa'] . "</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" value="Tampilkan Riwayat">
        </form>
    </div>

    <!-- Tabel riwayat transaksi siswa yang dipilih -->
    <?php if ($selected_siswa && $result_transaksi->num_rows > 0): ?>
        <table class="transaksi-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Nomor Referensi</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Tanggal Transaksi</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $no = 1;
            while ($row_transaksi = $result_transaksi->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row_transaksi['nomor_referensi']) . "</td>";
                echo "<td>" . htmlspecialchars($row_transaksi['jenis_transaksi']) . "</td>";
                echo "<td>" . number_format($row_transaksi['jumlah'], 0, ',', '.') . "</td>";
                echo "<td>" . date('d M Y, H:i', strtotime($row_transaksi['tanggal_transaksi'])) . "</td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($selected_siswa): ?>
        <p class="no-data">Belum ada transaksi untuk siswa ini.</p>
    <?php endif; ?>
</div>

</body>
</html>

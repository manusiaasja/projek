<?php
// Koneksi ke database
require 'koneksi.php'; // Sesuaikan dengan file koneksi Anda

// Ambil ID Siswa dari parameter URL
$id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : null;

if (!$id_siswa) {
    // Jika ID siswa tidak ada, arahkan kembali ke halaman sebelumnya
    header('location:admin.php?page=tabungan'); // Ganti dengan nama file daftar tabungan Anda
    exit();
}

// Query untuk mengambil detail transaksi siswa
$sql_detail = "
    SELECT 
        jenis_transaksi, 
        jumlah, 
        tanggal_transaksi,
        nomor_referensi  -- Ambil nomor referensi
    FROM transaksi_tabungan
    WHERE id_siswa = ?
";
$stmt = $koneksi->prepare($sql_detail);
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result_detail = $stmt->get_result();

// Query untuk mengambil nama, NISN, dan kelas siswa
$sql_siswa = "
    SELECT 
        nama_siswa, 
        nisn, 
        kelas
    FROM siswa
    WHERE id_siswa = ?
";
$stmt_siswa = $koneksi->prepare($sql_siswa);
$stmt_siswa->bind_param("i", $id_siswa);
$stmt_siswa->execute();
$result_siswa = $stmt_siswa->get_result();
$siswa = $result_siswa->fetch_assoc();

// Query untuk menghitung total saldo siswa
$sql_saldo = "
    SELECT 
        COALESCE(SUM(CASE WHEN jenis_transaksi = 'setoran' THEN jumlah ELSE 0 END) - 
                 SUM(CASE WHEN jenis_transaksi = 'penarikan' THEN jumlah ELSE 0 END), 0) AS saldo
    FROM transaksi_tabungan
    WHERE id_siswa = ?
";
$stmt_saldo = $koneksi->prepare($sql_saldo);
$stmt_saldo->bind_param("i", $id_siswa);
$stmt_saldo->execute();
$result_saldo = $stmt_saldo->get_result();
$saldo = $result_saldo->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi Siswa</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
        }
        .info {
            margin-bottom: 20px;
            padding: 0 10px;
        }
        .info div {
            margin-bottom: 10px;
        }
        .info strong {
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 14px 18px;
            border-bottom: 1px solid #e0e0e0;
        }
        table th {
            background-color: #007bff;
            color: white;
            text-align: center;
            font-size: 14px;
        }
        table td {
            font-size: 14px;
            text-align: center;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f1f1f1;
        }
        .clearfix {
             margin-top: 20px; /* Tambahkan margin-top untuk memberi jarak */
         }

        .back-container, .print-container {
             margin-top: 20px; /* Bisa juga menambahkan margin di sini */
        }
        .back-link, .print-link {
            display: inline-block;
            width: 200px;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .back-link {
            background-color: #6c757d;
            float: left;
        }
        .back-link:hover {
            background-color: #5a6268;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        .print-link {
            background-color: #007bff;
            float: right;
        }
        .print-link:hover {
            background-color: #0056b3;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        /* Hide buttons when printing */
        @media print {
            .back-container, .print-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Transaksi Siswa</h2>

        <!-- Menampilkan Nama, NISN, Kelas, dan Saldo secara vertikal -->
        <div class="info">
            <div><strong>Nama:</strong> <?php echo htmlspecialchars($siswa['nama_siswa']); ?></div>
            <div><strong>NISN:</strong> <?php echo htmlspecialchars($siswa['nisn']); ?></div>
            <div><strong>Kelas:</strong> <?php echo htmlspecialchars($siswa['kelas']); ?></div>
            <div><strong>Total Saldo:</strong> Rp <?php echo number_format($saldo['saldo'], 0, ',', '.'); ?></div>
        </div>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Referensi</th> <!-- Tambahkan kolom nomor referensi -->
                    <th>Jenis Transaksi</th>
                    <th>Jumlah</th>
                    <th>Tanggal Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_detail->num_rows > 0) { ?>
                    <?php $no = 1; ?>
                    <?php while ($row_detail = $result_detail->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $no++; ?></td> <!-- Nomor urut -->
                            <td><?php echo htmlspecialchars($row_detail['nomor_referensi']); ?></td> <!-- Tampilkan nomor referensi -->
                            <td><?php echo htmlspecialchars($row_detail['jenis_transaksi']); ?></td>
                            <td><?php echo number_format($row_detail['jumlah'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($row_detail['tanggal_transaksi']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">Tidak ada data transaksi untuk siswa ini.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="clearfix">
            <div class="back-container">
                <a href="admin.php?page=tabungan" class="back-link">Kembali</a>
            </div>
            <div class="print-container">
                <a href="javascript:window.print()" class="print-link">Cetak</a>
            </div>
        </div>
    </div>
</body>
</html>

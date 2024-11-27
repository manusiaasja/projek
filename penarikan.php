<?php
// Mengambil data siswa dari database
$sql = "SELECT id_siswa, nama_siswa FROM siswa";
$result = $koneksi->query($sql);
$siswa_list = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $siswa_list[] = $row;
    }
}

// Mengambil saldo siswa jika form diposting
$total_saldo = 0;
$id_siswa = ''; // Inisialisasi id_siswa
$total_penarikan = 0; // Inisialisasi total penarikan

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $total_penarikan = $_POST['jumlah']; // Tetap menggunakan format dengan titik

    // Menghitung total saldo siswa yang dipilih (total setoran - total penarikan)
    $sql_saldo = "SELECT 
                    (SELECT IFNULL(SUM(jumlah), 0) FROM transaksi_tabungan WHERE id_siswa = '$id_siswa' AND jenis_transaksi = 'setoran') as total_setoran, 
                    (SELECT IFNULL(SUM(jumlah), 0) FROM transaksi_tabungan WHERE id_siswa = '$id_siswa' AND jenis_transaksi = 'penarikan') as total_penarikan";
                    
    $result_saldo = $koneksi->query($sql_saldo);
    if ($result_saldo->num_rows > 0) {
        $row_saldo = $result_saldo->fetch_assoc();
        $total_saldo = $row_saldo['total_setoran'] - $row_saldo['total_penarikan']; // Hitung saldo akhir
    }

    // Memproses penarikan
    if (!empty($id_siswa) && !empty($total_penarikan)) {
        // Menghapus titik untuk menyimpan ke database
        $total_penarikan_db = str_replace('.', '', $total_penarikan); // Menghapus titik

        if ($total_penarikan_db <= $total_saldo) {
            // Membuat nomor referensi
            if ($id_siswa) {
                $sql_kelas = "SELECT kelas FROM siswa WHERE id_siswa = '$id_siswa'";
                $result_kelas = $koneksi->query($sql_kelas);
                if ($result_kelas->num_rows > 0) {
                    $row_kelas = $result_kelas->fetch_assoc();
                    // Ambil hanya angka dari kelas
                    $kelas = substr($row_kelas['kelas'], 0, 2); // Misalnya jika kelas = 'RPL1', ini akan mengambil 'RPL' (modifikasi jika perlu)
                }
            }
            $nomor_referensi = 'PN' . $kelas . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT); // Contoh: ST101234

            // Memasukkan data penarikan ke dalam database
            $sql_insert = "INSERT INTO transaksi_tabungan (id_siswa, jenis_transaksi, jumlah, tanggal_transaksi, nomor_referensi)
                           VALUES ('$id_siswa', 'penarikan', '$total_penarikan_db', NOW(), '$nomor_referensi')";

            if ($koneksi->query($sql_insert) === TRUE) {
                echo "<script>
                        alert('Penarikan berhasil! Nomor Referensi: $nomor_referensi');
                        window.location.href = 'admin.php?page=tabungan'; // Mengarahkan ke halaman daftar tabungan
                      </script>";
                exit;
            } else {
                echo "<script>alert('Error: " . $koneksi->error . "');</script>";
            }
        } else {
            echo "<script>
                    alert('Penarikan gagal: saldo tidak mencukupi!');
                    window.location.href = 'admin.php?page=transaction&action=penarikan';
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Siswa</title>
    <style>
        /* CSS sama seperti halaman setoran */
        #form-penarikan {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #form-penarikan label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        #form-penarikan input[type="text"],
        #form-penarikan input[type="number"],
        #form-penarikan select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        #form-penarikan .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #form-penarikan .form-actions a,
        #form-penarikan .form-actions button {
            flex: 1;
            margin: 0 5px;
            padding: 12px;
        }
        #form-penarikan .button_setoran {
            background-color: #f44336; /* Merah */
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition: 0.3s;
        }
        #form-penarikan .button_setoran:hover {
            background-color: #d32f2f; /* Merah lebih gelap saat hover */
        }
        #form-penarikan .back-btn {
            display: inline-block;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            border: 1px solid #007bff;
            border-radius: 4px;
            text-align: center;
            transition: 0.3s;
        }
        #form-penarikan .back-btn:hover {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
    <script>
    function formatRibuan(input) {
        // Menghilangkan semua karakter selain angka
        let value = input.value.replace(/\D/g, '');
        
        // Format nilai ke dalam format ribuan
        if (value) {
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
    </script>
</head>
<body>

<section id="form-penarikan">
    <form action="" method="POST">
        <label for="id_siswa">Pilih Siswa:</label>
        <select name="id_siswa" id="id_siswa" onchange="this.form.submit()">
            <option value="">-- Pilih Siswa --</option>
            <?php foreach ($siswa_list as $siswa): ?>
                <option value="<?php echo htmlspecialchars($siswa['id_siswa']); ?>"
                    <?php echo ($id_siswa == $siswa['id_siswa']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($siswa['nama_siswa']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="total_saldo">Total Saldo:</label>
        <input type="text" id="total_saldo" value="<?php echo number_format($total_saldo, 0, ',', '.'); ?>" readonly>

        <label for="jumlah">Jumlah Penarikan:</label>
        <input type="text" name="jumlah" id="jumlah" required onkeyup="formatRibuan(this)">

        <div class="form-actions">
            <a href="?page=transaction" class="back-btn">Kembali</a>
            <button type="submit" class="button_setoran">Submit</button>
        </div>
    </form>
</section>

</body>
</html>

<?php
// Menutup koneksi
$koneksi->close();
?>

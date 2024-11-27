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
$nomor_referensi = ''; // Inisialisasi nomor referensi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $jumlah_setoran = $_POST['jumlah']; // Tetap menggunakan format dengan titik

    // Menghapus titik untuk menyimpan ke database
    $jumlah_setoran_db = str_replace('.', '', $jumlah_setoran); // Menghapus titik

    // Membuat nomor referensi
    if ($id_siswa) {
        $sql_kelas = "SELECT kelas FROM siswa WHERE id_siswa = '$id_siswa'";
        $result_kelas = $koneksi->query($sql_kelas);
        if ($result_kelas->num_rows > 0) {
            $row_kelas = $result_kelas->fetch_assoc();
            // Ambil hanya angka dari kelas
            $kelas = substr($row_kelas['kelas'], 0, 2); // Misalnya jika kelas = 'RPL1', ini akan mengambil 'RPL'
        }
    }
    $nomor_referensi = 'ST' . $kelas . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT); // Contoh: ST101234

    // Memasukkan data setoran ke dalam database
    if (!empty($id_siswa) && !empty($jumlah_setoran_db)) {
        $sql_insert = "INSERT INTO transaksi_tabungan (id_siswa, jenis_transaksi, jumlah, tanggal_transaksi, nomor_referensi)
                       VALUES ('$id_siswa', 'setoran', '$jumlah_setoran_db', NOW(), '$nomor_referensi')";
        
        if ($koneksi->query($sql_insert) === TRUE) {
            // Menampilkan pesan sukses dan mengarahkan ke halaman tabungan
            echo "<script>
                    alert('Setoran berhasil ditambahkan! Nomor Referensi: $nomor_referensi');
                    window.location.href = 'admin.php?page=tabungan'; // Mengarahkan ke halaman daftar tabungan
                  </script>";
            exit;
        } else {
            echo "<script>alert('Error: " . $koneksi->error . "');</script>";
        }
    }

    // Menghitung total setoran untuk siswa yang dipilih
    $sql_setoran = "SELECT SUM(jumlah) as total_setoran FROM transaksi_tabungan WHERE id_siswa = '$id_siswa' AND jenis_transaksi = 'setoran'";
    $result_setoran = $koneksi->query($sql_setoran);
    $total_setoran = ($result_setoran->num_rows > 0) ? $result_setoran->fetch_assoc()['total_setoran'] : 0;

    // Menghitung total penarikan untuk siswa yang dipilih
    $sql_penarikan = "SELECT SUM(jumlah) as total_penarikan FROM transaksi_tabungan WHERE id_siswa = '$id_siswa' AND jenis_transaksi = 'penarikan'";
    $result_penarikan = $koneksi->query($sql_penarikan);
    $total_penarikan = ($result_penarikan->num_rows > 0) ? $result_penarikan->fetch_assoc()['total_penarikan'] : 0;

    // Hitung total saldo
    $total_saldo = $total_setoran - $total_penarikan;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setoran Siswa</title>
    <style>
        #form-setoran {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #form-setoran label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        #form-setoran input[type="text"],
        #form-setoran input[type="number"],
        #form-setoran select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        /* Gunakan Flexbox untuk mengatur tombol */
        #form-setoran .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #form-setoran .form-actions a,
        #form-setoran .form-actions button {
            flex: 1;
            margin: 0 5px;
            padding: 12px;
        }
        #form-setoran .button_setoran {
            background-color: #007bff; 
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            transition:  0.3s;
        }
        #form-setoran .button_setoran:hover {
            background-color: #0056b3; 
        }
        #form-setoran .back-btn {
            display: inline-block;
            color: #007bff;
            text-decoration: none;
            font-size: 16px;
            border: 1px solid #6c757d;
            border-radius: 4px;
            text-align: center;
            transition: 0.3s;
        }
        #form-setoran .back-btn:hover {
            color: #fff;
            background-color: #5a6268;
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

<section id="form-setoran">
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

        <label for="jumlah">Jumlah Setoran:</label>
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

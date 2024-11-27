<?php
include 'koneksi.php'; // Pastikan file koneksi sudah benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $jumlah_setoran = $_POST['jumlah'];

    // Validasi apakah id_siswa dan jumlah sudah terisi
    if (empty($id_siswa) || empty($jumlah_setoran)) {
        echo "
        <script>
            alert('ID Siswa atau Jumlah Setoran tidak boleh kosong!');
            window.location.href = 'admin.php?page=transaction&action=setoran';
        </script>";
        exit;
    }

    // Cek apakah id_siswa valid (misalnya apakah ada di database)
    $sql_check = "SELECT id_siswa FROM siswa WHERE id_siswa = '$id_siswa'";
    $result_check = $koneksi->query($sql_check);
    if ($result_check->num_rows == 0) {
        echo "
        <script>
            alert('Siswa tidak ditemukan!');
            window.location.href = 'admin.php?page=transaction&action=setoran';
        </script>";
        exit;
    }

    // Proses insert data setoran
    $sql = "INSERT INTO transaksi_tabungan (id_siswa, jenis_transaksi, jumlah, tanggal_transaksi) 
            VALUES ('$id_siswa', 'setoran', '$jumlah_setoran', NOW())";

    if ($koneksi->query($sql) === TRUE) {
        echo "
        <script>
            alert('Setoran Berhasil');
            window.location.href = 'admin.php?page=tabungan'; // Mengarahkan ke halaman daftar tabungan
        </script>";
    } else {
        echo "
        <script>
            alert('Setoran Gagal: " . $koneksi->error . "');
            window.location.href = 'admin.php?page=transaction&action=setoran';
        </script>";
    }
}
?>

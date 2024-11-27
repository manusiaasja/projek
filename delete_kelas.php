<?php
include 'koneksi.php';

$kelas = $_GET['kelas'];
$jurusan = $_GET['jurusan'];

// Query untuk menghapus data
$query = "DELETE FROM kelas WHERE kelas='$kelas' AND jurusan='$jurusan'";

if (mysqli_query($koneksi, $query)) {
    // Jika berhasil, tampilkan alert dan redirect ke halaman kelas
    echo "<script>
            alert('Data kelas berhasil dihapus!');
            window.location.href='admin.php?page=kelas';
          </script>";
} else {
    // Jika gagal, tampilkan error
    echo "<script>
            alert('Error: " . mysqli_error($koneksi) . "');
            window.location.href='admin.php?page=kelas';
          </script>";
}
?>

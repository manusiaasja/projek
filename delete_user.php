<?php
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ?page=home');
}

$id_user = $_GET['id'];

$query = "DELETE FROM user WHERE id_user = $id_user";
if (mysqli_query($koneksi, $query)) {
    // Menampilkan alert berhasil
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'admin.php?page=users'; // Mengarahkan ke halaman daftar pengguna
          </script>";
} else {
    echo "Gagal menghapus user: " . mysqli_error($koneksi);
}
?>

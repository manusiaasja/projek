<?php
// Koneksi ke database
$mysqli = new mysqli("localhost", "root", "", "tabungan_siswa");

// Periksa koneksi
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Cek jika ID siswa ada di parameter URL
if (isset($_GET['id'])) { // Ubah 'id_siswa' menjadi 'id'
    $id_siswa = intval($_GET['id']); // Mengambil ID dari URL dan mengkonversi ke integer

    // Hapus data di tabel siswa (ini juga akan menghapus data terkait di user karena ON DELETE CASCADE)
    $query_delete_siswa = "DELETE FROM siswa WHERE id_siswa = ?";
    $stmt_siswa = $mysqli->prepare($query_delete_siswa);

    if ($stmt_siswa) {
        $stmt_siswa->bind_param("i", $id_siswa);
        $stmt_siswa->execute();
        $stmt_siswa->close(); // Menutup prepared statement

        // Redirect ke halaman daftar siswa dengan status delete
        header("Location: admin.php?page=student&status=delete"); // Menambahkan status ke URL
        exit();
    } else {
        echo "Error preparing statement for siswa: " . $mysqli->error;
    }
} else {
    echo "ID siswa tidak ditemukan.";
}

// Tutup koneksi
$mysqli->close();
?>

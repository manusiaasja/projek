<?php
// Koneksi ke database
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];

    // Query untuk mengecek apakah kelas dan jurusan sudah ada
    $cekQuery = "SELECT * FROM kelas WHERE kelas = '$kelas' AND jurusan = '$jurusan'";
    $cekResult = mysqli_query($koneksi, $cekQuery);

    if (mysqli_num_rows($cekResult) > 0) {
        // Jika data sudah ada, tampilkan alert
        echo "<script>alert('Kelas sudah ada!'); window.location.href = 'admin.php?page=kelas';</script>";
    } else {
        // Jika data belum ada, tambahkan ke database
        $query = "INSERT INTO kelas (kelas, jurusan) VALUES ('$kelas', '$jurusan')";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            echo "<script>alert('Kelas berhasil ditambahkan!'); window.location.href = 'admin.php?page=kelas';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan kelas.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas</title>
    <style>
        /* CSS untuk membuat form berada di tengah halaman */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }

        /* Container form */
        .form-container {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container input[type="text"], .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* Flexbox untuk tombol */
        .button-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .button-container button, .button-container a {
            width: 48%;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .button-container button {
            background-color: #007BFF;
            color: white;
        }

        .button-container button:hover {
            background-color: #0056b3;
            cursor: pointer;
        }

        .button-container a {
            background-color: #6c757d;
            color: white;
            margin-right: 10px; /* Menambahkan jarak antara tombol Kembali dan Submit */
        }

        .button-container a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Tambah Kelas</h2>
    <form action="tambah_kelas.php" method="POST">
        <label for="kelas">Kelas:</label>
        <input type="text" id="kelas" name="kelas" required>

        <label for="jurusan">Jurusan:</label>
        <input type="text" id="jurusan" name="jurusan" required>

        <div class="button-container">
            <a href="admin.php?page=kelas">Kembali</a> <!-- Tombol Kembali di kiri -->
            <button type="submit">Submit</button> <!-- Tombol Submit di kanan -->
        </div>
    </form>
</div>

</body>
</html>

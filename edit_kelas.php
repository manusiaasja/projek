<?php
include 'koneksi.php';

// Mendapatkan kelas dan jurusan dari URL
$kelas = $_GET['kelas'];
$jurusan = $_GET['jurusan'];

// Mengambil data jurusan saat ini
$query = "SELECT * FROM kelas WHERE kelas='$kelas' AND jurusan='$jurusan'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jurusan_baru = $_POST['jurusan'];

    // Query untuk mengupdate jurusan
    $update_query = "UPDATE kelas SET jurusan='$jurusan_baru' WHERE kelas='$kelas' AND jurusan='$jurusan'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('Data jurusan berhasil diubah!'); window.location.href='admin.php?page=kelas';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

mysqli_close($koneksi);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <link rel="stylesheet" href="style.css"> <!-- Pastikan file CSS Anda dihubungkan -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #555;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            gap: 10px; /* Menambahkan jarak antar tombol */
            margin-top: 10px;
        }
        button {
            width: 48%;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            width: 48%;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
        }
        a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Jurusan Kelas</h1>
        <form method="POST" action=" ">
            <label for="kelas">Kelas:</label>
            <input type="text" id="kelas" name="kelas" value="<?php echo htmlspecialchars($data['kelas']); ?>" readonly>
            
            <label for="jurusan">Jurusan:</label>
            <input type="text" id="jurusan" name="jurusan" value="<?php echo htmlspecialchars($data['jurusan']); ?>" required>
            
            <div class="button-container">
                <a href="admin.php?page=kelas">Kembali</a>
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>



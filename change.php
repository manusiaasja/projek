<?php
include 'koneksi.php'; // Sambungkan ke database
if ($_SESSION['role'] != 'admin') {
    header('Location: home.php');
    exit;
}

// Cek apakah ada ID user yang dikirim lewat URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    // Query untuk mengambil username user yang dipilih
    $query = "SELECT username FROM user WHERE id_user = '$id_user'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    // Jika user tidak ditemukan
    if (!$user) {
        echo "User tidak ditemukan!";
        exit;
    }

    // Proses ubah password
    if (isset($_POST['submit'])) {
        $new_password = $_POST['new_password'];
        $retype_password = $_POST['retype_password'];

        // Validasi apakah password cocok
        if ($new_password == $retype_password) {
            // Enkripsi password baru dengan MD5
            $hashed_password = md5($new_password);

            // Update password di database
            $update_query = "UPDATE user SET password = '$hashed_password' WHERE id_user = '$id_user'";
            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>alert('Password berhasil diubah!'); window.location.href = 'admin.php?page=users';</script>";
            } else {
                echo "Terjadi kesalahan saat mengubah password!";
            }
        } else {
            echo "<script>alert('Password dan Retype Password tidak cocok!');</script>";
        }
    }
} else {
    echo "ID user tidak valid!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password</title>
    <style>
        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            color: #333;
            font-family: 'Arial', sans-serif;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-container input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Tambahkan flexbox untuk tombol */
        .form-actions {
            display: flex;
            justify-content: space-between; /* Membuat tombol di kiri dan kanan */
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .back-button a {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
        }

        .back-button a:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Ubah Password - <?php echo $user['username']; ?></h2> <!-- Tampilkan username -->
    <form action="" method="POST">
        <label for="new_password">Password Baru:</label>
        <input type="password" name="new_password" id="new_password" required>

        <label for="retype_password">Ulangi Password:</label>
        <input type="password" name="retype_password" id="retype_password" required>

        <!-- Bagian tombol diatur dengan Flexbox -->
        <div class="form-actions">
            <div class="back-button">
                <a href="admin.php?page=users">Kembali</a> <!-- Tombol kembali di sebelah kiri -->
            </div>
            <button type="submit" name="submit">Submit</button> <!-- Tombol ubah password di sebelah kanan -->
        </div>
    </form>
</div>

</body>
</html>


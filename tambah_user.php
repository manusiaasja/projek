<?php
include 'koneksi.php'; // Pastikan untuk menyertakan file koneksi Anda

// Ambil data siswa dari database
$query_siswa = "SELECT id_siswa, nama_siswa FROM siswa";
$result_siswa = mysqli_query($koneksi, $query_siswa);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_siswa = $_POST['id_siswa'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $role = $_POST['role'];

    // Validasi input
    if (!empty($username) && !empty($password) && !empty($retype_password) && !empty($role) && ($role == 'admin' || !empty($id_siswa))) {
        // Periksa apakah username sudah ada
        $check_query = "SELECT * FROM user WHERE username = '$username'";
        $check_result = mysqli_query($koneksi, $check_query);

        // Periksa apakah siswa sudah memiliki user
        if ($role == 'siswa') {
            $check_siswa_query = "SELECT * FROM user WHERE id_siswa = '$id_siswa'";
            $check_siswa_result = mysqli_query($koneksi, $check_siswa_query);
        }

        if (mysqli_num_rows($check_result) > 0) {
            // Jika username sudah ada
            echo "<script>alert('Username sudah ada! Pilih username lain.');</script>";
        } elseif ($role == 'siswa' && mysqli_num_rows($check_siswa_result) > 0) {
            // Jika siswa sudah memiliki user
            echo "<script>alert('Siswa sudah memiliki user! Pilih siswa lain.');</script>";
        } else {
            // Username dan siswa belum ada, lanjutkan dengan proses penambahan user
            if ($password === $retype_password) {
                $hashed_password = md5($password);

                // Tambahkan id_siswa ke query jika role siswa
                $query = "INSERT INTO user (username, password, role, id_siswa) VALUES ('$username', '$hashed_password', '$role', " . ($role == 'siswa' ? "'$id_siswa'" : "NULL") . ")";
                $result = mysqli_query($koneksi, $query);

                if ($result) {
                    echo "<script>alert('User berhasil ditambahkan!'); window.location.href='admin.php?page=users';</script>";
                } else {
                    echo "<script>alert('Gagal menambahkan user: " . mysqli_error($koneksi) . "');</script>";
                }
            } else {
                echo "<script>alert('Password dan Re-type Password tidak cocok!');</script>";
            }
        }
    } else {
        echo "<script>alert('Harap isi semua kolom!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
        }
        .form-container {
            width: 400px;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border 0.3s;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
        }
        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 20px;
        }
        .form-buttons a,
        .form-buttons input[type="submit"] {
            width: 48%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .form-buttons a:hover,
        .form-buttons input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-buttons a {
            background-color: #6c757d;
        }
        .form-buttons a:hover {
            background-color: #5a6268;
        }
        .back-link a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
    </style>

    <script>
        function toggleSiswaDropdown() {
            var roleSelect = document.getElementById('role');
            var siswaDropdown = document.getElementById('siswaDropdown');

            if (roleSelect.value === 'admin') {
                siswaDropdown.style.display = 'none'; // Sembunyikan dropdown siswa
            } else {
                siswaDropdown.style.display = 'block'; // Tampilkan dropdown siswa
            }
        }
    </script>
</head>
<body onload="toggleSiswaDropdown()">
    <div class="form-container">
        <h2>Tambah User</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required onchange="toggleSiswaDropdown()">
                    <option value="admin">Admin</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>
            <div class="form-group" id="siswaDropdown">
                <label for="id_siswa">Pilih Siswa</label>
                <select id="id_siswa" name="id_siswa">
                    <option value="">-- Pilih Siswa --</option>
                    <?php while ($row_siswa = mysqli_fetch_assoc($result_siswa)) { ?>
                        <option value="<?php echo $row_siswa['id_siswa']; ?>">
                            <?php echo $row_siswa['nama_siswa']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="retype_password">Re-type Password</label>
                <input type="password" id="retype_password" name="retype_password" required>
            </div>
            <div class="form-buttons">
                <a href="admin.php?page=users">Kembali</a>
                <input type="submit" value="Tambah">
            </div>
        </form>
    </div>
</body>
</html>

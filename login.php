<?php
    include "koneksi.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="vie wport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto Condensed', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
            position: relative;
        }

        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-to-home a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            display: flex;
            align-items: center;
            transition: color 0.3s;
        }

        .back-to-home i {
            margin-right: 8px;
            font-size: 16px;
        }

        .back-to-home a:hover {
            color: #3366cc;
        }

        .login-box {
            display: flex;
            justify-content: center;
            flex-direction: column;
            width: 440px;
            height: 480px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            box-shadow: 0px 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            position: relative;
        }

        .login-header {
            text-align: center;
            margin: 20px 0 40px 0;
        }

        .login-header header {
            color: #333;
            font-size: 40px;
            font-weight: 600;
        }

        .login-box .input-field {
            width: 100%;
            height: 50px;
            font-size: 17px;
            padding: 0 20px;
            margin-bottom: 20px;
            border-radius: 25px;
            border: none;
            background: rgba(0, 0, 0, 0.05);
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
            outline: none;
            transition: 0.3s;
        }

        ::placeholder {
            font-weight: 500;
            color: #666;
        }

        .input-field:focus {
            transform: scale(1.03);
            background-color: #e0e0e0;
        }

        .submit-btn {
            width: 100%;
            height: 50px;
            font-size: 18px;
            color: #fff;
            background-color: #292323;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            outline: none;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background-color: #fff;
            color: #292323;
            transform: scale(1.05);
            border: 2px solid #292323;
        }

        .login-box {
            animation: slideIn 1s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>
    <div class="back-to-home">
        <a href="http://localhost/tabungan/">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    
    <div class="login-box">
        <div class="login-header">
            <header>LOGIN</header>
        </div>

        <?php
            if(isset($_POST['login'])) {
                $username = $_POST['username'];
                $password = md5($_POST['password']); // Gunakan hashing untuk password

                // Query untuk mengecek username dan password
                $data = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' AND password='$password'");
                $cek = mysqli_num_rows($data); // Cek apakah username dan password cocok

                if($cek > 0) {
                    // Jika cocok, ambil data user
                    $user = mysqli_fetch_array($data);

                    // Set session untuk user
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['id_user'] = $user['id_user'];

                    // Cek role user (admin atau siswa)
                    if($user['role'] == 'siswa') {
                        // Jika role siswa, ambil data siswa
                        $id_siswa = $user['id_siswa'];
                        $siswa_data = mysqli_query($koneksi, "SELECT nama_siswa FROM siswa WHERE id_siswa='$id_siswa'");
                        $siswa = mysqli_fetch_array($siswa_data);
                        $nama_siswa = $siswa['nama_siswa'];

                       // Set session untuk siswa
                        $_SESSION['id_siswa'] = $id_siswa;
                        $_SESSION['nama_siswa'] = $nama_siswa;

                        // Redirect ke halaman siswa
                        echo '<script>alert("Selamat Datang ' . $nama_siswa . '!"); location.href="admin.php?page=tabungans";</script>';
                    } else {
                        // Jika admin, redirect ke halaman admin
                        echo '<script>alert("Selamat Datang Admin!"); location.href="admin.php?page=home";</script>';
                    }
                } else {
                    // Jika username atau password salah
                    echo '<script>alert("Maaf, Username atau Password Salah!");</script>';
                }
            }
        ?>
        
        <form method="POST" action="">
            <div class="input-box">
                <input type="text" class="input-field" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" name="password" placeholder="Password" required>
            </div>

            <div class="input-submit">
                <button type="submit" class="submit-btn" name="login">Log In</button>
            </div>
        </form>
    </div>
    
</body>
</html>

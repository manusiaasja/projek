<?php
if ($_SESSION['role'] != 'admin') {
    header('Location: ?page=home');
} 

$query = "SELECT * FROM user";
$result = mysqli_query($koneksi, $query);
$no = 1; // Mulai nomor urut dari 1
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Users</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .users-page .user-data {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .users-page h2 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
        }

        .users-page table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .users-page table th {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        .users-page table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .users-page table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .users-page table tr:hover {
            background-color: #f1f1f1;
        }

        .users-page a {
            color: #007bff;
            text-decoration: none;
            padding: 6px; /* Sesuaikan padding untuk ikon */
            border-radius: 5px;
            transition:  0.3s ease;
            display: inline-block; /* Agar padding berfungsi dengan baik */
        }

        .users-page a:hover {
            background-color: #007bff;
            color: white;
        }

        .users-page a.delete {
            color: #f44336;
        }

        .users-page a.delete:hover {
            background-color: #e41b0f;
            color: white;
        }

        .users-page .icon {
            font-size: 20px; /* Ukuran ikon */
            margin: 0 5px; /* Jarak antar ikon */
        }

        .users-page .divider {
            display: inline-block;
            width: 1px;
            height: 20px; /* Sesuaikan tinggi sesuai kebutuhan */
            background-color: #ccc; /* Warna pembatas */
            margin: 0 8px; /* Jarak pembatas dengan ikon */
        }

        .users-page .change-password {
            text-align: center;
            font-size: 20px;
        }

        .add-user {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-user a {
            font-size: 16px;
            margin-left: 8px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="users-page">
    <div class="user-data">
        <h2>Data Users</h2>
        <div class="add-user">
            <a href="tambah_user.php" title="Tambah User">
                <i class="fas fa-plus-circle"></i>
                Tambah User
            </a>
        </div>
        <table border="1">
            <tr>
                <th>No</th> <!-- Ubah dari ID ke No -->
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
                <th>Change</th> <!-- Kolom untuk Ganti Password -->
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td> <!-- Menampilkan nomor urut -->
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $row['id_user']; ?>" class="icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <span class="divider"></span>
                        <a href="delete_user.php?id=<?php echo $row['id_user']; ?>" class="delete icon" title="Hapus" onclick="return confirm('Yakin ingin menghapus user ini?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                    <td class="change-password">
                    <a href="change.php?id=<?php echo $row['id_user']; ?>" class="icon" title="Ganti Password">
                        <i class="fas fa-key"></i>
                    </a>
                    </td> <!-- Tambahkan ikon untuk ganti password -->
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
</body>
</html>

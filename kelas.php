<?php
// Query untuk mengambil data kelas
$query = "SELECT * FROM kelas";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .aksi-icons {
            display: flex;
            gap: 10px;
        }
        .aksi-icons a {
            text-decoration: none;
            font-size: 20px;
            color: black;
        }
        .aksi-icons a:hover {
            color: #007bff; /* Warna hover */
        }
        /* CSS untuk tombol Tambah Kelas */
        .tambah-kelas-container {
            text-align: right;
            margin-bottom: 20px; /* Tambahkan jarak di sini */
        }
        .tambah-kelas {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .tambah-kelas:hover {
            background-color: #007BBB;
        }
    </style>
    <!-- Link ke icon library, misal dari Boxicons -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

<h2>Daftar Kelas</h2>

<!-- Tombol Tambah Kelas -->
<div class="tambah-kelas-container">
    <a href="tambah_kelas.php" class="tambah-kelas">Tambah Kelas</a>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Aksi</th> <!-- Kolom untuk aksi -->
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['kelas'] . "</td>";
            echo "<td>" . $row['jurusan'] . "</td>";
            echo "<td>";
            echo "<div class='aksi-icons'>";
            // Tombol Edit
            echo "<a href='edit_kelas.php?kelas=" . $row['kelas'] . "&jurusan=" . $row['jurusan'] . "' title='Edit'><i class='bx bxs-edit-alt'></i></a>";
            // Tombol Delete
            echo "<a href='delete_kelas.php?kelas=" . $row['kelas'] . "&jurusan=" . $row['jurusan'] . "' title='Delete' onclick='return confirm(\"Apakah Anda yakin ingin menghapus kelas ini?\")'><i class='bx bxs-trash'></i></a>";
            echo "</div>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

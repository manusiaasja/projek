<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id_siswa = $_GET['id'];

    // Mempersiapkan query untuk mendapatkan data siswa
    $sql = "SELECT * FROM siswa WHERE id_siswa = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_siswa);
    $stmt->execute();
    $result = $stmt->get_result();
    $siswa = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $nama_siswa = $_POST['nama_siswa']; 
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan']; // Ambil jurusan dari form
    $nisn = $_POST['nisn'];

    // Mempersiapkan query untuk update data siswa
    $sql_update = "UPDATE siswa SET nama_siswa=?, kelas=?, jurusan=?, nisn=? WHERE id_siswa=?";
    $stmt_update = $koneksi->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $nama_siswa, $kelas, $jurusan, $nisn, $id_siswa);

    if ($stmt_update->execute()) {
        // Redirect ke halaman student dengan status update
        header("Location: admin.php?page=student&status=update");
        exit();
    } else {
        echo "Error: " . $stmt_update->error;
    }
}

// Ambil data kelas dan jurusan dari database
$sql_kelas = "SELECT DISTINCT kelas, jurusan FROM kelas"; // Ambil kelas dan jurusan
$result_kelas = $koneksi->query($sql_kelas);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .button-group {
            display: flex;
            gap: 10px; /* Jarak antar tombol */
        }
        button,
        .cancel-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button {
            background-color: #007bff; 
            color: white;
        }
        button:hover {
            background-color: #0056b3; 
        }
        .cancel-btn {
            background-color: #6c757d; 
            color: white;
            text-align: center;
            text-decoration: none;
        }
        .cancel-btn:hover {
            background-color: #5a6268; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Data Siswa</h1>
        <form method="POST" action="">
            <label>Nama Siswa:</label>
            <input type="text" name="nama_siswa" value="<?php echo htmlspecialchars($siswa['nama_siswa']); ?>" required><br>

            <label>Kelas:</label>
            <select name="kelas" required>
                <option value="">Pilih Kelas</option>
                <option value="10" <?php echo ($siswa['kelas'] == '10') ? 'selected' : ''; ?>>10</option>
                <option value="11" <?php echo ($siswa['kelas'] == '11') ? 'selected' : ''; ?>>11</option>
                <option value="12" <?php echo ($siswa['kelas'] == '12') ? 'selected' : ''; ?>>12</option>
            </select><br>


            <label>Jurusan:</label>
            <select name="jurusan" required>
                <option value="">Pilih Jurusan</option>
                <?php 
                // Ambil jurusan yang unik
                $sql_jurusan = "SELECT DISTINCT jurusan FROM kelas";
                $result_jurusan = $koneksi->query($sql_jurusan);
                while ($row_jurusan = $result_jurusan->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($row_jurusan['jurusan']); ?>" <?php echo ($row_jurusan['jurusan'] == $siswa['jurusan']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($row_jurusan['jurusan']); ?>
                    </option>
                <?php endwhile; ?>
            </select><br>

            <label>NISN:</label>
            <input type="text" name="nisn" value="<?php echo htmlspecialchars($siswa['nisn']); ?>" required><br>

            <div class="button-group">
                <a href="admin.php?page=student" class="cancel-btn">Kembali</a>
                <button type="submit" name="update">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>

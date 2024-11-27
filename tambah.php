<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan']; // Tambahkan jurusan
    $nisn = $_POST['nisn'];

    $sql = "INSERT INTO siswa (nama_siswa, kelas, jurusan, nisn) VALUES ('$nama_siswa', '$kelas', '$jurusan', '$nisn')";

    if ($koneksi->query($sql) === TRUE) {
        // Redirect ke halaman student dengan parameter status=tambah
        header('Location: admin.php?page=student&status=tambah');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}

// Ambil data kelas yang hanya 10, 11, dan 12 dari database
$sql_kelas = "SELECT DISTINCT kelas FROM kelas WHERE kelas LIKE '10%' OR kelas LIKE '11%' OR kelas LIKE '12%'"; // Ambil kelas yang hanya 10, 11, 12
$result_kelas = $koneksi->query($sql_kelas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            max-width: 600px;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, select {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
        }

        button, .cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
        }

        button {
            background-color: #007bff;
        }

        button:hover {
            background-color: #0056b3;
        }

        .cancel {
            text-decoration: none;
            background-color: #6c757d;
        }

        .cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="tambah.php" method="POST">
            <h2>Tambah Data Siswa</h2>
            <label for="nama_siswa">Nama Siswa:</label>
            <input type="text" id="nama_siswa" name="nama_siswa" required>
            
            <label for="kelas">Kelas:</label>
            <select id="kelas" name="kelas" required>
                <option value="">Pilih Kelas</option>
                <?php if ($result_kelas->num_rows > 0): ?>
                    <?php while ($row_kelas = $result_kelas->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row_kelas['kelas']); ?>">
                            <?php echo htmlspecialchars($row_kelas['kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Tidak ada kelas tersedia</option>
                <?php endif; ?>
            </select>
            
            <label for="jurusan">Jurusan:</label>
            <select id="jurusan" name="jurusan" required>
                <option value="">Pilih Jurusan</option>
                <?php
                // Ambil jurusan dari database
                $sql_jurusan = "SELECT DISTINCT jurusan FROM kelas"; // Ambil jurusan yang unik
                $result_jurusan = $koneksi->query($sql_jurusan);
                if ($result_jurusan->num_rows > 0): 
                    while ($row_jurusan = $result_jurusan->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row_jurusan['jurusan']); ?>">
                            <?php echo htmlspecialchars($row_jurusan['jurusan']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Tidak ada jurusan tersedia</option>
                <?php endif; ?>
            </select>
            
            <label for="nisn">NISN:</label>
            <input type="text" id="nisn" name="nisn" required>
            
            <div class="form-buttons">
                <a href="admin.php?page=student" class="cancel">Kembali</a>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>

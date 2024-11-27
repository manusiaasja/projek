<?php
include 'koneksi.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ?page=home');
}

$id_user = $_GET['id'];

$query = "SELECT * FROM user WHERE id_user = $id_user";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $query_update = "UPDATE user SET username = '$username', role = '$role' WHERE id_user = $id_user";
    if (mysqli_query($koneksi, $query_update)) {
        // Menampilkan alert berhasil
        echo "<script>
                alert('Data berhasil disimpan!');
                window.location.href = 'admin.php?page=users'; // Mengarahkan ke halaman daftar pengguna
              </script>";
        exit; // Pastikan tidak ada kode lain yang dieksekusi setelah ini
    } else {
        echo "Update gagal: " . mysqli_error($koneksi);
    }
}
?>

<style>
.edit-user-container {
    display: flex;
    justify-content: center; 
    align-items: center; 
    min-height: 100vh; 
    background-color: #f4f4f4; 
}

.edit-user-container form {
    background-color: #fff; 
    padding: 20px;
    border-radius: 10px; 
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); 
    width: 300px; 
    margin: 20px; 
}

.edit-user-container h2 {
    text-align: center; 
    color: #333; 
}

.edit-user-container label {
    display: block; 
    margin-bottom: 8px; 
    color: #333; 
    font-weight: bold; 
}

.edit-user-container input[type="text"],
.edit-user-container select {
    width: 100%; 
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc; 
    border-radius: 5px; 
}

.edit-user-container .button-container {
    display: flex; 
    justify-content: space-between; 
}

.edit-user-container input[type="submit"],
.edit-user-container .back-button {
    width: 48%; /* Mengatur lebar tombol menjadi 48% untuk memberikan ruang antara keduanya */
    padding: 10px; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    font-size: 16px; 
}

.edit-user-container input[type="submit"] {
    background-color: #007bff;  
    color: white;
}

.edit-user-container .back-button {
    background-color: #6c757d; 
    color: white; 
    text-decoration: none; 
    display: flex; 
    justify-content: center; 
    align-items: center; 
}

.edit-user-container input[type="submit"]:hover {
    background-color: #0056b3; 
}

.edit-user-container .back-button:hover {
    background-color: #5a6268; 
}

.edit-user-container .button-container input[type="submit"],
.edit-user-container .back-button {
    width: 48%; 
}

.edit-user-container .button-container input[type="submit"] {
    margin-left: 10px; 
}

.edit-user-container .button-container .back-button {
    margin-right: 10px; 
}

</style>

<div class="edit-user-container">
    <form method="POST">
        <h2>Edit User</h2>
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <label>Role:</label>
        <select name="role">
            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="siswa" <?php echo ($user['role'] == 'siswa') ? 'selected' : ''; ?>>Siswa</option>
        </select>
        
        <div class="button-container">
            <a href="admin.php?page=users" class="back-button">Kembali</a>
            <input type="submit" name="submit" value="Submit">
        </div>
    </form>
</div>

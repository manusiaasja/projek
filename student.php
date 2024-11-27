<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Daftar Nama Siswa</h3>
            <a href="tambah.php" class='add-btn' title="Tambah Siswa">
                <i class='bx bx-plus'></i> Tambah Siswa
            </a>
        </div>
        <table id="example" class="row-border" style="width:100%">
            <thead>
                <tr>
                    <th>No</th> <!-- Ubah header menjadi No -->
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th>NISN</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Ambil data siswa dari database
                $sql = "SELECT * FROM siswa"; // Ubah sesuai dengan tabel yang Anda gunakan
                $result = $koneksi->query($sql);

                $no = 1; // Inisialisasi nomor urut
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>"; // Tampilkan nomor urut
                        echo "<td>" . htmlspecialchars($row["nama_siswa"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["kelas"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["jurusan"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nisn"]) . "</td>";
                        echo "<td>
                                <a href='update.php?id=" . $row["id_siswa"] . "' class='icon-btn' title='Edit'>
                                    <i class='bx bx-edit'></i>
                                </a>
                                <a href='delete.php?id=" . $row["id_siswa"] . "' class='icon-btn' title='Hapus' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>
                                    <i class='bx bx-trash'></i>
                                </a>

                            </td>";
                        echo "</tr>";
                        $no++; // Increment nomor urut
                    }
                } else {
                    echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

<script>
    new DataTable('#example');

    // Fungsi untuk mengambil parameter dari URL
    function getParameterByName(name) {
        let url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        let results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    window.onload = function() {
        // Cek jika ada parameter 'status' di URL
        let status = getParameterByName('status');
        if (status === 'tambah') {
            alert('Data Siswa Berhasil Ditambahkan.');
        } else if (status === 'update') {
            alert('Data Siswa Berhasil Diupdate.');
        } else if (status === 'delete') {
            alert('Data Siswa Berhasil Dihapus.');
        }
    };
</script>

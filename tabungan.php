<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="assets/style.css">
<?php
// Cek apakah user sudah login dan role-nya adalah siswa
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {

    // Jika role bukan siswa (misalnya admin), tampilkan seluruh data siswa
    $sql = "
        SELECT 
            siswa.id_siswa, 
            siswa.nama_siswa,
            COALESCE(SUM(CASE WHEN transaksi_tabungan.jenis_transaksi = 'setoran' THEN transaksi_tabungan.jumlah ELSE 0 END) - 
                     SUM(CASE WHEN transaksi_tabungan.jenis_transaksi = 'penarikan' THEN transaksi_tabungan.jumlah ELSE 0 END), 0) AS saldo
        FROM siswa
        LEFT JOIN transaksi_tabungan ON siswa.id_siswa = transaksi_tabungan.id_siswa
        GROUP BY siswa.id_siswa, siswa.nama_siswa
    ";
}

$result = $koneksi->query($sql);
?>

<!-- Tambahkan class khusus untuk halaman tabungan -->
<div class="tabungan-page">
    <div class="container">
        <h2>Daftar Tabungan Siswa</h2>
        
        <table border="1" id="example" class="row-border" style="width:100%">
            <thead>
                <tr>
                    <th>No</th> <!-- Kolom untuk nomor urut -->
                    <th>ID Siswa</th>
                    <th>Nama Siswa</th>
                    <th>Saldo</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { 
                    $no = 1; // Inisialisasi nomor urut
                    while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $no; ?></td> <!-- Tampilkan nomor urut -->
                            <td><?php echo $row['id_siswa']; ?></td>
                            <td><?php echo $row['nama_siswa']; ?></td>
                            <td><?php echo number_format($row['saldo'], 0, ',', '.'); ?></td>
                            <td class="detail-cell">
                                <a href="detail_siswa.php?id_siswa=<?php echo $row['id_siswa']; ?>" class="detail-link">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php $no++; } // Increment nomor urut ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5">Tidak ada data tabungan yang ditemukan.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Include Bootstrap dan DataTables CSS & JS hanya di halaman tabungan -->

<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

<script>
   new DataTable('#example');
</script>

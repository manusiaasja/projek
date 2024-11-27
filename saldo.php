<?php
include 'koneksi.php';

if (isset($_POST['id_siswa'])) {
    $id_siswa = $_POST['id_siswa'];

    // Query untuk mengambil total saldo
    $sql_saldo = "SELECT SUM(jumlah) as total_setoran FROM transaksi_tabungan WHERE id_siswa = '$id_siswa' AND jenis_transaksi = 'setoran'";
    $result_saldo = $koneksi->query($sql_saldo);
    
    $total_saldo = 0;
    if ($result_saldo->num_rows > 0) {
        $row_saldo = $result_saldo->fetch_assoc();
        $total_saldo = $row_saldo['total_setoran'] ?? 0;
    }

    // Query untuk mengambil riwayat tabungan siswa
    $sql_history = "SELECT tanggal_transaksi, jenis_transaksi, jumlah FROM transaksi_tabungan WHERE id_siswa = '$id_siswa'";
    $result_history = $koneksi->query($sql_history);

    $history_html = '';
    if ($result_history->num_rows > 0) {
        while ($row = $result_history->fetch_assoc()) {
            $history_html .= '<tr>';
            $history_html .= '<td>' . htmlspecialchars($row['tanggal_transaksi']) . '</td>';
            $history_html .= '<td>' . htmlspecialchars($row['jenis_transaksi']) . '</td>';
            $history_html .= '<td>' . number_format($row['jumlah'], 0, ',', '.') . '</td>';
            $history_html .= '</tr>';
        }
    } else {
        $history_html = '<tr><td colspan="3">Belum ada transaksi</td></tr>';
    }

    // Mengirimkan saldo dan riwayat transaksi dalam format JSON
    echo json_encode([
        'saldo' => number_format($total_saldo, 0, ',', '.'),
        'history' => $history_html
    ]);
}
?>

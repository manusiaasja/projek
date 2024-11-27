<?php
    session_start();
    $koneksi = mysqli_connect('localhost','root','','tabungan_siswa');
    
    // if(!$koneksi) {
    //     die("Koneksi gagal: " . mysqli_connect_error());
    // } else {
    //     echo "Koneksi Berhasil";
    // }
?>
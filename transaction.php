<style>
    .transaction-selection {
    text-align: center;
    margin-top: 50px;
}

.transaction-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #007bbb;
}
form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

form input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

form button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

form button:hover {
    background-color: #45a049;
}

</style>

<?php
if(isset($_GET['action'])) {
    if($_GET['action'] == 'setoran') {
        include 'setoran.php';
    } else if($_GET['action'] == 'penarikan') {
        include 'penarikan.php';
    } else {
        echo "<p>Action tidak dikenal!</p>";
    }
} else {
    echo "<div class='transaction-selection'>";
    echo "<h2>Silahkan pilih jenis transaksi:</h2>";
    echo "<div class='transaction-buttons'>";
    echo "<a href='?page=transaction&action=setoran' class='btn'>Setoran</a>";
    echo "<a href='?page=transaction&action=penarikan' class='btn'>Penarikan</a>";
    echo "</div>";
    echo "</div>";
}
?>

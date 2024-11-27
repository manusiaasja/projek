	<?php
		include "koneksi.php";
		if(!isset($_SESSION['user'])) {
			header('location:login.php');
		}
		$sql = "SELECT * FROM siswa";
		$result = $koneksi->query($sql);
	?>

	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Boxicons -->
		<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
		<!-- My CSS -->
		<link rel="stylesheet" href="assets/style.css">
		 <!-- Load Bootstrap hanya jika halaman adalah tabungan -->
		<title>Tabungan Siswa</title>
	</head>
	<body>


		<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
        <i class='bx bxs-smile'></i>
        <span class="text">Tabunganku</span>
    </a>
    <ul class="side-menu top">
	<?php if($_SESSION['role'] == 'admin') { ?>
        <li class="<?php echo ($_GET['page'] == 'home' || !isset($_GET['page'])) ? 'active' : ''; ?>">
            <a href="?page=home">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
		<li class="<?php echo ($_GET['page'] == 'kelas') ? 'active' : ''; ?>">
            <a href="?page=kelas">
                <i class='bx bxs-chalkboard'></i>
                <span class="text">Kelas</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['page'] == 'student') ? 'active' : ''; ?>">
            <a href="?page=student">
                <i class='bx bxs-graduation'></i>
                <span class="text">Siswa</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['page'] == 'users') ? 'active' : ''; ?>">
            <a href="?page=users">
                <i class='bx bxs-user'></i>
                <span class="text">Pengguna</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['page'] == 'history') ? 'active' : ''; ?>">
            <a href="?page=history">
                <i class='bx bx-time'></i>
                <span class="text">Riwayat</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['page'] == 'transaction') ? 'active' : ''; ?>">
            <a href="?page=transaction">
                <i class='bx bxs-shopping-bag-alt'></i>
                <span class="text">Transaksi</span>
            </a>
        </li>
        <li class="<?php echo ($_GET['page'] == 'tabungan') ? 'active' : ''; ?>">
            <a href="?page=tabungan">
                <i class='bx bxs-wallet'></i>
                <span class="text">Tabungan</span>
            </a>
        </li>
		<?php } ?>
		<?php if($_SESSION['role'] == 'siswa') { ?>
		<li class="<?php echo ($_GET['page'] == 'tabungans') ? 'active' : ''; ?>">
            <a href="?page=tabungans">
                <i class='bx bxs-wallet'></i>
                <span class="text">Tabungan</span>
            </a>
        </li>
		<?php } ?>
    </ul>

    <ul class="side-menu">
        <li>
            <a href="logout.php" class="logout">
                <i class='bx bxs-log-out-circle'></i>
                <span class="text">Keluar</span>
            </a>
        </li>
    </ul>
</section>
<!-- SIDEBAR -->




		<!-- CONTENT -->
		<section id="content">
			<!-- NAVBAR -->
			<nav>
				<i class='bx bx-menu' ></i>
				<a href="#" class="nav-link">Categories</a>
				<form action="" method="GET">
					<div class="form-input">
						<input type="search" name="q" placeholder="Search..." disabled>
						<button type="submit" class="search-btn" disabled><i class='bx bx-search'></i></button>
					</div>
				</form>
				<input type="checkbox" id="switch-mode" hidden>
				<label for="switch-mode" class="switch-mode"></label>
				<a href="#" class="profile">
					<img src="img/people.jpg">
				</a>
			</nav>
			<!-- NAVBAR -->

			<!-- MAIN -->
			<main>
			<?php
					$page = isset($_GET['page']) ? $_GET['page']: '';
					if(file_exists($page . '.php')) {
						include $page . '.php';
					} else {
						include '404.php';
					}
				?>
			</main>
			<!-- MAIN -->
		</section>
		<!-- CONTENT -->
		<script src="assets/script.js"></script>
	</body>
	</html>
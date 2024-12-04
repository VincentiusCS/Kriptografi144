<?php
	session_start();

	include 'connection.php';

	$username = $_POST['username'];
	$userpass = $_POST['password'];

	$sql	  = "SELECT * FROM kurir WHERE username='$username'";
	$data	  = mysqli_query($connect, $sql);
	$cek	  = mysqli_num_rows($data);

	if ($cek > 0) {
		$d = mysqli_fetch_object($data);
		if (password_verify($userpass, $d->password)) {
			$_SESSION['status']	  = true;
			$_SESSION['a_global'] = $d;
			$_SESSION['id'] = $d->id;
			$_SESSION['nama'] = $d->nama;
			header("Location: dashboard_kurir.php");
			exit();
		} else {
			header("Location: login_kurir.php?pesan=gagal");
			exit();
		}
	}
?>
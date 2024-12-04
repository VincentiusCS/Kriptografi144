<?php
	session_start();

	include 'connection.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	$sql	  = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
	$data	  = mysqli_query($connect, $sql);
	$cek	  = mysqli_num_rows($data);

	if ($cek > 0) {
		$d = mysqli_fetch_object($data);
		$_SESSION['status']	  = true;
		$_SESSION['a_global'] = $d;
		$_SESSION['id'] = $d->id;
		header("location: dashboard_admin.php");
	}
	else {
		header("location: login_admin.php?pesan=gagal");
	}
?>
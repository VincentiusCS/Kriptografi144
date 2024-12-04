<?php
	include 'connection.php';

	$username = $_POST['username'];
	$password = $_POST['password'];
    $nama = $_POST['nama'];

    //Enkripsi Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

	$query = mysqli_query($connect, "INSERT INTO kurir VALUES ('', '$nama', '$username', '$hashed_password')") or die (mysqli_error($connect));

	if ($query) {
		header("location: login_kurir.php?pesan=berhasil");
	}
	else{
		echo "Proses registrasi gagal";
	}
?>
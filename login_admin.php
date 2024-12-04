<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="sheet.css">
    <title>LOGIN | ADMIN</title>
</head>
<body>
<center>
    <div class="bg" style="margin-top: 50px">
        <h2>KURIR RAHASIA</h2>
        <h5>Login Admin</h5>
			

			<!-- pesan notifikasi session -->
			<?php 
				if (isset($_GET['pesan'])) {
					if ($_GET['pesan'] == "gagal") {
						echo "Login gagal! Username dan password salah";
					}
					elseif ($_GET['pesan'] == "logout") {
						echo "Anda telah berhasil logout";
					}
					elseif ($_GET['pesan'] == "belum_login") {
						echo "Anda harus login dulu untuk mengakses halaman ini";
					}
				}
			?>

			<form method="POST" action="login_admin_proses.php" style="margin-top: 40px">
				<div class="text-center" style="width: 250px">
					<div class="mb-3">
					   <label for="inputUsername" class="form-label col-sm-9">Username</label>
					   <input type="text" name="username" class="form-control" id="inputUsername">
					</div>
					<div class="mb-3">
					   <label for="inputPassword" class="form-label col-sm-10">Pasword</label>
					   <input type="password" name="password" class="form-control" id="inputPassword">
					</div>
					<button class="btn btn-secondary btn-lg btn-outline-light" type="submit">Login</button>
				</div>
			</form>
		</div>
	</center>
</body>
</html>
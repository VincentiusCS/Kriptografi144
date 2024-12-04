<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="sheet.css">
	<title> Kurir Rahasia | Register</title>
</head>
<body>
    <center>
        <div class="bg" style="margin-top: 50px">  
            <h2>KURIR RAHASIA</h2>
            <h5>DAFTAR AKUN</h5>
			<small>Silakan buat akun terlebih dulu</small><br>

			<form method="POST" action="register_proses.php" style="margin-top: 10px">
				<div class="text-center" style="width: 250px">
                    <div>
					   <label for="inputUsername" class="form-label col-sm-9">Nama Lengkap</label>
					   <input type="text" name="nama" class="form-control" id="inputName">
					</div>
					<div>
					   <label for="inputUsername" class="form-label col-sm-9">Username</label>
					   <input type="text" name="username" class="form-control" id="inputUsername">
					</div>
					<div class="mb-1">
					   <label for="inputPassword" class="form-label col-sm-10">Password</label>
					   <input type="password" name="password" class="form-control" id="inputPassword">
					</div>
					<button class="btn btn-secondary btn-lg btn-outline-light" type="submit">DAFTAR</button>
				</div>
			</form>

			<small>Sudah punya akun? <a href="login_kurir.php" class="text-reset">Login di sini.</a></small>
		</div>
	</center>
</body>
</html>
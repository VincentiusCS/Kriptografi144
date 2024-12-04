<?php 
    error_reporting(0);
	session_start();
    include("connection.php");
	if ($_SESSION['status'] != true) {
		header("location: login_kurir.php?pesan=belum_login");
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="sheet.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="dashboard_admin.php"><img src="Source/kurir.png" width="200px"></a>
            <ul>
                <li><a href="logout.php">Keluar</a></li>
            </ul>
        </div>
    </header>

    <div class="section">
        <div class="container">
            <h3>Kirim Pesan Ke Bos</h3>
            <div class="box">
                
            <form action="upload_proses.php" method="POST" enctype="multipart/form-data">
                <textarea class="input-control" name="pesan" placeholder="Pesan"></textarea>
                <br><br>
                
                <label for="cover_image">Masukan Cover Image:</label>
                <input type="file" name="cover" class="input_control" id="cover_image" required>
                <br><br>
                
                <label for="secret_image">Masukan Secret Image:</label>
                <input type="file" name="secret" class="input_control" id="secret_image" required>
                <br><br>
                
                <label for="file">Masukan File:</label>
                <input type="file" name="file" class="input_control" id="file" required>
                <br><br>
                
                <input type="submit" name="submit" value="Submit" class="btn">
            </form>                       
            </div>
        </div>
    </div>

    <footer>
        <div class="footer">
            <H1>Kriptografi UPNVYK</H1>
        </div>
    </footer>
</body>
</html>
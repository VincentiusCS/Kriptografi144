<?php 
	session_start();
	if ($_SESSION['status'] != true) {
		header("location: login_admin.php?pesan=belum_login");
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
                <li><a href="admin_daftar_kurir.php">Daftar Kurir</a></li>
                <li><a href="admin_pesan.php">Pesan Masuk</a></li>
                <?php 
                echo '<li><a href="logout.php">' . $_SESSION['a_global']->nama . '</a></li>'
                ?>
            </ul>
        </div>
    </header>

    <div class="section">
        <div class="container">
            <h3>Dashboard</h3>
            <div class="box">
                <?php
                    echo '<h4>Selamat Datang '.$_SESSION['a_global']->nama.'</h4>';
                ?>
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
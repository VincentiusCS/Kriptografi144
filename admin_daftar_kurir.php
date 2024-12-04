<?php 
	session_start();
    include 'connection.php';
	if ($_SESSION['status'] != true) {
		header("location: login_admin.php?pesan=belum_login");
	}
?>
<?php 
function xor_decrypt($encryptedText, $key) {
    $result = '';
    $key_length = strlen($key);
    for ($i = 0; $i < strlen($encryptedText); $i++) {
        $result .= $encryptedText[$i] ^ $key[$i % $key_length];
    }
    return $result;
}

function rail_fence_decrypt($encryptedText, $rails) {
    if ($rails <= 1) return $encryptedText;

    $length = strlen($encryptedText);
    $fence = array_fill(0, $rails, array_fill(0, $length, null));

    // Mark the zigzag pattern
    $direction_down = false;
    $row = 0;
    for ($i = 0; $i < $length; $i++) {
        $fence[$row][$i] = '*';
        if ($row == 0 || $row == $rails - 1) $direction_down = !$direction_down;
        $row += $direction_down ? 1 : -1;
    }

    // Fill the fence with the characters of the encrypted text
    $index = 0;
    for ($r = 0; $r < $rails; $r++) {
        for ($c = 0; $c < $length; $c++) {
            if ($fence[$r][$c] === '*' && $index < $length) {
                $fence[$r][$c] = $encryptedText[$index++];
            }
        }
    }

    // Read the fence in a zigzag manner to decrypt
    $result = '';
    $row = 0;
    $direction_down = false;
    for ($i = 0; $i < $length; $i++) {
        $result .= $fence[$row][$i];
        if ($row == 0 || $row == $rails - 1) $direction_down = !$direction_down;
        $row += $direction_down ? 1 : -1;
    }

    return $result;
}

function decrypt_message($encryptedText, $xor_key, $rails) {          

    // Then decrypt XOR Cipher
    $decrypted1 = xor_decrypt($encryptedText, $xor_key);

    // First decrypt Rail Fence Cipher
    $original_message = rail_fence_decrypt($decrypted1, $rails);

    return $original_message;
}

function extract_secret_image($stegoPath, $secretPath) {
    // Load the stego image
    $stego_image = imagecreatefrompng($stegoPath); // Assuming PNG format
    $stego_width = imagesx($stego_image);
    $stego_height = imagesy($stego_image);

    // Create a new empty image for the secret
    $secret_image = imagecreatetruecolor($stego_width, $stego_height);

    // Loop through each pixel of the stego image
    for ($y = 0; $y < $stego_height; $y++) {
        for ($x = 0; $x < $stego_width; $x++) {
            // Get the color of the pixel at (x, y) in the stego image
            $stego_color = imagecolorat($stego_image, $x, $y);

            // Extract the RGB components of the stego image
            $stego_r = ($stego_color >> 16) & 0xFF;
            $stego_g = ($stego_color >> 8) & 0xFF;
            $stego_b = $stego_color & 0xFF;

            // Extract the most significant bit (MSB) of each color channel to reveal the secret
            $secret_r = ($stego_r & 0x01) << 7; // Shift the LSB to the MSB
            $secret_g = ($stego_g & 0x01) << 7;
            $secret_b = ($stego_b & 0x01) << 7;

            // Combine the extracted bits to form the secret pixel's color
            $secret_color = imagecolorallocate($secret_image, $secret_r, $secret_g, $secret_b);

            // Set the pixel in the secret image
            imagesetpixel($secret_image, $x, $y, $secret_color);
        }
    }

    // Save the secret image to the given path
    imagepng($secret_image, $secretPath);
    imagedestroy($stego_image);
    imagedestroy($secret_image);

    return true;
}

function aes_decrypt_file($encryptedPath, $decryptedPath, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);

    // Read the encrypted file content
    $encryptedContent = file_get_contents($encryptedPath);
    if ($encryptedContent === false) {
        echo "Failed to read the encrypted file.<br>";
        return false;
    }

    // Extract the IV and encrypted data
    $iv = substr($encryptedContent, 0, $ivlen); // First bytes are the IV
    $encryptedData = substr($encryptedContent, $ivlen); // Rest is the encrypted data

    // Decrypt the data
    $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    if ($decryptedData === false) {
        echo "Failed to decrypt the file.<br>";
        return false;
    }

    // Save the decrypted content to a new file
    if (file_put_contents($decryptedPath, $decryptedData) === false) {
        echo "Failed to save the decrypted file.<br>";
        return false;
    }

    return true;
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
            <h3>Kurir Terdaftar</h3>
            <div class="box">
            <table border="1" cellspacing="0" class="table">

                    <thead>
                        <tr>
                            <th width="60px">No</th>
                            <th>Nama</th>
                            <th>username</th>
                            <th>password</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                        $no = 1;
                            $kurir = mysqli_query($connect,"SELECT * FROM kurir ORDER BY id ASC");
                            if(mysqli_num_rows($kurir) > 0) {
                            while ($row = mysqli_fetch_array($kurir)) {
                                    
                        ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $row['nama'] ?></td>
                            <td><?php echo $row['username'] ?></td>
                            <td><?php echo $row['password'] ?></td>
                        </tr>
                    <?php }}else{ ?>
                        <tr>
                            <td colspan="8">Tidak ada data</td>
                        </tr>
                    <?php } ?>
                    </tbody>

            </table>
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
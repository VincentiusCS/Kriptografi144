<?php
session_start();
include("connection.php");
if ($_SESSION['status'] != true) {
    header("location: login_kurir.php?pesan=belum_login");
}

// XOR Encryption Function
function xor_encrypt($text, $key) {
    $result = '';
    $key_length = strlen($key);
    for ($i = 0; $i < strlen($text); $i++) {
        $result .= $text[$i] ^ $key[$i % $key_length];
    }
    return $result;
}

// Rail Fence Cipher Encryption Function
function rail_fence_encrypt($text, $rails) {
    if ($rails <= 1) return $text;

    $fence = array_fill(0, $rails, []);
    $direction_down = false;
    $row = 0;

    // Build the rail fence pattern
    for ($i = 0; $i < strlen($text); $i++) {
        $fence[$row][] = $text[$i];
        if ($row == 0 || $row == $rails - 1) $direction_down = !$direction_down;
        $row += $direction_down ? 1 : -1;
    }

    // Concatenate characters from each rail
    $result = '';
    foreach ($fence as $rail) {
        $result .= implode('', $rail);
    }

    return $result;
}

//Steganography LSB Method
function hide_secret_image($coverPath, $secretPath, $stegoPath) {
    // Load cover and secret images
    $cover_image = imagecreatefrompng($coverPath); // Assuming PNG format
    $secret_image = imagecreatefrompng($secretPath);

    // Get the width and height of both images
    $cover_width = imagesx($cover_image);
    $cover_height = imagesy($cover_image);
    $secret_width = imagesx($secret_image);
    $secret_height = imagesy($secret_image);

    // Resize the secret image to fit within the cover image if necessary
    if ($secret_width > $cover_width || $secret_height > $cover_height) {
        // You can resize it or return an error
        echo "Secret image is larger than cover image!";
        return false;
    }

    // Loop through each pixel of the cover image
    for ($y = 0; $y < $secret_height; $y++) {
        for ($x = 0; $x < $secret_width; $x++) {
            // Get RGB values of the cover and secret image at (x, y)
            $cover_color = imagecolorat($cover_image, $x, $y);
            $secret_color = imagecolorat($secret_image, $x, $y);

            // Extract the RGB values of the cover image
            $cover_r = ($cover_color >> 16) & 0xFF;
            $cover_g = ($cover_color >> 8) & 0xFF;
            $cover_b = $cover_color & 0xFF;

            // Extract the RGB values of the secret image
            $secret_r = ($secret_color >> 16) & 0xFF;
            $secret_g = ($secret_color >> 8) & 0xFF;
            $secret_b = $secret_color & 0xFF;

            // Embed the secret image bits into the least significant bits of the cover image
            $new_r = ($cover_r & 0xFE) | ($secret_r >> 7); // LSB for red channel
            $new_g = ($cover_g & 0xFE) | ($secret_g >> 7); // LSB for green channel
            $new_b = ($cover_b & 0xFE) | ($secret_b >> 7); // LSB for blue channel

            // Combine the new RGB values back into a color
            $new_color = imagecolorallocate($cover_image, $new_r, $new_g, $new_b);

            // Set the pixel with the new color
            imagesetpixel($cover_image, $x, $y, $new_color);
        }
    }

    // Save the resulting image with the hidden secret
    imagepng($cover_image, $stegoPath);
    imagedestroy($secret_image);
    imagedestroy($cover_image);
    return true;
}

function aes_encrypt_file($filePath, $encryptedPath, $key) {
    $cipher = "aes-256-cbc";
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);

    // Read file content
    $fileContent = file_get_contents($filePath);
    if ($fileContent === false) {
        echo "Failed to read the file for encryption.<br>";
        return false;
    }

    // Encrypt the file content
    $encryptedData = openssl_encrypt($fileContent, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    if ($encryptedData === false) {
        echo "Failed to encrypt the file.<br>";
        return false;
    }

    // Combine IV and encrypted data for storage
    $encryptedContent = $iv . $encryptedData;

    // Save the encrypted content to a new file
    if (file_put_contents($encryptedPath, $encryptedContent) === false) {
        echo "Failed to save the encrypted file.<br>";
        return false;
    }

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kurir_id = $_SESSION['id'];
    // Sanitize and retrieve the text input
    $pesan = htmlspecialchars($_POST['pesan']);

    //key
    $xor_key = "informatika";
    $rails = 4;
    $aes_key = "informatika1234567890kriptografi";

    //enkripsi pesan
    $encrypt1 = rail_fence_encrypt($pesan, $rails);
    $super_encrypted= xor_encrypt($encrypt1, $xor_key);
    $pesan = $super_encrypted;

    // Directories for uploads
    $uploadDir = 'uploads/';
    $imageDir = $uploadDir . 'images/';
    $encryptedDir = $uploadDir . 'encrypted_files/';

    // Ensure directories exist
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }
    if (!is_dir($encryptedDir)) {
        mkdir($encryptedDir, 0777, true);
    }

    // Handle Cover Image Upload
    if (isset($_FILES['cover']) && isset($_FILES['secret']) && $_FILES['cover']['error'] === 0 && $_FILES['secret']['error'] === 0) {
        // Handle Cover Image
        $coverTmp = $_FILES['cover']['tmp_name'];
        $coverName = time() . '_cover_' . basename($_FILES['cover']['name']);
        $coverPath = $imageDir . $coverName;
        if (!move_uploaded_file($coverTmp, $coverPath)) {
            die("Failed to upload the cover image.");
        }
    
        // Handle Secret Image
        $secretTmp = $_FILES['secret']['tmp_name'];
        $secretName = time() . '_secret_' . basename($_FILES['secret']['name']);
        $secretPath = $imageDir . $secretName;
        if (!move_uploaded_file($secretTmp, $secretPath)) {
            die("Failed to upload the secret image.");
        }
    
        // Embed Secret into Cover
        $stegoPath = $imageDir . time() . '_stego_image.png';
        if (!hide_secret_image($coverPath, $secretPath, $stegoPath)) {
            die("Failed to embed the secret image into the cover image.");
        }
    }
    
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        // Encrypt File
        $fileTmp = $_FILES['file']['tmp_name'];
        $encryptedFileName = time() . '_encrypted_' . basename($_FILES['file']['name']);
        $encryptedFilePath = $encryptedDir . $encryptedFileName;
        if (!aes_encrypt_file($fileTmp, $encryptedFilePath, $aes_key)) {
            die("Failed to encrypt the file.");
        }
    }

    // Save data to the database
    $sql = "INSERT INTO uploads (pesan, cover_image_path, stego_image_path, file_path, kurir_id) 
            VALUES ('$pesan', '$coverPath', '$stegoPath', '$encryptedFilePath', '$kurir_id')";

    if (mysqli_query($connect, $sql)) {
        header("Location: dashboard_kurir.php?pesan=success");
        exit();
    } else {
        echo "Error saving data: " . mysqli_error($connect);
    }
}

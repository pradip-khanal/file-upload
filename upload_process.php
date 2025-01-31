<?php
include '_db.php';

if(isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $image_name = $image['name'];
    $image_tmp = $image['tmp_name'];
    $image_size = $image['size'];
    $image_error = $image['error'];

    $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
    $image_ext = strtolower($image_ext);

    $allowed = ['jpg', 'jpeg', 'png'];

    if (in_array($image_ext, $allowed)) {
        if ($image_error === 0) {
            if ($image_size <= 1000000) {
                $image_name_new = uniqid('', true) . '.' . $image_ext;
                $image_destination = 'uploads/' . $image_name_new;

                if (move_uploaded_file($image_tmp, $image_destination)) {
                    $sql = "INSERT INTO images (image) VALUES ('$image_name_new')";
                    if (mysqli_query($conn, $sql)) {
                        echo 'Image uploaded successfully';
                    } else {
                        echo 'Database error: ' . mysqli_error($conn);
                    }
                } else {
                    echo 'Failed to upload image';
                }
            } else {
                echo 'Image size must be less than 1MB';
            }
        } else {
            echo 'Error uploading image';
        }
    } else {
        echo 'Only jpg, jpeg, and png files are allowed';
    }
} else {
    echo 'No image selected';
}

// Close database connection
mysqli_close($conn);
?>

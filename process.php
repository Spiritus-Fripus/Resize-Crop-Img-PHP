<?php

if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "") {
    $uploadDirectory = "test-resize";
    $uploadFilePath = $uploadDirectory . "-" . basename($_FILES['product_image']['name']);

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFilePath)) {
        echo "Image a été téléchargée avec succès.";
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}


// crop image

function resizeAndCrop($max_width, $max_height, $source_file, $dst_dir, $quality = 100)
{
    $imgsize = getimagesize($source_file);
    $width = $imgsize[0];
    $height = $imgsize[1];
    $extension = $imgsize['mime'];

    switch ($extension) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image = "imagegif";
            break;

        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image = "imagepng";
            $quality = 7;
            break;

        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image = "imagejpeg";
            $quality = 100;
            break;

        default:
            return false;
            break;
    }

    $dst_img = imagecreatetruecolor($max_width, $max_height);
    $src_img = $image_create($source_file);

    $width_new = round($height * $max_width / $max_height);
    $height_new = round($width * $max_height / $max_width);

    if ($width_new > $width) {
        $h_point = round((($height - $height_new) / 2));
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        $w_point = round((($width - $width_new) / 2));
        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }

    $image($dst_img, $dst_dir, $quality);

    if ($dst_img) imagedestroy($dst_img);
    if ($src_img) imagedestroy($src_img);
}

// infos sur l'image

function infosImage($path)
{
    $showInfo = getimagesize($path);
    if ($showInfo !== false) {
        $largeur = $showInfo[0];
        $hauteur = $showInfo[1];
        echo "Largeur de l'image : $largeur pixels<br>";
        echo "Hauteur de l'image : $hauteur pixels<br>";
    } else {
        echo "Impossible d'obtenir les informations sur l'image.";
        echo "<br>";
    }
}
// def des tailles

resizeAndCrop(1600, 900, $uploadFilePath, "test-resizeAndCrop-1600.jpg");
$newImg1600 = "test-resizeAndCrop-1600.jpg";
resizeAndCrop(800, 600, $uploadFilePath, "test-resizeAndCrop-800.jpg");
$newImg800 = "test-resizeAndCrop-800.jpg";
resizeAndCrop(400, 400, $uploadFilePath, "test-resizeAndCrop-400.jpg");
$newImg400 = "test-resizeAndCrop-400.jpg";
resizeAndCrop(150, 150, $uploadFilePath, "test-resizeAndCrop-150.jpg");
$newImg150 = "test-resizeAndCrop-150.jpg";

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div>
        <?php if (isset($_FILES['product_image']) && $_FILES['product_image']['name'] != "") { ?>
            <br>
            <?= infosImage($uploadFilePath) ?>
            <img src=" <?= $uploadFilePath; ?> " alt="image-originale">
            <br>
            <?= infosImage($newImg1600) ?>
            <img src="<?= $newImg1600 ?> " alt="New image 1600x900">
            <br>
            <?= infosImage($newImg800) ?>
            <img src="<?= $newImg800 ?> " alt="New image 800x600">
            <br>
            <?= infosImage($newImg400) ?>
            <img src="<?= $newImg400 ?> " alt="New image 400x400">
            <br>
            <?= infosImage($newImg150) ?>
            <img src="<?= $newImg150 ?>" alt="New image 150x150">
        <?php } else { ?>
            <p>Aucune image</p>
        <?php } ?>
    </div>
</body>

</html>
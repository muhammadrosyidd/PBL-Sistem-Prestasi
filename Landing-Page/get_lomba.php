<?php

$folderPath = '../Dashboard-SuperAdmin/pages-SuperAdmin/Poster Lomba/';


$files = scandir($folderPath);


$images = [];


foreach ($files as $file) {

    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
        $images[] = $folderPath . $file;
    }
}


foreach ($images as $image) {
    echo '<div class="slide-item">
            <div class="card">
                <img src="' . $image . '" alt="Gambar" class="img-fluid rounded">
            </div>
          </div>';
}
?>
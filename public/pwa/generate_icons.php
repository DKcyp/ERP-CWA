<?php
$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$src = __DIR__ . "/../logo.png";
if (!file_exists($src)) { $src = __DIR__ . "/../logo_cwa.png"; }
if (!file_exists($src)) { echo "Logo not found at: " . $src . "\n"; exit(1); }
foreach ($sizes as $size) {
    $img = imagecreatetruecolor($size, $size);
    $bg = imagecolorallocate($img, 37, 99, 235);
    imagefill($img, 0, 0, $bg);
    $source = imagecreatefrompng($src);
    $sw = imagesx($source);
    $sh = imagesy($source);
    $pad = (int)($size * 0.12);
    $inner = $size - ($pad * 2);
    imagecopyresampled($img, $source, $pad, $pad, 0, 0, $inner, $inner, $sw, $sh);
    $out = __DIR__ . "/icons/icon-" . $size . "x" . $size . ".png";
    imagepng($img, $out);
    imagedestroy($img);
    imagedestroy($source);
    echo "Created: $out\n";
}
echo "Done!\n";


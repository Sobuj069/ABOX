<?php

function createPwaIcon($size, $filename) {
    $img = imagecreatetruecolor($size, $size);
    imagealphablending($img, false);
    imagesavealpha($img, true);

    // Dark background #0e0d1b
    $bg = imagecolorallocate($img, 14, 13, 27);
    imagefill($img, 0, 0, $bg);

    imagealphablending($img, true);

    // Inner rounded rect / neon gradient badge in center
    $pad = (int)($size * 0.15);
    $badgeWidth = $size - ($pad * 2);
    $badgeHeight = $size - ($pad * 2);

    // Gradient effect simulation
    for ($y = $pad; $y <= $pad + $badgeHeight; $y++) {
        $percent = ($y - $pad) / $badgeHeight;
        $r = (int)(236 * (1 - $percent) + 147 * $percent);
        $g = (int)(72 * (1 - $percent) + 51 * $percent);
        $b = (int)(153 * (1 - $percent) + 234 * $percent);
        $lineColor = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, $pad, $y, $pad + $badgeWidth, $y, $lineColor);
    }

    // Draw soundwave bars in white in center
    $white = imagecolorallocate($img, 255, 255, 255);
    $centerX = $size / 2;
    $centerY = $size / 2;

    $bars = [
        [-4, 0.25],
        [-2, 0.55],
        [0, 0.85],
        [2, 0.5],
        [4, 0.25]
    ];

    $barWidth = (int)($size * 0.05);
    $maxH = $size * 0.4;
    $spacing = (int)($size * 0.08);

    foreach ($bars as $bar) {
        $x = $centerX + ($bar[0] * $spacing / 2) - ($barWidth / 2);
        $h = $maxH * $bar[1];
        $y1 = $centerY - ($h / 2);
        $y2 = $centerY + ($h / 2);
        imagefilledrectangle($img, (int)$x, (int)$y1, (int)($x + $barWidth), (int)$y2, $white);
    }

    imagepng($img, $filename);
    imagedestroy($img);
}

$iconDir = __DIR__ . '/../../public/images/icons';
if (!is_dir($iconDir)) mkdir($iconDir, 0777, true);

createPwaIcon(192, $iconDir . '/icon-192.png');
createPwaIcon(512, $iconDir . '/icon-512.png');
createPwaIcon(512, $iconDir . '/icon-maskable.png');

echo "PWA icons generated successfully!\n";

<?php
declare(strict_types=1);

require_once 'Rectangle.php';


$tst = get_extension_funcs("gd");
if(!$tst){
    die("no gd extension installed");
}

$image_size = (int)$argv[1];
$canvas = imagecreate($image_size,$image_size);
$white = imagecolorallocate($canvas, 255, 255, 255);
$black = imagecolorallocate($canvas, 0,0,0);

$white_background = imagefilledrectangle($canvas,0, 0, $image_size-1, $image_size-1, $white);

$rectangles_count = (int)$argv[2];
$min_size = (int)$argv[3];
$max_size = (int)$argv[4];
if(isOverSized($image_size, $min_size, $rectangles_count)){
    die("Rectangles total area exceed image size");
}

$rectangles = [];
$x1 = 0;
$y1 = 0;
$max_lowest = min($max_size, $image_size);
$x2 = rand($min_size, min($max_size, $image_size));

for($i = 0; $i < $rectangles_count; $i++) {
    $y2 = $y1 + rand($min_size, $max_lowest);

//    var_dump(['x1' =>$x1, 'y1' => $y1, 'x2' => $x2, 'y2' =>$y2]);
    $rectangle = new Rectangle($x1, $y1, $x2, $y2);
    if($rectangle->isOverlapMulti($rectangles)){
        trigger_error("overlapped", E_USER_ERROR);
    }

    if(imagerectangle($canvas, $x1, $y1, $x2, $y2, $black)){
        $rectangles[] = $rectangle;
    }

    if($x2 + 1 + $max_size < $image_size) {
        $x1 = $x2 + 1;
        $x2 = $x1 + rand($min_size, $max_size);
    } elseif ($x2 + 1 + $min_size < $image_size) {
        $x1 = $x2 + 1;
        $x2 = rand($x2 + 1 + $min_size, $image_size -1);
    } else {
        $x1 = 0;
        $x2 = rand($min_size, min($max_size, $image_size));
        $y1 = lowestPointAvailable($rectangles);
        if($y1 + $min_size > $image_size) {
            trigger_error("Exceeded image size", E_USER_ERROR);
        }

        $max_lowest = min($max_size, $image_size - ($y1 + $min_size));
    }
    $y2 = rand($y1 + $min_size, $y1 + $max_lowest);
}

imagejpeg($canvas, 'result.jpg');

function isOverSized(int $image_size, int $min_rectangle_size, int $num_of_rectangles): bool {
    $min_possible_area = $num_of_rectangles * ($min_rectangle_size * $min_rectangle_size);
    $image_area = $image_size * $image_size;
    return $image_area <= $min_possible_area;
}

/**
 * @param Rectangle[] $rectangles
 * @return int|null
 */
function lowestPointAvailable(array $rectangles): ?int {
    $y = 0;
    foreach ($rectangles as $rectangle) {
        $y = max($y, $rectangle->y2);
    }
    return $y + 1;
}
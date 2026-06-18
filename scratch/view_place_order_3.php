<?php
$content = file_get_contents('d:/bluewhyte/projects/Le-Marble-Gallery/app/Http/Controllers/MobileController.php');
if (preg_match('/public function placeOrder\b/is', $content, $m, PREG_OFFSET_CAPTURE)) {
    $start = $m[0][1];
    echo substr($content, $start + 3200, 1500);
}

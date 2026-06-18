<?php
$content = file_get_contents('d:/bluewhyte/projects/Le-Marble-Gallery/app/Http/Controllers/MobileController.php');
if (preg_match('/public function placeOrder\b/is', $content, $m, PREG_OFFSET_CAPTURE)) {
    $start = $m[0][1];
    // Find the next public function to find where this method ends
    if (preg_match('/public function \b/is', $content, $m2, PREG_OFFSET_CAPTURE, $start + 100)) {
        $end = $m2[0][1];
        echo substr($content, $start, $end - $start);
    } else {
        echo substr($content, $start, 5000);
    }
}

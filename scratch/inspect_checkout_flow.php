<?php
$content = file_get_contents('d:/bluewhyte/projects/Le-Marble-Gallery/app/Http/Controllers/MobileController.php');
// Search for orderSummary or placeOrder methods
if (preg_match('/public function orderSummary\b.*?\{/is', $content, $m, PREG_OFFSET_CAPTURE)) {
    $start = $m[0][1];
    echo "=== orderSummary ===\n" . substr($content, $start, 1500) . "\n\n";
}
if (preg_match('/public function placeOrder\b.*?\{/is', $content, $m, PREG_OFFSET_CAPTURE)) {
    $start = $m[0][1];
    echo "=== placeOrder ===\n" . substr($content, $start, 1500) . "\n\n";
}

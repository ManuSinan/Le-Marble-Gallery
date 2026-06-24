<?php
require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$inputFileType = 'Xlsx';
$inputFileName = __DIR__ . '/../products_demo_upload.xlsx';

echo "Loading $inputFileName...\n";
try {
    $reader = IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    // Print first 5 rows to inspect columns
    for ($i = 0; $i < min(10, count($rows)); $i++) {
        echo "Row $i: " . json_encode($rows[$i]) . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

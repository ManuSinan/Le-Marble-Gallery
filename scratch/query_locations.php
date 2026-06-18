<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Location;
$locs = Location::all();
foreach ($locs as $l) {
    echo "ID: {$l->id}, Name: {$l->name}, Delivery Charge: {$l->delivery_charge}, Min Cart: {$l->minimum_cart_amount}\n";
}

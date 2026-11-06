<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$osSection = \App\Models\Section::where('type', 'online_services_grid')->first();
echo "Title: " . $osSection->title . "\n";
print_r($osSection->content);

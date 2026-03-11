<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $response = \Illuminate\Support\Facades\Http::withHeaders([
        'x-dolarvzla-key' => '2320db1bc8b81274ea5552c7d0158512b39cecd259f05eb477a71ea6231d26d1',
    ])->get('https://api.dolarvzla.com/public/bcv/exchange-rate');

    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
    echo "Is Successful: " . ($response->successful() ? 'Yes' : 'No') . "\n";
    
    if (isset($response['current']['usd'])) {
        echo "Parsed Rate: " . $response['current']['usd'] . "\n";
    } else {
        echo "JSON Parse Error or missing 'current.usd' key.\n";
    }
} catch (\Exception $e) {
    echo "Exception Class: " . get_class($e) . "\n";
    echo "Exception Message: " . $e->getMessage() . "\n";
}

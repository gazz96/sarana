<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

echo "=== DASHBOARD TEST ===" . PHP_EOL;

try {
    // Get admin user
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    if (!$user) {
        echo "Admin user not found!" . PHP_EOL;
        exit(1);
    }
    
    echo "✅ User found: " . $user->name . PHP_EOL;
    echo "✅ User role: " . $user->role->name . PHP_EOL;
    
    // Authenticate as admin
    \Auth::login($user);
    echo "✅ User authenticated" . PHP_EOL;
    
    // Test DashboardService
    $service = new \App\Services\DashboardService();
    $stats = $service->getStatisticsForRole('admin');
    echo "✅ DashboardService works" . PHP_EOL;
    echo "✅ Statistics keys: " . implode(', ', array_keys($stats)) . PHP_EOL;
    
    // Test DashboardController
    $controller = new \App\Http\Controllers\DashboardController($service);
    $request = new \Illuminate\Http\Request();
    
    $response = $controller->index($request);
    echo "✅ Controller response type: " . get_class($response) . PHP_EOL;
    
    if (method_exists($response, 'getContent')) {
        $content = $response->getContent();
        if (str_contains($content, 'Dashboard')) {
            echo "✅ Dashboard content generated successfully" . PHP_EOL;
        } else {
            echo "❌ Dashboard content missing expected text" . PHP_EOL;
        }
        
        // Check for common errors
        if (str_contains($content, 'Error') || str_contains($content, 'Exception')) {
            echo "❌ Content contains error messages" . PHP_EOL;
            // Print first part of content with error
            $errorPos = strpos($content, 'Error');
            if ($errorPos !== false) {
                echo substr($content, $errorPos, 500) . PHP_EOL;
            }
        }
    }
    
    \Auth::logout();
    echo "🎉 Dashboard test completed successfully!" . PHP_EOL;
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}
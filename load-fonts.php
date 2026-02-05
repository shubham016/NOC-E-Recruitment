<?php

// Use Laravel's autoload
require __DIR__.'/vendor/autoload.php';

// Font details
$fontName = 'noto-sans-devanagari';
$fontDir = __DIR__ . '/storage/fonts/';

$fonts = [
    'normal' => $fontDir . 'NotoSansDevanagari-Regular.ttf',
    'bold' => $fontDir . 'NotoSansDevanagari-Bold.ttf',
];

echo "Loading fonts for DomPDF...\n";
echo "Font name: {$fontName}\n";
echo "Font directory: {$fontDir}\n\n";

// Check if fonts exist
foreach ($fonts as $type => $path) {
    if (!file_exists($path)) {
        die("ERROR: Font file not found: {$path}\n");
    }
    echo "✓ Found {$type} font: " . basename($path) . "\n";
}

echo "\n";

// Load fonts using DomPDF
try {
    $options = new \Dompdf\Options();
    $options->set('fontDir', $fontDir);
    $options->set('fontCache', $fontDir);
    
    $dompdf = new \Dompdf\Dompdf($options);
    
    // Generate a test PDF to trigger font caching
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <style>
            body { font-family: "noto sans devanagari", sans-serif; }
            .bold { font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>नमस्ते - Font Test</h1>
        <p>यो परीक्षण हो।</p>
        <p class="bold">बोल्ड फन्ट परीक्षण</p>
    </body>
    </html>
    ';
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    echo "✓ Fonts loaded successfully!\n";
    echo "✓ Font cache created in: {$fontDir}\n\n";
    
    // Save test PDF
    $output = $dompdf->output();
    $testFile = $fontDir . 'font-test.pdf';
    file_put_contents($testFile, $output);
    
    echo "✓ Test PDF created: {$testFile}\n";
    echo "  Open this file to verify the fonts are working!\n\n";
    
    echo "SUCCESS! Your fonts are ready to use.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
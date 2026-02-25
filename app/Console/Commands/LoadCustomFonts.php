<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Dompdf\Dompdf;
use Dompdf\Options;

class LoadCustomFonts extends Command
{
    protected $signature = 'fonts:load';
    protected $description = 'Load custom fonts for DomPDF';

    public function handle()
    {
        $fontDir = storage_path('fonts/');
        
        // Ensure directory exists
        if (!file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }
        
        // Copy fonts from public/storage/fonts to storage/fonts
        $publicFontPath = public_path('storage/fonts/');
        
        if (file_exists($publicFontPath . 'NotoSansDevanagari-Regular.ttf')) {
            copy(
                $publicFontPath . 'NotoSansDevanagari-Regular.ttf',
                $fontDir . 'NotoSansDevanagari-Regular.ttf'
            );
            copy(
                $publicFontPath . 'NotoSansDevanagari-Bold.ttf',
                $fontDir . 'NotoSansDevanagari-Bold.ttf'
            );
            
            $this->info('Fonts copied successfully!');
        } else {
            $this->error('Font files not found in public/storage/fonts/');
        }
        
        // Now register the fonts
        $options = new Options();
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontDir);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        try {
            // Register font family
            $dompdf->getOptions()->set('defaultFont', 'noto sans devanagari');
            
            $this->info('Fonts registered successfully!');
            $this->info('Font directory: ' . $fontDir);
            
        } catch (\Exception $e) {
            $this->error('Error registering fonts: ' . $e->getMessage());
        }
    }
}
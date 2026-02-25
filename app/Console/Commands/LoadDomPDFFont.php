<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LoadDomPDFFont extends Command
{
    protected $signature = 'dompdf:load-font';
    protected $description = 'Load custom fonts for DomPDF';

    public function handle()
    {
        $this->info('Setting up Noto Sans Devanagari font for DomPDF...');
        
        $fontDir = storage_path('fonts/');
        $regularFont = $fontDir . 'NotoSansDevanagari-Regular.ttf';
        $boldFont = $fontDir . 'NotoSansDevanagari-Bold.ttf';
        
        // Check if fonts exist
        if (!file_exists($regularFont)) {
            $this->error("Regular font not found at: {$regularFont}");
            
            // Try to copy from public/storage/fonts
            $publicFont = public_path('storage/fonts/NotoSansDevanagari-Regular.ttf');
            if (file_exists($publicFont)) {
                if (!file_exists($fontDir)) {
                    mkdir($fontDir, 0755, true);
                }
                copy($publicFont, $regularFont);
                $this->info("Copied Regular font from public/storage/fonts");
            } else {
                $this->error("Font not found in public/storage/fonts either!");
                return 1;
            }
        }
        
        if (!file_exists($boldFont)) {
            $this->error("Bold font not found at: {$boldFont}");
            
            // Try to copy from public/storage/fonts
            $publicFont = public_path('storage/fonts/NotoSansDevanagari-Bold.ttf');
            if (file_exists($publicFont)) {
                if (!file_exists($fontDir)) {
                    mkdir($fontDir, 0755, true);
                }
                copy($publicFont, $boldFont);
                $this->info("Copied Bold font from public/storage/fonts");
            } else {
                $this->error("Font not found in public/storage/fonts either!");
                return 1;
            }
        }
        
        $this->info("✓ Regular font: {$regularFont}");
        $this->info("✓ Bold font: {$boldFont}");
        $this->info('');
        $this->info('✓ Fonts are ready! DomPDF will use them automatically.');
        $this->info('Font directory: ' . $fontDir);
        
        return 0;
    }
}
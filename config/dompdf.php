<?php

return [
    'show_warnings' => false,
    'convert_entities' => true,
    
    'options' => [
        "font_dir" => storage_path('fonts/'),
        "font_cache" => storage_path('fonts/'),
        "temp_dir" => sys_get_temp_dir(),
        "chroot" => realpath(base_path()),
        
        "allowed_protocols" => [
            "file://" => ["rules" => []],
            "http://" => ["rules" => []],
            "https://" => ["rules" => []],
        ],
        
        "log_output_file" => storage_path('logs/dompdf.log'),
        "enable_php" => false,
        "enable_javascript" => true,
        //"enable_remote" => true,
        "enable_font_subsetting" => false, 
        "default_paper_size" => "a4",
        "default_paper_orientation" => "portrait",
        "isHtml5ParserEnabled" => true,
        "isRemoteEnabled" => true,
        "default_font" => "NotoSansDevanagari", 
        "dpi" => 96,
        "enable_font_kerning" => true,
        //"enable_html5_parser" => true,
    ],
];
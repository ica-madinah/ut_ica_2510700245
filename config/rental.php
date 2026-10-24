<?php
return [
    'name' => env('RENTAL_NAME', 'Prima Rent'),
    'whatsapp' => env('RENTAL_WHATSAPP', ''),
    'phone' => env('RENTAL_PHONE', ''),
    'email' => env('RENTAL_EMAIL', ''),
    'address' => env('RENTAL_ADDRESS', 'Alamat rental belum diatur'),
    'hours' => env('RENTAL_HOURS', 'Senin–Sabtu, 08.00–18.00 WIB'),
    'demo' => env('RENTAL_DEMO', true),
    'student_name' => env('STUDENT_NAME', '[NAMA LENGKAP]'),
    'student_nim' => env('STUDENT_NIM', '[NIM]'),
    'student_class' => env('STUDENT_CLASS', '[KODE KELAS]'),
];

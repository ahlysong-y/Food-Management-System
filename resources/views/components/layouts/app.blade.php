<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Food Management System' }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @filamentStyles
       
    </head>
    <body class="antialiased font-sans text-gray-900 bg-gray-50 dark:bg-gray-900 dark:text-white">
        {{ $slot }}
        
        @filamentScripts
      
    </body>
</html>

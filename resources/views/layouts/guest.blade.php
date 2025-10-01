<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" 
      style="background-image: url('{{ asset('images/bg-login.jpg') }}');">

    <div class="w-full max-w-md px-6 py-8 bg-white/90 backdrop-blur-md shadow-lg rounded-2xl">
        {{ $slot }}
    </div>

</body>
</html>

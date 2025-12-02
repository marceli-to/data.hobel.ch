<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-y-scroll">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Products - {{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 h-full">
  <div id="app" class="flex flex-col min-h-screen"></div>
</body>
</html>

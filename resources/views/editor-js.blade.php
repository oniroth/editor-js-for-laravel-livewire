<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/editor.js'])
    <script>
        window.siteUrl = "{{ getenv('APP_URL') }}";
    </script>
</head>
<body
    class="">

<div class="w-[90%] mr-20">
    <div id="editorjs"></div>
</div>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css', env('MIX_BASE')) }}" data-skin="default">
    <script src="{{ mix('js/app.js', env('MIX_BASE')) }}" defer></script>
</head>
<body>
@inertia
</body>
</html>
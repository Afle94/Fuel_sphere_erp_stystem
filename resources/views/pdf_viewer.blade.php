<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'FuelTracker PDF' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: #202124;
        }

        .pdf-frame {
            width: 100%;
            height: 100%;
            display: block;
            border: 0;
        }
    </style>
</head>
<body>
    <iframe class="pdf-frame" src="{{ $pdfUrl }}" title="{{ $title ?? 'FuelTracker PDF' }}"></iframe>
</body>
</html>

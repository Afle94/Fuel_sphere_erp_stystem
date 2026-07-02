<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sample['product_label'] }} Sample Preview</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">
    <style>
        :root {
            --bg: #f4f7fb;
            --panel: #ffffff;
            --ink: #172033;
            --muted: #657089;
            --line: #dce3ee;
            --primary: {{ $theme['primary'] ?? '#0f766e' }};
            --primary-dark: {{ $theme['primaryDark'] ?? '#115e59' }};
            --primary-shine: {{ $theme['accent'] ?? '#2dd4bf' }};
            --accent: {{ $theme['accent'] ?? '#f59e0b' }};
            --theme-bg-end: {{ $theme['bgEnd'] ?? '#eef5f3' }};
            --theme-glow: color-mix(in srgb, var(--primary) 22%, transparent);
            --theme-brand-start: var(--primary-dark);
            --theme-brand-end: var(--primary);
        }

        * { box-sizing: border-box; }

        @page {
            size: A4;
            margin: 6mm;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--ink);
            background: radial-gradient(circle at top left, color-mix(in srgb, var(--primary) 18%, transparent), transparent 32rem),
                linear-gradient(135deg, #f8fbff 0%, var(--bg) 55%, var(--theme-bg-end) 100%);
            font-family: Arial, Helvetica, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .preview-header {
            position: sticky;
            top: 0;
            z-index: 5;
            min-height: 58px;
            display: grid;
            grid-template-columns: minmax(220px, 1fr) auto minmax(220px, 1fr);
            align-items: center;
            gap: 16px;
            padding: 10px 16px;
            color: #ffffff;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.18) 0%, transparent 32%),
                linear-gradient(135deg, var(--theme-brand-start), var(--theme-brand-end));
            box-shadow: 0 10px 30px rgba(23, 32, 51, 0.12);
        }

        .preview-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            font-weight: 900;
        }

        .preview-logo {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: #ffffff;
            object-fit: cover;
            padding: 2px;
        }

        .preview-header h1 {
            justify-self: center;
            margin: 0;
            font-size: 20px;
            text-align: center;
        }

        .preview-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .preview-actions button,
        .preview-actions a {
            min-height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 8px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            cursor: pointer;
            font: inherit;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .preview-actions a:hover,
        .preview-actions button:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .preview-page {
            width: min(100% - 28px, 1120px);
            margin: 14px auto;
        }

        .sample-preview-workspace.app-shell-with-sidebar {
            width: calc(100vw - 24px);
            min-height: calc(100vh - 82px);
            grid-template-columns: 300px minmax(0, 1fr);
            margin: 12px;
            border-radius: 12px;
        }

        .sample-preview-workspace.app-shell-with-sidebar.menu-collapsed {
            grid-template-columns: 64px minmax(0, 1fr);
        }

        .sample-preview-workspace .preview-page {
            width: 100%;
            height: calc(100vh - 84px);
            margin: 0;
            padding: 14px;
            overflow: auto;
        }

        .sample-preview-workspace .preview-grid {
            width: min(100%, 1050px);
            margin: 0 auto;
        }

        .print-title {
            display: none;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .preview-card {
            min-height: 360px;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--line);
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 14px 34px rgba(23, 32, 51, 0.10);
        }

        .preview-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-dark), var(--primary) 62%, var(--primary-shine));
        }

        .preview-card-title {
            font-size: 18px;
            font-weight: 900;
        }

        .preview-copy {
            font-size: 12px;
            font-weight: 800;
            opacity: 0.88;
        }

        .preview-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            padding: 14px;
        }

        .preview-field {
            min-height: 58px;
            display: grid;
            align-content: center;
            gap: 4px;
            padding: 9px 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fbfcfe;
        }

        .preview-field.wide {
            grid-column: span 2;
        }

        .preview-label {
            color: var(--muted);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .preview-value {
            color: var(--ink);
            font-size: 15px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .preview-header {
                display: none;
            }

            .sample-preview-workspace.app-shell-with-sidebar {
                display: block;
                width: 100%;
                min-height: 0;
                margin: 0;
                border: 0;
                box-shadow: none;
            }

            .sample-preview-workspace .sidebar {
                display: none;
            }

            .sample-preview-workspace .preview-page {
                height: auto;
                padding: 0;
                overflow: visible;
            }

            .preview-page {
                width: 100%;
                margin: 0;
            }

            .print-title {
                display: block;
                margin-bottom: 3mm;
                padding: 2mm 3mm;
                border: 0.35mm solid var(--line);
                text-align: center;
            }

            .print-title h1 {
                margin: 0 0 1mm;
                color: var(--primary-dark);
                font-size: 13pt;
            }

            .print-title p {
                margin: 0;
                font-size: 8pt;
            }

            .preview-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 3mm;
            }

            .preview-card {
                min-height: 0;
                height: 130mm;
                border: 0.35mm solid var(--line);
                border-radius: 2mm;
                break-inside: avoid;
                box-shadow: none;
            }

            .preview-card-head {
                padding: 2mm 3mm;
            }

            .preview-card-title {
                font-size: 10pt;
            }

            .preview-copy {
                font-size: 6.8pt;
            }

            .preview-fields {
                gap: 1.4mm;
                padding: 2mm;
            }

            .preview-field {
                min-height: 8.2mm;
                padding: 1mm 1.4mm;
                border-radius: 1.4mm;
            }

            .preview-label {
                font-size: 5.4pt;
            }

            .preview-value {
                font-size: 7.2pt;
            }
        }

        @media screen and (max-width: 760px) {
            .preview-header { grid-template-columns: 1fr; }
            .preview-header h1,
            .preview-actions,
            .preview-brand { justify-self: center; }
            .preview-grid { grid-template-columns: 1fr; }
            .preview-fields { grid-template-columns: 1fr; }
            .preview-field.wide { grid-column: auto; }
        }

        @if (! empty($isPdf))
            body {
                background: #ffffff;
                color: #172033;
            }

            .preview-card-head {
                color: #ffffff;
                background: {{ $theme['primary'] ?? '#0f766e' }};
            }

            .print-title h1 {
                color: {{ $theme['primaryDark'] ?? '#115e59' }};
            }
        @endif
    </style>
    @if (empty($isPdf))
        @include('partials.theme')
    @endif
</head>
<body>
    @php
        $fields = [
            ['Date', $sample['date']],
            ['Tanker', $sample['tanker']],
            ['Transport', $sample['transport']],
            ['Oil Company', $sample['oil_company']],
            ['Invoice No.', $sample['invoice_no']],
            ['Product', $sample['product'] ?: $sample['product_label']],
            ['Temperature', number_format((float) $sample['temp'], 2)],
            ['Base Density', number_format((float) $sample['base_density'], 4)],
            ['Chart Value', number_format((float) $sample['value'], 4)],
            ['Sample', $sample['sample']],
            ['Invoice Sample', $sample['invoice_sample']],
            ['Plastic Seal', $sample['plastic_seal']],
            ['Aluminium Seal', $sample['aluminium_seal']],
        ];
    @endphp

    <header class="preview-header">
        <div class="preview-brand">
            <img src="{{ asset('images/fueltracker-logo.jpeg') }}" alt="" class="preview-logo">
            <span>FuelTracker</span>
        </div>
        <h1>{{ $sample['product_label'] }} Sample Preview</h1>
        <div class="preview-actions">
            <a href="#" id="samplePreviewPdfBtn">PDF</a>
            <button type="button" onclick="window.print()">Print</button>
            <button type="button" onclick="window.close()">Close</button>
        </div>
    </header>

    <div class="app-shell-with-sidebar sample-preview-workspace" id="dashboardPage">
        @include('partials.fueltracker-menu')

        <main class="preview-page">
            <section class="print-title">
                <h1>{{ $sample['product_label'] }} Sample Preview</h1>
                <p>Date: {{ $sample['date'] ?: '-' }} | Tanker: {{ $sample['tanker'] ?: '-' }} | Invoice: {{ $sample['invoice_no'] ?: '-' }}</p>
            </section>

            <div class="preview-grid">
                @for ($copy = 1; $copy <= 4; $copy++)
                    <section class="preview-card">
                        <div class="preview-card-head">
                            <div class="preview-card-title">{{ $sample['product_label'] }} Sample</div>
                            <div class="preview-copy">Preview {{ $copy }}</div>
                        </div>
                        <div class="preview-fields">
                            @foreach ($fields as [$label, $value])
                                <div class="preview-field {{ in_array($label, ['Oil Company', 'Product'], true) ? 'wide' : '' }}">
                                    <span class="preview-label">{{ $label }}</span>
                                    <span class="preview-value">{{ $value !== '' && $value !== null ? $value : '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endfor
            </div>
        </main>
    </div>

    @if (empty($isPdf))
        <script>
            (function () {
                var pdfButton = document.getElementById('samplePreviewPdfBtn');
                var theme = 'default';

                try {
                    theme = localStorage.getItem('fueltracker:theme') || 'default';
                } catch (error) {
                    theme = 'default';
                }

                if (pdfButton) {
                    var url = new URL(window.location.href);
                    url.searchParams.set('theme', theme);
                    url.searchParams.set('raw_pdf', '1');
                    pdfButton.href = url.toString();
                    pdfButton.target = '_blank';
                    pdfButton.rel = 'noopener';
                }
            })();
        </script>
    @endif
</body>
</html>

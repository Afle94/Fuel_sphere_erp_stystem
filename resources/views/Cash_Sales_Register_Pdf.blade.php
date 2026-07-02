<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Cash Sales Register
    </title>

    <link rel="icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">

    <link rel="shortcut icon" type="image/jpeg" href="{{ asset('images/fueltracker-logo.jpeg') }}">

    @php

        $theme = array_merge([

            'primary' => '#0f766e',
            'primaryDark' => '#115e59',
            'accent' => '#f59e0b',
            'bgEnd' => '#eef5f3',

        ], $theme ?? []);

    @endphp

    <style>

        body {
            margin: 0;
            color: #172033;
            background: #ffffff;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
        }

        .report-header {
            margin-bottom: 14px;
            padding: 12px;
            color: #ffffff;
            background: {{ $theme['primaryDark'] }};
            border-bottom: 3px solid {{ $theme['accent'] }};
        }

        h1 {
            margin: 0 0 4px;
            color: #ffffff;
            font-size: 20px;
        }

        .report-meta {
            color: #f8fbff;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px 5px;
            border: 1px solid #dce3ee;
            vertical-align: top;
        }

        th {
            color: #ffffff;
            background: {{ $theme['primary'] }};
            font-size: 9px;
            text-align: left;
        }

        td {
            background: #ffffff;
            font-size: 8.5px;
        }

        .number-cell {
            text-align: right;
        }

        .total-row td {
            color: #ffffff;
            background: {{ $theme['primaryDark'] }};
            font-weight: bold;
        }

    </style>

</head>

<body>

    <div class="report-header">

        <h1>
            Cash Sales Register
        </h1>

        <div class="report-meta">

            Period:
            {{ $periodLabel }}

            |

            Generated on
            {{ now()->format('d M Y h:i A') }}

            |

            Total Entries:
            {{ $entries->count() }}

            @if($search)

                |

                Search:
                {{ $search }}

            @endif

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th>
                    Sr.
                </th>

                <th>
                    Date
                </th>

                <th>
                    Slip No
                </th>

                <th>
                    Ref No
                </th>

                <th>
                    Item Name
                </th>

                <th class="number-cell">
                    Quantity
                </th>

                <th class="number-cell">
                    Rate
                </th>

                <th class="number-cell">
                    Amount
                </th>

                <th>
                    Narration
                </th>

            </tr>

        </thead>

        <tbody>

            @foreach ($entries as $entry)

                <tr>

                    <td>
                        {{ $loop->iteration }}
                    </td>

                    <td>
                        {{ date('d M Y', strtotime($entry->date)) }}
                    </td>

                    <td>
                        {{ $entry->slip_no ?: '-' }}
                    </td>

                    <td>
                        {{ $entry->ref_no ?: '-' }}
                    </td>

                    <td>
                        {{ $entry->item_name ?: '-' }}
                    </td>

                    <td class="number-cell">
                        {{ number_format((float) $entry->quantity, 2) }}
                    </td>

                    <td class="number-cell">
                        {{ number_format((float) $entry->rate, 2) }}
                    </td>

                    <td class="number-cell">
                        {{ number_format((float) $entry->amount, 2) }}
                    </td>

                    <td>
                        {{ $entry->Narration ?: '-' }}
                    </td>

                </tr>

            @endforeach

            <tr class="total-row">

                <td colspan="5">
                    Total
                </td>

                <td class="number-cell">
                    {{ number_format((float) $entries->sum('quantity'), 2) }}
                </td>

                <td>
                </td>

                <td class="number-cell">
                    {{ number_format((float) $entries->sum('amount'), 2) }}
                </td>

                <td>
                </td>

            </tr>

        </tbody>

    </table>

</body>

</html>
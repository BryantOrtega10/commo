<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 100px 20px;
        }

        body {
            font-size: 8pt;
            font-family: sans-serif;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 12px;
            color: #8f8f8f;
        }

        table {
            /* width: 100%; */
            border-collapse: collapse;
        }

        td {
            border-bottom: 1px solid #E9E9E9;
            padding: 2px 5px;
        }

        th {
            padding: 2px 10px;
            text-align: left;
        }

        thead {
            background-color: #8DB4E2;
        }

        .orange {
            background-color: #FE6C01;
        }

        .statement-total {
            background-color: #D6DFEC;
            font-size: 10pt;
        }

        .subtotal {
            background-color: #D6DFEC;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .col-3 {
            display: inline-block;
            width: 24%;
            vertical-align: middle;
        }

        .col-6 {
            display: inline-block;
            width: 49%;
            vertical-align: middle;
        }
    </style>
</head>

<body>

    <header>
        <div class="col-3"></div>
        <div class="col-6 text-center">
            <h1 style="font-size: 14pt;">{{ date('F Y') }} Commission
                Statement<br>{{ date('F Y', strtotime($statement_date)) }} Sales</h1>
        </div>
        <div class="col-3 text-right">
            {{ $agent->first_name }} {{ $agent->last_name }} - PEN<br>
            {{ date('m/d/Y', strtotime($statement_date)) }}<br>
            {{ $agent->sales_region?->name }}
        </div>
    </header>

    <footer style="text-align: left;">
        Generated {{ date('l, M d, Y') }}
    </footer>

    <main>
        @php
            $count = 0;
        @endphp
        <b style="font-size: 13pt;">Summary</b>
        <br><br>
        <table>
            <thead>
                <tr>
                    <th>Agent Type</th>
                    <th>Carrier</th>
                    <th>Agent Name</th>
                    <th>Compensation Type</th>
                    <th>Transaction Count</th>
                    <th>Commission Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($summary['items'] as $agentNumberID => $row)
                    @foreach ($row as $commTypeId => $item)
                        <tr>
                            <td>{{ $item['agent_type'] }}</td>
                            <td>{{ $item['carrier'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['comm_type'] }}</td>
                            <td>{{ $item['transaction_count'] }}</td>
                            <td style="text-align: right;">$ {{ number_format($item['commission_amount'], 2) }}
                        </tr>
                    @endforeach
                @endforeach

                <tr>
                    <td colspan="4">Subtotal</td>
                    <td>{{ $summary['transaction_count'] }}</td>
                    <td style="text-align: right;">$ {{ number_format($summary['commission_amount'], 2) }}</td>
                </tr>
                <tr class="statement-total">
                    <th colspan="5">Statement Total</th>
                    <th style="text-align: right;">$
                        {{ number_format($summary['commission_amount'], 2) }}</th>
                </tr>
            </tbody>
        </table>
        <div style="page-break-after: always"></div>

        <table>
            <thead>
                <tr>
                    <th>Subscriber</th>
                    <th>Prod Type</th>
                    <th>Plan Description</th>
                    <th># of Memb</th>
                    <th>Policy #</th>
                    <th>Orig Eff Date</th>
                    <th>Account. Date</th>
                    <th>Cancel Date</th>
                    <th>Tier</th>
                    <th>Region</th>
                    <th>Writing Agent</th>
                    <th>Agent Comm</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $item)
                    <tr>
                        <td>{{ $item['subscriber'] }}</td>
                        <td>{{ $item['prod_type'] }}</td>
                        <td>{{ $item['plan'] }}</td>
                        <td>{{ $item['n_members'] }}</td>
                        <td>{{ $item['policy_num'] }}</td>
                        <td>
                            @if (isset($item['orig_eff']))
                                {{ date('m/d/Y', strtotime($item['orig_eff'])) }}
                            @endif
                        </td>
                        <td>
                            @if (isset($item['account_date']))
                                {{ date('m/d/Y', strtotime($item['account_date'])) }}
                            @endif
                        </td>
                        <td>
                            @if (isset($item['cancel_date']))
                                {{ date('m/d/Y', strtotime($item['cancel_date'])) }}
                            @endif
                        </td>
                        <td>{{ $item['tier'] }}</td>
                        <td>{{ $item['region'] }}</td>
                        <td>{{ $item['writting_agent'] }}</td>
                        <td style="text-align: right;">$
                            {{ number_format($item['agent_commision'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

</body>

</html>

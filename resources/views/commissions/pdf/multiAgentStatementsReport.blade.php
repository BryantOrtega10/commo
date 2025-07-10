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
        <div class="col-3 text-right">{{ date('m/d/Y', strtotime($statement_date)) }}</div>
    </header>

    <footer style="text-align: left;">
        Generated {{ date('l, M d, Y') }}
    </footer>

    <main>
        @php
            $count = 0;
        @endphp
        @foreach ($finalData as $row => $item)
            @php
                $count++;
            @endphp
            <b style="font-size: 13pt;">Summary - {{ $item['name'] }} </b>
            <br><br>
            <table>
                <thead>
                    <tr>
                        <th>Carrier</th>
                        <th>Agent Number - Status</th>
                        <th>Transaction Count</th>
                        <th>Commission Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subTotalTransaction = 0;
                        $subTotal = 0;
                    @endphp
                    @foreach ($item['agentNumbers'] as $agentNumberId => $agentNumberItem)
                        <tr>
                            <td>{{ $agentNumberItem['carrier'] }}</td>
                            <td>{{ $agentNumberItem['number'] }} - {{ $agentNumberItem['status'] }}</td>
                            <td>{{ sizeof($agentNumberItem['statements_items'] ?? []) }}</td>
                            <td style="text-align: right;">$
                                {{ number_format($agentNumberItem['commission_amount'] ?? 0, 2) }}</td>
                        </tr>
                        @php
                            $subTotalTransaction += sizeof($agentNumberItem['statements_items'] ?? []);
                            $subTotal += $agentNumberItem['commission_amount'] ?? 0;
                        @endphp
                    @endforeach
                    <tr class="orange">
                        <td colspan="2">Subtotal</td>
                        <td>{{ $subTotalTransaction }}</td>
                        <td style="text-align: right;">$ {{ number_format($subTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">Adjustments</td>
                        <td style="text-align: right;">$ {{ number_format($item['ammount_adjustments'], 2) }}</td>
                    </tr>
                    <tr class="statement-total">
                        <th colspan="3">Statement Total</th>
                        <th style="text-align: right;">$
                            {{ number_format($subTotal + $item['ammount_adjustments'], 2) }}</th>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>

            @foreach ($item['agentNumbers'] as $agentNumberId => $agentNumberItem)
                @isset($agentNumberItem['statements_items'])
                    <b style="font-size: 12pt;">{{ $item['name'] }}  - {{ $agentNumberItem['pay_agency'] }} </b><br>
                    {{ $agentNumberItem['carrier'] }} - {{ $agentNumberItem['number'] }}

                    <table>
                        <thead>
                            <tr>
                                <th>Subscriber</th>
                                <th>Comp Type</th>
                                <th>Prod Type</th>
                                <th>Plan Description</th>
                                <th>#&nbsp;of&nbsp;Mem</th>
                                <th>Policy #</th>
                                <th>Orig Eff Date</th>
                                <th>Accouning Date</th>
                                <th>Cancel Date</th>
                                <th>Tier</th>
                                <th>Region</th>
                                <th>Agent Comm</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subTotal = 0;
                            @endphp
                            @foreach ($agentNumberItem['statements_items'] as $itemStatement)
                                @php
                                    $policy = $itemStatement->commission_transaction->policy;
                                    $subTotal += $itemStatement->comp_amount;
                                @endphp
                                <tr>
                                    <td>{{ $policy->customer->first_name }} {{ $policy->customer->last_name }}</td>
                                    <td>{{ $itemStatement->commission_transaction?->compensation_type?->name }}</td>
                                    <td>{{ $policy->product?->product_type?->name }}</td>
                                    <td>{{ $policy->product?->description }}</td>
                                    <td>{{ $policy->num_dependents }}</td>
                                    <td>{{ $policy->contract_id }}</td>
                                    <td>
                                        @if (isset($policy->original_effective_date))
                                            {{ date('m/d/Y', strtotime($policy->original_effective_date)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($itemStatement->commission_transaction->accounting_date))
                                            {{ date('m/d/Y', strtotime($itemStatement->commission_transaction->accounting_date)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($policy->cancel_date))
                                            {{ date('m/d/Y', strtotime($policy->cancel_date)) }}
                                        @endif
                                    </td>
                                    <td>{{ $policy->product?->tier?->name }}</td>
                                    <td>{{ $policy->county?->region?->name }}</td>
                                    <td style="text-align: right;">$ {{ number_format($itemStatement->comp_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="subtotal text-right">
                                <td colspan="11" class="subtotal text-right">
                                <td>$ {{ number_format($subTotal, 2) }}</td>
                            </tr>

                        </tbody>
                    </table>
                    <br><br>
                @endisset
            @endforeach

            @isset($item['adjustments'])
                <h3>Adjustments</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Comp. Type</th>
                            <th>Agent #</th>
                            <th>Description</th>
                            <th>Agent Comm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subTotal = 0;
                        @endphp
                        @foreach ($item['adjustments'] as $agentNumber => $itemAdjustment)
                            @foreach ($itemAdjustment as $compensation_type => $itemAdjustment2)
                                @foreach ($itemAdjustment2 as $description => $comp_amount)
                                    <tr>
                                        <td>{{ $compensation_type }}</td>
                                        <td>{{ $agentNumber }}</td>
                                        <td>{{ $description }}</td>
                                        <td class="text-right">$ {{ number_format($comp_amount, 2) }}</td>
                                    </tr>
                                    @php
                                        $subTotal += $comp_amount;
                                    @endphp
                                @endforeach
                            @endforeach
                        @endforeach

                        <tr class="subtotal text-right">
                            <td colspan="3">
                            <td>$ {{ number_format($subTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endisset
            @if (sizeof($finalData) > $count)
                <div style="page-break-after: always"></div>
            @endif
        @endforeach
    </main>

</body>

</html>

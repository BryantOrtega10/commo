<table>
    <thead>
        <tr>
            <th>Carrier</th>
            <th>Agent Number</th>
            <th>Name</th>
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
        @foreach ($data as $item)
            @foreach ($item['items'] as $itemStatement)
                @php
                    $policy = $itemStatement->commission_transaction->policy;
                @endphp
                <tr>
                    <td>{{ $item["carrier"] }}</td>
                    <td>{{ $item["number"] }}</td>
                    <td>{{ $item["name"] }}</td>
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
                    <td>{{ $itemStatement->comp_amount }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

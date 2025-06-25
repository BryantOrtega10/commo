<table>
    <thead>
        <tr>
            <th>Carrier</th>
            <th>Agent Number - Status</th>
            <th>Transactions Count</th>
            <th>Commission Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['items'] as $item)
            <tr>
                <td>{{ $item['carrier'] }}</td>
                <td>{{ $item['number_status'] }}</td>
                <td>{{ $item['transactions_count'] }}</td>
                <td>{{ $item['commission_amount'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Subtotal</td>
            <td>{{ $data["summary"]["subTotalTransaction"] }}</td>
            <td>{{ $data["summary"]["subTotal"] }}</td>
        </tr>
        <tr>
            <td colspan="3">Adjustments</td>
            <td>{{ $data["summary"]["adjustments"] }}</td>
        </tr>
        <tr>
            <td colspan="3">Statement Total</td>
            <td>{{ $data["summary"]["total"] }}</td>
        </tr>
    </tfoot>
</table>

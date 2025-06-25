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
        @foreach ($data as $item)
            <tr>
                <td>{{$item['agentNumber']}}</td>
                <td>{{$item['compensation_type']}}</td>
                <td>{{$item['description']}}</td>
                <td>{{$item['comp_amount']}}</td>
            </tr>
        @endforeach
    </tbody>
</table>

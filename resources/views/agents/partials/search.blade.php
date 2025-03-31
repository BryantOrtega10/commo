<div class="search-agent-filters">   
        <div class="row align-items-end">
            <div class="col-3 mb-3">
                <input type="button" class="btn btn-outline-danger clear-selected-agent" value="Clear selected" />
            </div>
        </div>   
</div>
<div class="search-agent-table-container">
    <table class="table table-striped min-w-100" id="search-agent-table" data-url="{{route('agents.search')}}">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
                <tr>
                    <td>{{ $agent->id }}</td>
                    <td><b>{{ $agent->first_name }} {{ $agent->last_name }}</b></td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->phone }}</td>
                    <td><button class="search-agent-btn-select btn btn-outline-primary" data-name="{{ $agent->first_name }} {{ $agent->last_name }}" data-id="{{ $agent->id }}">Select</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
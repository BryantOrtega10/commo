<div class="search-customer-filters">
    <div class="row">
        <div class="col-3 mb-3">
            <input type="button" class="btn btn-outline-danger clear-selected-customer" value="Clear selected" />
        </div>
    </div>

</div>
<div class="search-customer-table-container">
    <table class="table table-striped min-w-100" id="search-customer-table" data-url="{{route('customers.search')}}">
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
            @foreach ($customers as $customer)
                <tr>
                    <th>{{ $customer->id }}</th>
                    <td><b>{{ $customer->first_name }} {{ $customer->last_name }}</b></td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td><button class="search-customer-btn-select btn btn-outline-primary"
                            data-name="{{ $customer->first_name }} {{ $customer->last_name }}"
                            data-id="{{ $customer->id }}">Select</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

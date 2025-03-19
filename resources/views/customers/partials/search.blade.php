<div class="search-customer-filters">
    <form class="search-customer-form" method="POST" action="{{route('customers.search')}}">
        <div class="row align-items-end">
            <div class="col-3">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Email:">
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone"
                        placeholder="Phone:">
                </div>
            </div>
            <div class="col-3 mb-3">
                <input type="submit" class="btn btn-outline-primary mr-2" value="Search" />
                <button type="reset" class="btn btn-secondary mr-2"><i class="fas fa-redo"></i></button>
            </div>
            <div class="col-3 mb-3">
                <input type="button" class="btn btn-outline-danger clear-selected-customer" value="Clear selected" />
            </div>
        </div>   
    </form>
</div>
<div class="search-customer-table-container">
    <table class="table table-striped datatable min-w-100" id="search-customer-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td><b>{{ $customer->first_name }} {{ $customer->last_name }}</b></td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td><button class="search-customer-btn-select btn btn-outline-primary" data-name="{{ $customer->first_name }} {{ $customer->last_name }}" data-id="{{ $customer->id }}">Select</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
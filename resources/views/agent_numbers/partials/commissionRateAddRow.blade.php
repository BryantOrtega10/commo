<tr class="fields-row add-new-row">
    <td>
        <select id="business_segment" name="business_segment"
            class="form-control @if ($errors->addNewRate->has('business_segment')) is-invalid @endif">
            <option value=""></option>
            @foreach ($business_segments as $business_segment)
                <option value="{{ $business_segment->id }}" @if (old('business_segment') == $business_segment->id) selected @endif>
                    {{ $business_segment->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('business_segment'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('business_segment') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="business_type" name="business_type" class="form-control @if ($errors->addNewRate->has('business_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($business_types as $business_type)
                <option value="{{ $business_segment->id }}" @if (old('business_type') == $business_type->id) selected @endif>
                    {{ $business_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('business_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('business_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="compensation_type" name="compensation_type"
            class="form-control @if ($errors->addNewRate->has('compensation_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($compensation_types as $compensation_type)
                <option value="{{ $compensation_type->id }}" @if (old('compensation_type') == $compensation_type->id) selected @endif>
                    {{ $compensation_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('compensation_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('compensation_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="amf_compensation_type" name="amf_compensation_type"
            class="form-control @if ($errors->addNewRate->has('amf_compensation_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($amf_compensation_types as $amf_compensation_type)
                <option value="{{ $amf_compensation_type->id }}" @if (old('amf_compensation_type') == $amf_compensation_type->id) selected @endif>
                    {{ $amf_compensation_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('amf_compensation_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('amf_compensation_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="plan_type" name="plan_type" class="form-control @if ($errors->addNewRate->has('plan_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($plan_types as $plan_type)
                <option value="{{ $plan_type->id }}" @if (old('plan_type') == $plan_type->id) selected @endif>
                    {{ $plan_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('plan_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('plan_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="product" name="product" class="form-control @if ($errors->addNewRate->has('product')) is-invalid @endif">
            <option value=""></option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @if (old('product') == $product->id) selected @endif>
                    {{ $product->description }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('product'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('product') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="product_type" name="product_type" class="form-control @if ($errors->addNewRate->has('product_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($product_types as $product_type)
                <option value="{{ $product_type->id }}" @if (old('product_type') == $product_type->id) selected @endif>
                    {{ $product_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('product_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('product_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="tier" name="tier" class="form-control @if ($errors->addNewRate->has('tier')) is-invalid @endif">
            <option value=""></option>
            @foreach ($tiers as $tier)
                <option value="{{ $tier->id }}" @if (old('tier') == $tier->id) selected @endif>
                    {{ $tier->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('tier'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('tier') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="county" name="county" class="form-control @if ($errors->addNewRate->has('county')) is-invalid @endif">
            <option value=""></option>
            @foreach ($counties as $county)
                <option value="{{ $county->id }}" @if (old('county') == $county->id) selected @endif>
                    {{ $county->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('county'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('county') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="region" name="region" class="form-control @if ($errors->addNewRate->has('region')) is-invalid @endif">
            <option value=""></option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" @if (old('region') == $region->id) selected @endif>
                    {{ $region->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('region'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('region') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="text" class="form-control @if ($errors->addNewRate->has('policy_contract_id')) is-invalid @endif"
            id="policy_contract_id" name="policy_contract_id" value="{{ old('policy_contract_id') }}">
        @if ($errors->addNewRate->has('policy_contract_id'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('policy_contract_id') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="tx_type" name="tx_type" class="form-control @if ($errors->addNewRate->has('tx_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($txTypes as $tx_type)
                <option value="{{ $tx_type->id }}" @if (old('tx_type') == $tx_type->id) selected @endif>
                    {{ $tx_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('tx_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('tx_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="agentType" name="agentType" class="form-control @if ($errors->addNewRate->has('agentType')) is-invalid @endif">
            <option value=""></option>
            @foreach ($agentTypes as $row => $agentType)
                <option value="{{ $row }}" @if (old('agentType') == $row && old('agentType') !== null) selected @endif>
                    {{ $agentType }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('agentType'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('agentType') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('submit_from')) is-invalid @endif" id="submit_from"
            name="submit_from" value="{{ old('submit_from') }}">
        @if ($errors->addNewRate->has('submit_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('submit_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('submit_to')) is-invalid @endif" id="submit_to"
            name="submit_to" value="{{ old('submit_to') }}">
        @if ($errors->addNewRate->has('submit_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('submit_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('statement_from')) is-invalid @endif" id="statement_from"
            name="statement_from" value="{{ old('statement_from') }}">
        @if ($errors->addNewRate->has('statement_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('statement_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('statement_to')) is-invalid @endif" id="statement_to"
            name="statement_to" value="{{ old('statement_to') }}">
        @if ($errors->addNewRate->has('statement_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('statement_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('original_effective_from')) is-invalid @endif"
            id="original_effective_from" name="original_effective_from"
            value="{{ old('original_effective_from') }}">
        @if ($errors->addNewRate->has('original_effective_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('original_effective_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('original_effective_to')) is-invalid @endif"
            id="original_effective_to" name="original_effective_to" value="{{ old('original_effective_to') }}">
        @if ($errors->addNewRate->has('original_effective_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('original_effective_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('benefit_effective_from')) is-invalid @endif"
            id="benefit_effective_from" name="benefit_effective_from" value="{{ old('benefit_effective_from') }}">
        @if ($errors->addNewRate->has('benefit_effective_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('benefit_effective_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->addNewRate->has('benefit_effective_to')) is-invalid @endif"
            id="benefit_effective_to" name="benefit_effective_to" value="{{ old('benefit_effective_to') }}">
        @if ($errors->addNewRate->has('benefit_effective_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('benefit_effective_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="number" class="form-control @if ($errors->addNewRate->has('flat_rate')) is-invalid @endif" id="flat_rate"
            name="flat_rate" value="{{ old('flat_rate') }}">
        @if ($errors->addNewRate->has('flat_rate'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('flat_rate') }}</strong>
            </span>
        @endif
    </td>
    <td>
        
        <select id="rate_type" name="rate_type" class="form-control @if ($errors->addNewRate->has('rate_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($rateTypes as $row => $rate_type)
                <option value="{{ $row }}" @if (old('rate_type') == $row && old('rate_type')!=null) selected @endif>
                    {{ $rate_type }}</option>
            @endforeach
        </select>
        @if ($errors->addNewRate->has('rate_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('rate_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="number" class="form-control @if ($errors->addNewRate->has('rate_amount')) is-invalid @endif" id="rate_amount"
            name="rate_amount" value="{{ old('rate_amount') }}">
        @if ($errors->addNewRate->has('rate_amount'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->addNewRate->first('rate_amount') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="hidden" name="order" class="order" data-id="-1" value="0" />
        {{-- <button type="button" class="btn btn-outline-secondary commission-rate-order-up" data-id="-1">
            <i class="fas fa-chevron-up"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary commission-rate-order-down" data-id="-1">
            <i class="fas fa-chevron-down"></i>
        </button> --}}
    </td>
    <td class="d-flex" width="220">
        <button type="button" class="btn btn-primary mr-3 save-row" data-url="{{route("commissions.rate.create")}}">
            <i class="fas fa-save"></i> Save
        </button>
        <button type="button" class="btn btn-outline-danger cancel-row">
            <i class="fas fa-ban"></i> Cancel
        </button>
    </td>
</tr>

<tr class="fields-row edit-new-row">
    <td>
        <select id="business_segment" name="business_segment"
            class="form-control @if ($errors->editRate->has('business_segment')) is-invalid @endif">
            <option value=""></option>
            @foreach ($business_segments as $business_segment)
                <option value="{{ $business_segment->id }}" @if (old('business_segment', $commissionRate->fk_business_segment) == $business_segment->id) selected @endif>
                    {{ $business_segment->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('business_segment'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('business_segment') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="business_type" name="business_type" class="form-control @if ($errors->editRate->has('business_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($business_types as $business_type)
                <option value="{{ $business_segment->id }}" @if (old('business_type', $commissionRate->fk_business_type) == $business_type->id) selected @endif>
                    {{ $business_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('business_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('business_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="compensation_type" name="compensation_type"
            class="form-control @if ($errors->editRate->has('compensation_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($compensation_types as $compensation_type)
                <option value="{{ $compensation_type->id }}" @if (old('compensation_type', $commissionRate->fk_compensation_type) == $compensation_type->id) selected @endif>
                    {{ $compensation_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('compensation_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('compensation_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="amf_compensation_type" name="amf_compensation_type"
            class="form-control @if ($errors->editRate->has('amf_compensation_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($amf_compensation_types as $amf_compensation_type)
                <option value="{{ $amf_compensation_type->id }}" @if (old('amf_compensation_type', $commissionRate->fk_amf_compensation_type) == $amf_compensation_type->id) selected @endif>
                    {{ $amf_compensation_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('amf_compensation_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('amf_compensation_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="plan_type" name="plan_type" class="form-control @if ($errors->editRate->has('plan_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($plan_types as $plan_type)
                <option value="{{ $plan_type->id }}" @if (old('plan_type', $commissionRate->fk_plan_type) == $plan_type->id) selected @endif>
                    {{ $plan_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('plan_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('plan_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="product" name="product" class="form-control @if ($errors->editRate->has('product')) is-invalid @endif">
            <option value=""></option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}" @if (old('product', $commissionRate->fk_product) == $product->id) selected @endif>
                    {{ $product->description }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('product'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('product') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="product_type" name="product_type" class="form-control @if ($errors->editRate->has('product_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($product_types as $product_type)
                <option value="{{ $product_type->id }}" @if (old('product_type', $commissionRate->fk_product_type) == $product_type->id) selected @endif>
                    {{ $product_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('product_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('product_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="tier" name="tier" class="form-control @if ($errors->editRate->has('tier')) is-invalid @endif">
            <option value=""></option>
            @foreach ($tiers as $tier)
                <option value="{{ $tier->id }}" @if (old('tier', $commissionRate->fk_tier) == $tier->id) selected @endif>
                    {{ $tier->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('tier'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('tier') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="county" name="county" class="form-control @if ($errors->editRate->has('county')) is-invalid @endif">
            <option value=""></option>
            @foreach ($counties as $county)
                <option value="{{ $county->id }}" @if (old('county', $commissionRate->fk_county) == $county->id) selected @endif>
                    {{ $county->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('county'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('county') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="region" name="region" class="form-control @if ($errors->editRate->has('region')) is-invalid @endif">
            <option value=""></option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}" @if (old('region', $commissionRate->fk_region) == $region->id) selected @endif>
                    {{ $region->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('region'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('region') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="text" class="form-control @if ($errors->editRate->has('policy_contract_id')) is-invalid @endif"
            id="policy_contract_id" name="policy_contract_id" value="{{ old('policy_contract_id', $commissionRate->policy_contract_id) }}">
        @if ($errors->editRate->has('policy_contract_id'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('policy_contract_id') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="tx_type" name="tx_type" class="form-control @if ($errors->editRate->has('tx_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($txTypes as $tx_type)
                <option value="{{ $tx_type->id }}" @if (old('tx_type', $commissionRate->fk_tx_type) == $tx_type->id) selected @endif>
                    {{ $tx_type->name }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('tx_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('tx_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="agentType" name="agentType" class="form-control @if ($errors->editRate->has('agentType')) is-invalid @endif">
            <option value=""></option>
            @foreach ($agentTypes as $row => $agentType)
                <option value="{{ $row }}" @if (old('agentType', $commissionRate->agent_type) === $row) selected @endif>
                    {{ $agentType }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('agentType'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('agentType') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('submit_from')) is-invalid @endif" id="submit_from"
            name="submit_from" value="{{ old('submit_from', $commissionRate->submit_from) }}">
        @if ($errors->editRate->has('submit_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('submit_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('submit_to')) is-invalid @endif" id="submit_to"
            name="submit_to" value="{{ old('submit_to', $commissionRate->submit_to) }}">
        @if ($errors->editRate->has('submit_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('submit_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('statement_from')) is-invalid @endif" id="statement_from"
            name="statement_from" value="{{ old('statement_from', $commissionRate->statement_from) }}">
        @if ($errors->editRate->has('statement_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('statement_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('statement_to')) is-invalid @endif" id="statement_to"
            name="statement_to" value="{{ old('statement_to', $commissionRate->statement_to) }}">
        @if ($errors->editRate->has('statement_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('statement_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('original_effective_from')) is-invalid @endif"
            id="original_effective_from" name="original_effective_from"
            value="{{ old('original_effective_from', $commissionRate->original_effective_from) }}">
        @if ($errors->editRate->has('original_effective_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('original_effective_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('original_effective_to')) is-invalid @endif"
            id="original_effective_to" name="original_effective_to" value="{{ old('original_effective_to', $commissionRate->original_effective_to) }}">
        @if ($errors->editRate->has('original_effective_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('original_effective_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('benefit_effective_from')) is-invalid @endif"
            id="benefit_effective_from" name="benefit_effective_from" value="{{ old('benefit_effective_from', $commissionRate->benefit_effective_from) }}">
        @if ($errors->editRate->has('benefit_effective_from'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('benefit_effective_from') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="date" class="form-control @if ($errors->editRate->has('benefit_effective_to')) is-invalid @endif"
            id="benefit_effective_to" name="benefit_effective_to" value="{{ old('benefit_effective_to', $commissionRate->benefit_effective_to) }}">
        @if ($errors->editRate->has('benefit_effective_to'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('benefit_effective_to') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="number" class="form-control @if ($errors->editRate->has('flat_rate')) is-invalid @endif" id="flat_rate"
            name="flat_rate" value="{{ old('flat_rate', $commissionRate->flat_rate) }}">
        @if ($errors->editRate->has('flat_rate'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('flat_rate') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <select id="rate_type" name="rate_type" class="form-control @if ($errors->editRate->has('rate_type')) is-invalid @endif">
            <option value=""></option>
            @foreach ($rateTypes as $row => $rate_type)
                <option value="{{ $row }}" @if (old('rate_type', $commissionRate->rate_type) === $row) selected @endif>
                    {{ $rate_type }}</option>
            @endforeach
        </select>
        @if ($errors->editRate->has('rate_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('rate_type') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="number" class="form-control @if ($errors->editRate->has('rate_amount')) is-invalid @endif" id="rate_amount"
            name="rate_amount" value="{{ old('rate_amount', $commissionRate->rate_amount) }}">
        @if ($errors->editRate->has('rate_amount'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->editRate->first('rate_amount') }}</strong>
            </span>
        @endif
    </td>
    <td>
        <input type="hidden" class="order" data-id="{{$commissionRate->id}}" value="{{$commissionRate->order}}" />
        <button type="button" class="btn btn-outline-secondary commission-rate-order-up" data-id="{{$commissionRate->id}}">
            <i class="fas fa-chevron-up"></i>
        </button>
        <button type="button" class="btn btn-outline-secondary commission-rate-order-down" data-id="{{$commissionRate->id}}">
            <i class="fas fa-chevron-down"></i>
        </button>
    </td>
    <td class="d-flex" width="220">
        <button type="button" class="btn btn-primary mr-3 save-row" data-url="{{route("commissions.rate.update",['id' => $commissionRate->id])}}">
            <i class="fas fa-save"></i> Save
        </button>
        <input type="hidden" name="commissionRateId" value="{{$commissionRate->id}}" />
        <button type="button" class="btn btn-outline-danger cancel-row" data-id="{{$commissionRate->id}}">
            <i class="fas fa-ban"></i> Cancel
        </button>
    </td>
</tr>

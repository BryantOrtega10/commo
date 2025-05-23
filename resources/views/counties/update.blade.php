@extends('adminlte::page')

@section('title', 'Edit '.$defaults['singular-title'])

@section('content_header')
    <h1>Edit {{$defaults['singular-title']}}</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($defaults['base-route'].'.show') }}">{{$defaults['plural-title']}}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <form action="{{ route($defaults['base-route'].'.update',['id' => $item->id]) }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="name">Name (*):</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Name:" value="{{ old('name',$item->name) }}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Description:" value="{{ old('description',$item->description) }}">
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="sort_order">Sort Order:</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" id="sort_order" name="sort_order" placeholder="Sort Order:" value="{{ old('sort_order',$item->sort_order) }}">
                            @error('sort_order')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="">Selecciona una</option>
                                <option value="1" @if (old('status',$item->status) == "1") selected @endif>Active</option>                                
                                <option value="0" @if (old('status',$item->status) == "0") selected @endif>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="state">State:</label>
                            <select id="state" name="state" class="form-control @error('state') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($states as $state)
                                    <option value="{{$state->id}}" @if (old('state',$item->fk_state) == $state->id) selected @endif>{{$state->name}}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <div class="form-group">
                            <label for="region">Region:</label>
                            <select id="region" name="region" class="form-control @error('region') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($regions as $region)
                                    <option value="{{$region->id}}" @if (old('region',$item->fk_region) == $region->id) selected @endif>{{$region->name}}</option>
                                @endforeach
                            </select>
                            @error('region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>      
            </div>
            <div class="text-right card-footer">
                <input type="submit" class="btn btn-lg btn-primary" value="Save" />
            </div>
        </form>
    </div>
@stop

@section('js')

@stop
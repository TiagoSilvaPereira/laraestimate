@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('estimates.index') }}">@lang('app.estimates')</a></li>
            <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.create')</li>
        </ol>
    </nav>

    <div class="row">

        <div class="col mb-4">
            <div class="card animated slideInLeft faster">
                <div class="card-body">
                    <h4 class="card-title"><a href="{{ route('estimates.index') }}" class="mr-4"><i class="icon ion-md-arrow-back"></i></a> @lang('app.create_estimate')</h4>

                    <form action="{{ route('estimates.store') }}" method="post">
                        
                        @csrf

                        <div class="form-group">
                            <label for="name">@lang('app.labels.name')</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <div class="switch-container mt-2">
                                <label class="switch">
                                    <input type="hidden" name="use_name_as_title" value="0">
                                    <input type="checkbox" name="use_name_as_title" value="1" checked>
                                    <span class="slider round"></span>
                                </label>
                                
                                Use name as title?
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="currency_symbol">@lang('app.currency_symbol')</label>
                            <input name="currency_symbol" type="text" class="form-control" value="{{ optional($setting)->currency_symbol ?? '$' }}">
                        </div>

                        <div class="form-group">
                            <label for="currency_decimal_separator">@lang('app.currency_decimal_separator')</label>
                            <input name="currency_decimal_separator" type="text" class="form-control" value="{{ optional($setting)->currency_decimal_separator ?? '.' }}">
                        </div>

                        <div class="form-group">
                            <label for="currency_thousands_separator">@lang('app.currency_thousands_separator')</label>
                            <input name="currency_thousands_separator" type="text" class="form-control" value="{{ optional($setting)->currency_thousands_separator ?? '' }}">
                        </div>

                        <a href="{{ route('estimates.index') }}" class="btn btn-outline-dark btn-lg mt-5"><i class="icon ion-md-arrow-back"></i> @lang('app.labels.back')</a>
                        <button type="submit" class="btn btn-primary btn-lg float-right mt-5">@lang('app.labels.create')</button>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
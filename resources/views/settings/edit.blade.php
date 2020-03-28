@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('settings.edit') }}">@lang('app.settings')</a></li>
          <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.edit')</li>
        </ol>
      </nav>

    <div class="row">

        <div class="col mb-4">
            <div class="card animated slideInLeft faster">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.edit_settings')</h4>

                    <form action="{{ route('settings.update') }}" method="post">
                        
                        @csrf
                        @method('PUT')
                        
                        @if(config('app.preview'))
                        <div class="alert alert-warning mb-4">Logo upload is disabled on <b>Preview Mode</b></div>
                        @endif

                        <div class="form-group">
                            <label for="image">@lang('app.labels.logo')</label>
                            <image-uploader url="{{ route('settings.image.store') }}" 
                                image-path="{{ $setting->getFirstMediaUrl('logo') }}"></image-uploader>
                        </div>
                        
                        <div class="form-group">
                            <label for="currency_symbol">@lang('app.currency_symbol')</label>
                            <input name="currency_symbol" type="text" class="form-control" value="{{ $setting->currency_symbol ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="currency_decimal_separator">@lang('app.currency_decimal_separator')</label>
                            <input name="currency_decimal_separator" type="text" class="form-control" value="{{ $setting->currency_decimal_separator ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="currency_thousands_separator">@lang('app.currency_thousands_separator')</label>
                            <input name="currency_thousands_separator" type="text" class="form-control" value="{{ $setting->currency_thousands_separator ?? '' }}">
                        </div>

                        <button class="btn btn-primary float-right"><i class="icon ion-md-save"></i> @lang('app.labels.save')</button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
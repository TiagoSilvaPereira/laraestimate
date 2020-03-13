@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('estimates.index') }}">@lang('app.estimates')</a></li>
          <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.create')</li>
        </ol>
      </nav>

    <div class="row">

        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.create_estimate')</h4>

                    <form action="{{ route('estimates.store') }}" method="post">
                        
                        @csrf

                        <div class="form-group">
                            <label for="name">@lang('app.labels.name')</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="description">@lang('app.labels.description') @include('ui.optional')</label>
                            <textarea name="description" rows="3" class="form-control"></textarea>
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
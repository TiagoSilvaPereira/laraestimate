@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('estimates.index') }}">@lang('app.estimates')</a></li>
          <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.edit')</li>
        </ol>
      </nav>

    <div class="row">

        <div class="col mb-4">
            <div class="card animated slideInLeft faster">
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="{{ route('estimates.index') }}" class="mr-4"><i class="icon ion-md-arrow-back"></i></a> @lang('app.edit_estimate') 

                        <a target="_blank" href="{{ route('estimates.show', $estimate) }}" class="btn btn-primary btn-lg float-right"><i class="icon ion-md-document"></i> @lang('app.view_estimate')</a>
                    </h4>

                    <div class="mt-4">
                        <estimate-editor-component estimate="{{ $estimate->id }}"></estimate-editor-component>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
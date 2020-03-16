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
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.edit_estimate')</h4>

                    <div>
                        <estimate-component estimate="{{ $estimate->id }}"></estimate-component>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
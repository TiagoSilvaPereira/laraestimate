@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.create_estimate')</h4>

                    <form action="{{ route('estimates.store') }}" method="post">
                        
                        @csrf

                        <div class="form-group">
                            <label for="name">@lang('app.labels.name')</label>
                            <input type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="description">@lang('app.labels.description') @include('ui.optional')</label>
                            <textarea name="description" rows="3" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg float-right mt-5">Create</button>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
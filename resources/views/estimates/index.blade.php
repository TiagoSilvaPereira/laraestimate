@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col-sm-12 mb-4">
            <h1>@lang('app.estimates')</h1>
            <a href="{{ route('estimates.create') }}" class="btn btn-primary btn-lg"><i class="icon ion-md-add"></i> @lang('app.add_estimate')</a>
        </div>

        @forelse ($estimates as $estimate)    
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $estimate->name }}</h4>

                    <p class="card-subtitle">{{ $estimate->created_at->diffForHumans() }}</p>

                    <div class="mt-2">
                        <a href="#" class="text-primary"><i class="icon ion-md-create"></i></a>
                        <a href="#" class="text-danger float-right"><i class="icon ion-md-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
        @empty
            @lang('app.no_estimates_found')
        @endforelse

    </div>

    <div class="row">
        <div class="col-sm-12">
            {{ $estimates->render() }}
        </div>
    </div>
</div>
@endsection
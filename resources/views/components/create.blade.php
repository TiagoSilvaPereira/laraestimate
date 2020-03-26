{{-- The routes base params (useful for tenant routes) --}}
@php
$route_base_params = isset($route_base_params) ? $route_base_params : [];  
@endphp

@component('components.card')
        
    @slot('title')
    <a href="{{ route($route_prefix . '.index', $route_base_params) }}" class="mr-4"><i class="icon ion-md-arrow-back"></i></a> {{ $title }}
    @endslot

    {!! Form::open(['url' => route($route_prefix . '.store', $route_base_params)]) !!}

        {{-- Fields Slot --}}
        {{ $slot }}

        <a href="{{ route($route_prefix . '.index', $route_base_params) }}" class="btn btn-outline-dark">
            <i class="icon ion-md-return-left"></i> @lang('app.labels.back_to_index')
        </a>
        
        <button type="submit" class="btn btn-primary float-right"><i class="icon ion-md-save"></i> @lang('app.labels.save')</button>

    {!! Form::close() !!}

@endcomponent
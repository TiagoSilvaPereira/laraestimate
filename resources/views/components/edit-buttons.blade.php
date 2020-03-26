{{-- The routes base params (useful for tenant routes) --}}
@php
$route_base_params = isset($route_base_params) ? $route_base_params : [];  
@endphp

<div class="mt-4">
    <a href="{{ route($route_prefix . '.index', $route_base_params) }}" class="btn btn-outline-dark"><i class="icon ion-md-return-left"></i> @lang('app.labels.back_to_index')</a>
    
    <a href="{{ route($route_prefix . '.create', $route_base_params) }}" class="btn btn-outline-dark"><i class="icon ion-md-add"></i> @lang('app.labels.create')</a>
    
    <button type="submit" class="btn btn-primary float-right"><i class="icon ion-md-save"></i> @lang('app.labels.save')</button>
</div>
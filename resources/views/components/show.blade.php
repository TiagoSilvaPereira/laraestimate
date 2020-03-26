{{-- The routes base params (useful for tenant routes) --}}
@php
$route_base_params = isset($route_base_params) ? $route_base_params : [];
$route_params = array_merge($route_base_params, [$data]);
@endphp

@component('components.card')
    <h4 class="card-title">
        <a href="{{ route($route_prefix . '.index', $route_base_params) }}" class="mr-4"><i class="icon ion-md-arrow-back"></i></a> @lang('app.labels.showing') {{ $title }}
    </h4>

    <div class="mt-4">
        @forelse($fields as $field => $fieldData)
        <div class="mb-4">
    
            {{-- If we passed a closure (function) to the field --}}
            @if(is_callable($fieldData))
            
            @php
            $fieldData = $fieldData($data[$field]);    
            @endphp
    
            <h5>{{ $fieldData['label'] }}</h5>
            @if(isset($fieldData['isHtml']) && $fieldData['isHtml'])
            <span>{!! $fieldData['value'] !!}</span>
            @else
            <span>{{ $fieldData['value'] }}</span>
            @endif
            
            @else
            <h5>{{ $fieldData }}</h5>
            <span>{{ $data[$field] ?? '-' }}</span>
            @endif
        </div>
        @empty
        Data not found
        @endforelse
    </div>

    <a href="{{ route($route_prefix . '.index', $route_base_params) }}" class="btn btn-outline-dark"><i class="icon ion-md-return-left"></i> @lang('app.labels.back_to_index')</a>

    @can('update', $data)
    <a href="{{ route($route_prefix . '.edit', $route_params) }}" class="btn btn-outline-dark"><i class="icon ion-md-create"></i> @lang('app.labels.edit')</a>
    @endcan

    @if(isset($model))
        @can('create', $model)
        <a href="{{ route($route_prefix . '.create', $route_base_params) }}" class="btn btn-outline-dark float-right"><i class="icon ion-md-add"></i> @lang('app.labels.create')</a>
        @endcan
    @endif
@endcomponent
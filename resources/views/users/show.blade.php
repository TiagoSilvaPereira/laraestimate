@extends('layouts.app')

@section('content')

    <div class="container">
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('users.index') }}">@lang('app.users')</a></li>
                <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.showing')</li>
            </ol>
        </nav>

        @component('components.show', 
            [
                'data' => $user,
                'title' => trans('app.user'),
                'model' => App\Models\User::class,
                'route_prefix' => 'users',
                'fields' => [
                    'name' => trans('app.labels.name'),
                    'email' => trans('app.labels.email'),
                ]
            ])
        @endcomponent
    </div>

@endsection
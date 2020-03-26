@extends('layouts.app')

@section('content')

    <div class="container">
        @component('components.index', 
            [
                'hideId' => true,
                'model' => App\Models\User::class,
                'title' => trans('app.users'),
                'route_prefix' => 'users',
                'search' => $search ?? '',
                'fields' => [
                    'name' => trans('app.labels.name'),
                    'email' => trans('app.labels.email'),
                ], 
                'data' => $users,
            ])
        @endcomponent
    </div>
    
@endsection
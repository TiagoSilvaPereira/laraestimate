@extends('layouts.app')

@section('content')
    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('users.index') }}">@lang('app.users')</a></li>
                <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.create')</li>
            </ol>
        </nav>

        @component('components.create', ['route_prefix' => 'users'])

            @slot('title')
                @lang('app.create_user')
            @endslot

            @include('users.fields')
        
        @endcomponent

    </div>
@endsection
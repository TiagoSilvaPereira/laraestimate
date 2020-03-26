@extends('layouts.app')

@section('content')
    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('users.index') }}">@lang('app.users')</a></li>
                <li class="breadcrumb-item active" aria-current="page">@lang('app.labels.edit')</li>
            </ol>
        </nav>

        @component('components.card')
        
            @slot('title')
            <a href="{{ route('users.index') }}" class="mr-4"><i class="icon ion-md-arrow-back"></i></a> @lang('app.edit_user')
            @endslot

            {!! Form::model($user, [
                'url' => route('users.update', $user), 
                'method' => 'put'
            ]) !!}

                @include('users.fields')

                @include('components.edit-buttons', ['route_prefix' => 'users'])

            {!! Form::close() !!}

        @endcomponent

    </div>
@endsection
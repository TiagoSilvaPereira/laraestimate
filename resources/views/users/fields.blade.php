@php
$isCreationMode = empty($user) ? true : false;    
@endphp

<div class="form-group">
    <label for="name">@lang('app.labels.name')</label>
    {{ Form::text('name', null, ['class' => 'form-control', 'required' => true]) }}
</div>

<div class="form-group">
    <label for="email">@lang('app.labels.email')</label>
    {{ Form::email('email', null, ['class' => 'form-control', 'required' => true]) }}
</div>

<div class="form-group">
    <label for="password">@lang('app.labels.password')</label>
    {{ Form::password('password', ['class' => 'form-control', 'required' => $isCreationMode, 'min' => 8]) }}
</div>

<div class="form-group">
    <label for="password_confirmation">@lang('app.labels.password_confirmation')</label>
    {{ Form::password('password_confirmation', ['class' => 'form-control', 'required' => $isCreationMode]) }}
</div>
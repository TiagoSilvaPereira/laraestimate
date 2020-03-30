@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        This action is blocked on <b>Preview Mode</b>
                    </div>

                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg"><i class="icon ion-md-return-left"></i> Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

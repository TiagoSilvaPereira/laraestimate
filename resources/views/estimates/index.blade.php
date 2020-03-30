@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row animated slideInLeft faster">

        <div class="col-sm-12 mb-4">
            <h1>@lang('app.estimates')</h1>
            <a href="{{ route('estimates.create') }}" class="btn btn-primary btn-lg"><i class="icon ion-md-add"></i> @lang('app.add_estimate')</a>
        </div>

        <div class="col-sm-12">
            <div class="searchbar mt-4 mb-4">
                <form>
                    <div class="input-group">
                        <input id="listItemsSearch" type="text" name="search" placeholder="Search..." class="form-control form-control-lg" value="{{ isset($search) ? $search : '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary btn-lg" type="button">
                                <i class="icon ion-md-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @forelse ($estimates as $estimate)    
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $estimate->name }}</h4>

                    <p class="card-subtitle text-black-50">{{ $estimate->created_at->diffForHumans() }}</p>

                    <div class="row mt-2">
                        <div class="col">
                            <a href="{{ route('estimates.edit', $estimate) }}" class="btn btn-light text-primary btn-sm"><i class="icon ion-md-create"></i> @lang('app.labels.edit')</a>
                            <a href="{{ route('estimates.show', $estimate) }}" target="_blank" class="btn btn-light text-primary btn-sm"><i class="icon ion-md-document"></i>  @lang('app.labels.view')</a>
                            <a href="{{ route('estimates.duplicate', $estimate) }}" target="_blank" class="btn btn-light text-primary btn-sm"><i class="icon ion-md-copy"></i> @lang('app.labels.duplicate')</a>
                        </div>
                        <div class="col">
                            <form id="deleteEstimateForm{{ $estimate->id }}" action="{{ route('estimates.destroy', $estimate) }}" method="POST" onsubmit="return estimates.confirmDelete(event, '{{ $estimate->id }}')">
                                    
                                @method('DELETE')
                                @csrf
                                
                                <button type="submit" class="btn btn-light text-danger float-right btn-sm">
                                    <i class="icon ion-md-trash"></i> @lang('app.labels.remove')
                                </button>
    
                            </form>
                        </div>

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

@push('scripts')
<script>
    var estimates = {

        confirmDelete: function(event, id) {

            event.preventDefault();
            event.stopPropagation();

            bootbox.confirm('{{ trans("app.dialogs.are_you_sure") }}', function(confirmed) {
                if(confirmed) {
                    document.getElementById('deleteEstimateForm' + id).submit();
                }
            });

            return false;

        }

    }
</script>
@endpush
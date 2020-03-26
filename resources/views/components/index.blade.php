{{-- The routes base params (useful for tenant routes) --}}
@php
$route_base_params = isset($route_base_params) ? $route_base_params : [];  
@endphp

@component('components.card')
    <h4 class="card-title" style="display: flex; justify-content: space-between;">
        
        {{ $title }}
        
        @can('create', $model)
        <a href="{{ route($route_prefix . '.create', $route_base_params) }}" class="btn btn-primary">
            <i class="icon ion-md-add"></i> @lang('app.labels.create')
        </a>
        @endcan

    </h4>

    @unless(isset($canSearch) && $canSearch == false)
    <div class="searchbar mt-4 mb-4">
        <form>
            <div class="input-group">
                <input id="listItemsSearch" type="text" name="search" placeholder="@lang('app.labels.search')" class="form-control form-control-lg" value="{{ $search }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary" type="button">
                        <i class="icon ion-md-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endunless

    <table class="table table-borderless table-hover">
        <thead class="">
            <tr>
                @unless ($hideId)
                <th>#</th>
                @endunless

                @foreach($fields as $field => $fieldData)
                
                {{-- If we passed a closure (function) to the field --}}
                @if(is_callable($fieldData))
                
                @php
                $fieldData = $fieldData(null);
                @endphp

                <th>{{ $fieldData['label'] }}</th>
                @else
                <th>{{ $fieldData }}</th>
                @endif

                @endforeach
                <th class="text-center">@lang('app.labels.actions')</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
                <tr>
                    @unless ($hideId)
                    <td>{{ $item->id }}</td>
                    @endunless

                    @foreach($fields as $field => $fieldData)
                    <td>
                        {{-- If we passed a closure (function) to the field --}}
                        @if(is_callable($fieldData))
            
                            @php
                            $fieldData = $fieldData($item[$field]);
                            @endphp

                            @if(isset($fieldData['isHtml']) && $fieldData['isHtml'])
                            {!! $fieldData['value'] !!}
                            @else
                            {{ $fieldData['value'] }}
                            @endif
                        
                        @else
                        {{ $item[$field] ?? '-' }}
                        @endif
                    </td>
                    @endforeach
                    <td class="text-center" style="width: 134px">
                        <div class="btn-group" role="group" aria-label="Row Actions">
                            
                            {{-- Let's mix the route params --}}
                            @php
                                $route_params = array_merge($route_base_params, [$item]);   
                            @endphp

                            @can('update', $item)
                            <a href="{{ route($route_prefix . '.edit', $route_params) }}">
                                <button type="button" class="btn btn-light">
                                    <i class="icon ion-md-create"></i>
                                </button>
                            </a>
                            @endcan
                            
                            @can('view', $item)
                            <a href="{{ route($route_prefix . '.show', $route_params) }}">
                                <button type="button" class="btn btn-light">
                                    <i class="icon ion-md-search"></i>
                                </button>
                            </a>
                            @endcan
                            
                            @can('delete', $item)
                            <form id="deleteItemForm{{ $item->id }}" action="{{ route($route_prefix . '.destroy', $route_params) }}" method="POST" onsubmit="return list.confirmDelete(event, '{{ $item->id }}')">
                                
                                @method('DELETE')
                                @csrf
                                
                                <button type="submit" class="btn btn-light text-danger">
                                    <i class="icon ion-md-trash"></i>
                                </button>

                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($fields) + 2 }}">No results found</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="{{ count($fields) + 2 }}">
                    @unless(isset($paginate) && $paginate == false)
                    {!! $data->links() !!}
                    @endunless
                </td>
            </tr>
        </tfoot>

    </table>
@endcomponent

@push('scripts')
<script>
    
    var list = {

        // Autofocus on search
        init: function() {
            setTimeout(() => {
                var listItemsSearch = document.getElementById('listItemsSearch');
                listItemsSearch.focus();
            }, 100);
        },
        
        // Confirm to delete
        confirmDelete: function(event, id) {

            event.preventDefault();
            event.stopPropagation();

            bootbox.confirm('{{ trans("app.dialogs.are_you_sure") }}', function(confirmed) {
                if(confirmed) {
                    document.getElementById('deleteItemForm' + id).submit();
                }
            });

            return false;
        }
    };

    list.init();

</script>
@endpush
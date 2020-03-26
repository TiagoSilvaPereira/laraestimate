<div class="card {{ $class ?? '' }} animated slideInLeft faster">
    <div class="card-body">
        
        <h4 class="card-title">
            {{-- Title Slot --}}
            {{ $title ?? '' }}
        </h4>

        {{ $slot ?? '' }}        

    </div>
</div>
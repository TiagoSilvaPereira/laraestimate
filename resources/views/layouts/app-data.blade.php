<script>
    window.App = {
        'messages': {
            'success': '{{ Session::get("success") }}',
            @if($errors->any())
            'errors': @json($errors->all())
            @endif
        },
    }
</script>
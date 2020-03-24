<script>
    window.App = {
        'localizationData': @json($localizationData),
        'messages': {
            'success': '{{ Session::get("success") }}',
            @if($errors->any())
            'errors': @json($errors->all())
            @endif
        },
    }
</script>
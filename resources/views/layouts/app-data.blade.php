<script>
    window.App = {
        'isOnPreviewMode': @json(config('app.preview')),
        'localizationData': @json($localizationData),
        'messages': {
            'success': '{{ Session::get("success") }}',
            @if($errors->any())
            'errors': @json($errors->all())
            @endif
        },
    }
</script>
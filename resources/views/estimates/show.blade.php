<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $estimate->name }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        html, body {
            background-color: #eee;
            text-align: justify;
        }
    </style>

    @include('layouts.app-data')
</head>
<body>
    <div id="app">
        <main class="p-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-md-2 bg-white p-5">
                        @foreach ($estimate->sections as $section)
                            <section class="mb-5">
                                {!! $section->text !!}
            
                                @if(count($section->items))
                                    <table class="table mt-4">
                                        <tr>
                                            <th>Description</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                        </tr>
            
                                        @foreach ($section->items as $item)
                                            <tr>
                                                <td>{{ $item->description ?? '-' }}</td>
                                                <td>{{ $item->duration ?? '-' }}</td>
                                                <td>{{ $item->price ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif
                            </section>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

    @if(config('app.env') == 'local')
        <script src="http://localhost:35729/livereload.js"></script>
    @endif

</body>
</html>

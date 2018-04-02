<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--话题字段 excerpt 是文章摘录，用作 SEO 页面描述使用，因此我们还需要修改主布局模板，新增 --}}
    <meta name="description" content="@yield('description', 'LaraBBS 爱好者社区')" />

    <title>@yield('title', 'LaraBBS') - Laravel 进阶教程</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>
<div id="app" class="{{ route_class() }}-page">

    @include('layouts._header')

    <div class="container">
        @include('layouts._message')
        @yield('content')

    </div>

    @include('layouts._footer')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
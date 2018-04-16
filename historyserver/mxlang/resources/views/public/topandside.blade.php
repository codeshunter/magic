<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{asset('resources/views/assets/css/amazeui.min.css')}}">
    <title>@lang('admin.title')</title>
    <style>
        @yield('style')
    </style>
    <style>
        .index {
            background: black;
        }
    </style>
    <script src="{{asset('resources/views/assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('resources/views/assets/js/amazeui.min.js')}}"></script>
</head>
<body>
<header class="am-topbar am-topbar-inverse index">
    <h1 class="am-topbar-brand">
        <a href="{{url('/')}}">D A</a>
    </h1>

    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

    <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
        <ul class="am-nav am-nav-pills am-topbar-nav">
            {{--<li class="am-active"><a href="javascript:void(0)">@lang('admin.index')</a></li>--}}
            <li><a href="javascript:void(0)">@lang('admin.about')</a></li>
            <li><a href="mailto:anny.s.titova@gmail.com?&subject=I%20want%20to%20purchase%20something!">
                    @lang('admin.contact')
                </a>
            </li>
        </ul>


        <div class="am-topbar-right">
            <div class="am-dropdown" data-am-dropdown="{boundary: '.am-topbar'}">
                <button class="am-btn am-btn-link am-topbar-btn am-btn-sm am-dropdown-toggle" data-am-dropdown-toggle>@lang('admin.cglang') <span class="am-icon-caret-down"></span></button>
                <ul class="am-dropdown-content">
                    <li><a href="javascript:void(0);" id="langen">English</a></li>
                    <li><a href="javascript:void(0);" id="langzh">中文</a></li>
                    <li><a href="javascript:void(0);" id="langru">русский</a></li>
                </ul>
            </div>
        </div>

        <div class="am-topbar-right">
            {{--<button class="am-btn am-btn-primary am-topbar-btn am-btn-sm">@lang('admin.login')</button>--}}
            <a class="am-btn am-topbar-btn am-btn-sm" href="{{url('admin/login')}}">@lang('admin.login')</a>
            <a class="am-btn  am-topbar-btn am-btn-sm" href="{{url('admin/logout')}}">@lang('admin.logout')</a>
        </div>
    </div>
</header>
@yield('page')
</body>
<script>
    $('#langen').click(function () {
        cglang('en');
    });
    $('#langzh').click(function () {
        cglang('zh');
    });
    $('#langru').click(function () {
        cglang('ru');
    });
    function cglang(lang) {
        $.get('{{url('cglang')}}', {
            'lang': lang
        }, function (res) {
            window.location.href=window.location.href;
        });
    }
</script>
</html>
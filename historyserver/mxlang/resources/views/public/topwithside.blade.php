@extends("public.topandside")

@section('page')
    <style>
        .sidebar {
            width: 20%;
            float: left;
            background: rgba(255, 255, 255, 0.8);
        }
        body {
            background: rgba(0, 0, 0, 0) url("https://dn-coding-net-production-static.qbox.me/9b8e717d-9795-4d54-b687-931aea15afb6.jpg") no-repeat fixed center center / cover;
            height: 100%;
        }
        .main {
            float: left;
            width: 78%;
            /*margin-left: 10%;*/
        }
    </style>
    <section data-am-widget="accordion" class="am-accordion am-accordion-default sidebar" data-am-accordion='{"multiple": true}'>
        <dl class="am-accordion-item am-active am-disabled">
            <dt class="am-accordion-title">@lang('admin.sort')</dt>
            <dd class="am-accordion-bd am-collapse am-in">
                <div class="am-accordion-content">
                    <a href="{{url('admin')}}">@lang('admin.list')</a>
                </div>
                <div class="am-accordion-content">
                    <a href="{{url('admin/addsort')}}">@lang('admin.add')</a>
                </div>
            </dd>
        </dl>
        <dl class="am-accordion-item">
            <dt class="am-accordion-title">@lang('admin.goods')</dt>
            <dd class="am-accordion-bd am-collapse ">
                <div class="am-accordion-content">
                    <a href="{{url('admin/prodlist')}}">@lang('admin.list')</a>
                </div>
                <div class="am-accordion-content">
                    <a href="{{url('admin/addprod')}}">@lang('admin.add')</a>
                </div>
            </dd>
        </dl>
        <dl class="am-accordion-item">
            <dt class="am-accordion-title">@lang('admin.carousel')</dt>
            <dd class="am-accordion-bd am-collapse ">
                <div class="am-accordion-content">
                    <a href="{{url('admin/indeximglist')}}">@lang('admin.list')</a>
                </div>
                <div class="am-accordion-content">
                    <a href="{{url('admin/addindeximg')}}">@lang('admin.add')</a>
                </div>
            </dd>
        </dl>
        <dl class="am-accordion-item">
            <dt class="am-accordion-title">@lang('admin.changepwd')</dt>
            <dd class="am-accordion-bd am-collapse ">
                <div class="am-accordion-content">
                    <a href="">@lang('admin.changepwd')</a>
                </div>
            </dd>
        </dl>
    </section>
    <div class="main">
        @yield("main")
    </div>
@endsection
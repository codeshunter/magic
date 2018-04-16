@extends("public.topandside")

@section('style')
    html, body {
        height: 100%;
    }
    body {
        background: rgba(0, 0, 0, 0) url("https://dn-coding-net-production-static.qbox.me/9b8e717d-9795-4d54-b687-931aea15afb6.jpg") no-repeat fixed center center / cover;
    }

    body {
        margin: 0;
        padding: 0;
        width: 100%;
        display: table;
        font-weight: 100;
        {{--font-family: 'Lato';--}}
    }

    {{--.container {--}}
        {{--/*text-align: center;*/--}}
        {{--display: table-cell;--}}
        {{--vertical-align: middle;--}}
    {{--}--}}
    .container {
        width: 60%;
        heigh: 60%;
        background: white;
        border-radius: 10px;
        padding: 40px 20px;
        margin: 20% auto;
    }
@endsection

@section("page")
    <div class="container">
        <form class="am-form am-form-horizontal" action="{{url('admin/logincon')}}" method="post">
            {{csrf_field()}}
            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.username')</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="user" placeholder="@lang('admin.username')">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-pwd-2" class="am-u-sm-2 am-form-label">@lang('admin.pwd')</label>
                <div class="am-u-sm-10">
                    <input type="password" id="doc-ipt-pwd-2" name="pwd" placeholder="@lang('admin.pwd')">
                </div>
            </div>

            <div class="am-form-group">
                <div class="am-u-sm-10 am-u-sm-offset-2">
                    <button type="submit" class="am-btn am-btn-default">@lang('admin.login')</button>
                </div>
            </div>
        </form>
    </div>

@endsection
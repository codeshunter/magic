@extends("public.topwithside")
@section("main")
    <style>
        .addsort {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
    <div class="addsort">
        <h3>@lang('admin.add') @lang('admin.sort')</h3>
        <hr>
        <form class="am-form am-form-horizontal" action="{{url('admin/addsortcon')}}" method="post">
            {{csrf_field()}}
            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.sortname')(@lang('admin.en'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="enname" placeholder="@lang('admin.sortname')(@lang('admin.en'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.sortname')(@lang('admin.zh'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="zhname" placeholder="@lang('admin.sortname')(@lang('admin.zh'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.sortname')(@lang('admin.ru'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="runame" placeholder="@lang('admin.sortname')(@lang('admin.ru'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.psort')</label>
                <div class="am-u-sm-10">
                    <select name="pid" id="">
                        <option value="-1">@lang('admin.psort')</option>
                        @foreach($data as $v)
                            <option value="{{$v->id}}">{{$v->en}} {{$v->zh}} {{$v->ru}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="am-form-group">
                <div class="am-u-sm-10 am-u-sm-offset-2">
                    <button type="submit" class="am-btn am-btn-default">@lang('admin.add')</button>
                </div>
            </div>
        </form>
    </div>

@endsection
@extends("public.topwithside")
@section("main")
    <style>
        .prodlist {
            padding: 10px;
            background: rgba(255, 255, 255, .8);
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
    <div class="prodlist">
        <table class="am-table">
            <thead>
            <tr>
                <th>id</th>
                <th>@lang('admin.carousel')</th>
                <th>@lang('admin.opt')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td><a target="_blank" href="{{url('/').'/'.$v->path}}">@lang('admin.gotoimg')</a></td>
                    <td>
                        {{--<a href="{{url('admin/editsort?id='.$v["id"])}}">@lang('admin.edit')</a> |--}}
                        <a href="{{url('admin/delindeximg?id='.$v["id"])}}">@lang('admin.delete')</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
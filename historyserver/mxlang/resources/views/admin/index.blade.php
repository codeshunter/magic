@extends("public.topwithside")
@section("main")
<style>
    .sortlist {
        padding: 10px;
        background: rgba(255, 255, 255, .8);
        border-radius: 5px;
        margin-top: 10px;
    }
</style>
<div class="sortlist">
    <table class="am-table">
        <thead>
        <tr>
            <th>id</th>
            <th>@lang('admin.sortname')(@lang('admin.en'))</th>
            <th>@lang('admin.sortname')(@lang('admin.zh'))</th>
            <th>@lang('admin.sortname')(@lang('admin.ru'))</th>
            <th>@lang('admin.psort')</th>
            <th>@lang('admin.opt')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $v)
            <tr>
                <td>{{$v->id}}</td>
                <td>{{$v->en}}</td>
                <td>{{$v->zh}}</td>
                <td>{{$v->ru}}</td>
                <td>{{$v->pid}}</td>
                <td>
                    <a href="{{url('admin/editsort?id='.$v["id"])}}">@lang('admin.edit')</a> |
                    <a href="{{url('admin/delsort?id='.$v["id"])}}">@lang('admin.delete')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
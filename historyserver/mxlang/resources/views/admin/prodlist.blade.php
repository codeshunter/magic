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
                <th>@lang('admin.prodname')<br>(@lang('admin.en'))</th>
                <th>@lang('admin.prodname')<br>(@lang('admin.zh'))</th>
                <th>@lang('admin.prodname')<br>(@lang('admin.ru'))</th>
                <th>@lang('admin.prodet')<br>(@lang('admin.en'))</th>
                <th>@lang('admin.prodet')<br>(@lang('admin.zh'))</th>
                <th>@lang('admin.prodet')<br>(@lang('admin.ru'))</th>
                <th>@lang('admin.price')<br>(@lang('admin.en'))</th>
                <th>@lang('admin.price')<br>(@lang('admin.zh'))</th>
                <th>@lang('admin.price')<br>(@lang('admin.ru'))</th>
                <th>@lang('admin.psort')</th>
                <th>@lang('admin.opt')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->enname}}</td>
                    <td>{{$v->zhname}}</td>
                    <td>{{$v->runame}}</td>
                    <td>{{$v->endet}}</td>
                    <td>{{$v->zhdet}}</td>
                    <td>{{$v->rudet}}</td>
                    <td>{{$v->enprice}}</td>
                    <td>{{$v->zhprice}}</td>
                    <td>{{$v->ruprice}}</td>
                    <td>{{$v->sid}}</td>
                    <td>
                        {{--<a href="{{url('admin/editsort?id='.$v["id"])}}">@lang('admin.edit')</a> |--}}
                        <a href="{{url('admin/delprod?id='.$v["id"])}}">@lang('admin.delete')</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
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
        <h3>@lang('admin.add') @lang('admin.goods')</h3>
        <hr>
        <form class="am-form am-form-horizontal" action="{{url('admin/addprodcon')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.goods')@lang('admin.name')(@lang('admin.en'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="enname" placeholder="@lang('admin.goods')@lang('admin.name')(@lang('admin.en'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.goods')@lang('admin.name')(@lang('admin.zh'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="zhname" placeholder="@lang('admin.goods')@lang('admin.name')(@lang('admin.zh'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.goods')@lang('admin.name')(@lang('admin.ru'))</label>
                <div class="am-u-sm-10">
                    <input type="text" id="doc-ipt-3" name="runame" placeholder="@lang('admin.goods')@lang('admin.name')(@lang('admin.ru'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-1" class="am-u-sm-2 am-form-label">@lang('admin.prodet')(@lang('admin.en'))</label>
                <div class="am-u-sm-10">
                    <textarea class="" rows="5" id="doc-ta-1" name="endet"></textarea>
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-1" class="am-u-sm-2 am-form-label">@lang('admin.prodet')(@lang('admin.zh'))</label>
                <div class="am-u-sm-10">
                    <textarea class="" rows="5" id="doc-ta-1" name="zhdet"></textarea>
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-1" class="am-u-sm-2 am-form-label">@lang('admin.prodet')(@lang('admin.ru'))</label>
                <div class="am-u-sm-10">
                    <textarea class="" rows="5" id="doc-ta-1" name="rudet"></textarea>
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.price')(@lang('admin.en'))</label>
                <div class="am-u-sm-2">
                    <input type="text" id="doc-ipt-3" name="enprice" placeholder="@lang('admin.price')(@lang('admin.en'))">
                </div>
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.price')(@lang('admin.zh'))</label>
                <div class="am-u-sm-2">
                    <input type="text" id="doc-ipt-3" name="zhprice" placeholder="@lang('admin.price')(@lang('admin.zh'))">
                </div>
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.price')(@lang('admin.ru'))</label>
                <div class="am-u-sm-2">
                    <input type="text" id="doc-ipt-3" name="ruprice" placeholder="@lang('admin.price')(@lang('admin.ru'))">
                </div>
            </div>

            <div class="am-form-group">
                <label for="doc-ipt-3" class="am-u-sm-2 am-form-label">@lang('admin.psort')</label>
                <div class="am-u-sm-10">
                    <select name="sid" id="">
                        @foreach($data as $v)
                            <option value="{{$v->id}}">{{$v->en}} {{$v->zh}} {{$v->ru}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="am-form-group am-form-file am-u-sm-offset-2">
                <button type="button" class="am-btn am-btn-secondary am-btn-sm">
                    <i class="am-icon-cloud-upload"></i> @lang('admin.selectimg')</button>
                <input id="doc-form-file" type="file" name="prodimgs[]" multiple>
            </div>
            <div id="file-list" class="am-u-sm-offset-2"></div>

            <div class="am-form-group">
                <div class="am-u-sm-10 am-u-sm-offset-2">
                    <button type="submit" class="am-btn am-btn-default">@lang('admin.add')</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        $(function() {
            $('#doc-form-file').on('change', function() {
                var fileNames = '';
                $.each(this.files, function() {
                    fileNames += '<span class="am-badge">' + this.name + '</span> ';
                });
                $('#file-list').html(fileNames);
            });
        });
    </script>
@endsection
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
        <h3>@lang('admin.carousel')</h3>
        <hr>
        <form class="am-form am-form-horizontal" action="{{url('admin/addindeximgcon')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}

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
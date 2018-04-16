@extends("public.topandside")
@section("page")
<link rel="stylesheet" href="{{asset('resources/views/assets/css/amazeui.tree.min.css')}}">
<script src="{{asset('resources/views/assets/js/amazeui.tree.min.js')}}"></script>
<script src="{{asset('resources/views/assets/js/vue.min.js')}}"></script>
<style>
    .container {
        width: 60%;
        margin: 0 auto;
    }
    header.index {
        padding: 0 20%;
        /*margin: 0 auto;*/
    }
    .sidebar {
        width: 18%;
        border-radius: 5px;
        border: 1px solid #888888;
        box-shadow: 10px 10px 5px #888888;
        /*background: gray;*/
        position: fixed;
        left: 10px;
        top: 70px;
    }
    [class*="am-u-"] {
        padding: 0 0.5rem;

    }
    [class*="am-u-"] + [class*="am-u-"]:last-child {
        float: left;
    }
</style>
<script>
    $("title").html("@lang('index.title')");
</script>
<div class="container">
    <div class="banner">
        <div data-am-widget="slider" class="am-slider am-slider-c2" data-am-slider='{"directionNav":true}' >
            <ul class="am-slides">
                @foreach($imgs as $img)
                    <li>
                        <img src="{{url('/').'/'.$img->path}}">
                        <div class="am-slider-desc">FASTER <br> TOGETHER</div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <hr>
    <div class="goods">
        <div class="am-g">
            <template v-for="x in goods">
                <div class="am-u-sm-3">
                    <section class="am-panel am-panel-default"
                             :data-am-modal="modal(x.id)">
                        <main class="am-panel-bd" style="padding: 0px; height: 125px; overflow: hidden">
                            <img :src="x.imgs[0].path" alt="" width="100%" >
                        </main>
                        <footer class="am-panel-footer">@{{x[langname(lang, 'name')]}}</footer>
                    </section>
                    <div class="am-modal am-modal-no-btn" tabindex="-1" :id="x.id">
                        <div class="am-modal-dialog">
                            <div class="am-modal-hd">
                                @{{x[langname(lang, 'name')]}}
                                <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                            </div>
                            <div class="am-modal-bd" style="overflow: auto; height: 530px">
                                <article class="am-article">
                                    <div class="am-article-bd" style="text-align: left">
                                        <p class="am-article-lead">
                                            @{{x[langname(lang, 'det')]}}--@{{x[langname(lang, 'price')]}}
                                        </p>
                                    </div>
                                </article>
                                <template v-for="i in x.imgs">
                                    <img :src="i.path" alt="" width="100%" >
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>


</div>
<div class="sidebar">
    <button class="am-btn am-btn-default allprod" style="margin: 10px 0px 0 10px">@lang('index.allprod')</button>
    <ul class="am-tree am-tree-folder-select" role="tree" id="myTreeSelectableFolder" style="margin-top: 0px">
        <li class="am-tree-branch am-hide" data-template="treebranch" role="treeitem" aria-expanded="false">
            <div class="am-tree-branch-header">
                <button class="am-tree-icon am-tree-icon-caret am-icon-caret-right"><span class="am-sr-only">Open</span></button>
                <button class="am-tree-branch-name">
                    <span class="am-tree-icon am-tree-icon-folder"></span>
                    <span class="am-tree-label"></span>
                </button>
            </div>
            <ul class="am-tree-branch-children" role="group"></ul>
            <div class="am-tree-loader" role="alert">Loading...</div>
        </li>
        <li class="am-tree-item am-hide" data-template="treeitem" role="treeitem">
            <button class="am-tree-item-name">
                <span class="am-tree-icon am-tree-icon-item"></span>
                <span class="am-tree-label"></span>
            </button>
        </li>
    </ul>
</div>
<script>
    var lang = '{{(session()->exists("lang")) ? session("lang") : "en"}}';

    var app = new Vue({
        el: '.goods',
        data: function () {
            return {
                goods: [{imgs:[]}],
                lang: lang
            }
        },
        mounted: function () {
            var that = this;
            $.get("{{url('/getprods')}}", function (data) {
                var res = JSON.parse(data);
                that.goods = res;
            });
        },
        methods: {
            modal: function (x) {
                return "{target: '#" + x + "', width: 800, height: 600}";
            },
            langname: function (lang, sort) {
                var res = 'en' + sort;
                switch (lang) {
                    case 'en': res = 'en' + sort;break;
                    case 'zh': res = 'zh' + sort;break;
                    case 'ru': res = 'ru' + sort;break;
                }
                console.log(res);
                return res;
            }
        }
    });

    $(".allprod").click(function () {
        $.get("{{url('/getprods')}}", function (data) {
            var res = JSON.parse(data);
            app.goods = res;
        });
    });

    var pid = -1;
    var $myTree = $('#myTreeSelectableFolder');
    $myTree.tree({
        dataSource: function(options, callback) {
            if (options.attr) {
                pid = options.attr.id;
            }
            $.get('{{url("/getsorts")}}?pid=' + pid, function(data) {
                var res = JSON.parse(data);
                var sorts = [];
                for (var x in res) {
                    var sort = {
                        title: '',
                        type: '',
                        attr: {
                            id: '-1',
                            classNames: ['-1']
                        }
                    }
                    sort.title = res[x][lang];
                    sort.type = 'folder';
                    sort.attr.id = res[x].id;
                    sort.attr.classNames = [res[x].id];
                    sorts.push(sort);
                }
                callback({
//                    data: [
//                        {
//                            title: 'Ascending and Descending',
//                            type: 'folder',
//                            attr: {
//                                id: 'folder1'
//                            }
//                        }
//                    ]
                    data: sorts
                });
            });
        },
        multiSelect: false,
        folderSelect: true,
        cacheItems: false
    }).on('selected.tree.amui', function(e, data) {
        console.log(data)
        var sid = data.selected[0].attr.id;
        if (pid == -1) return;
        $.get("{{url('/getprodsbysort')}}?sid=" + sid, function (data) {
            res = JSON.parse(data);
            if (res.length == 0) {
                return;
            }
            app.goods = res;
        });
    }).on('loaded.tree.amui', function () {
        $myTree.tree('discloseAll');
    })

</script>
@endsection
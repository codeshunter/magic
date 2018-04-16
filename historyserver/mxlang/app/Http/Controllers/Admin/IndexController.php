<?php

namespace App\Http\Controllers\Admin;

use App\Model\indeximg;
use App\Model\pimg;
use App\Model\product;
use App\Model\sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //
    public function index()
    {
        $sort = new sort();
        $data = $sort->where('state', 1)->get();
        return view('admin.index')->with('data', $data);
    }

    public function cglang()
    {
        if (key_exists("lang", $_GET)) {
            $lang = $_GET['lang'];
            session(['lang' => $lang]);
            return 'success';
        } else {
            return 'error';
        }

    }

    public function login()
    {
//        echo Crypt::encrypt('dafishion123');
        return view('admin.login');
    }

    public function addsort()
    {
        $sort = new sort();
        $data = $sort->where('state', 1)->get();
        return view('admin.addsort')->with('data', $data);
    }

    public function delsort()
    {
        $id = $_GET['id'];
        $sort = new sort();
        $data = $sort->where('id', $id)->update(['state' => 0]);
        return redirect(url('admin'));
    }

    public function addsortcon()
    {
        $data = $_POST;
        $sort = new sort();
        $sort->en = $data['enname'];
        $sort->zh = $data['zhname'];
        $sort->ru = $data['runame'];
        $sort->pid = $data['pid'];
        $sort->state = 1;

        $sort->save();
        return redirect(url('admin'));
    }

    public function editsort()
    {
        $id = $_GET['id'];
        $sort = new sort();

        $detail = $sort->find($id);

        $data = $sort->where('state', 1)->where('id', '!=', $detail->id)->get();

        return view('admin.editsort')->with('data', $data)->with('detail', $detail);
    }

    public function editsortcon()
    {
        $data = $_POST;
        $sort = new sort();

        $sort->where('id', $data['id'])->update([
            'en' => $data['enname'],
            'zh' => $data['zhname'],
            'ru' => $data['runame'],
            'pid' => $data['pid']
        ]);

        return redirect(url('admin'));
    }

    public function addprod() {
        $sort = new sort();
        $data = $sort->where('state', 1)->get();
        return view('admin.addprod')->with('data', $data);
    }

    public function prodlist () {
        $product = new product();
        $data = $product->get();
        return view('admin.prodlist')->with('data', $data);
    }

    public function delprod () {
        $id = $_GET['id'];
        $product = new product();
        $pimg = new pimg();

        $product->where('id', $id)->delete();

        $paths = $pimg->where('pid', $id)->pluck('path');
        if (($paths[0]) == null) {
            return redirect(url('admin/prodlist'));
            $pimg->where('pid', $id)->delete();
        }
        foreach ($paths as $p) {
            unlink($p);
        }

        $pimg->where('pid', $id)->delete();

        return redirect(url('admin/prodlist'));
    }

    public function addprodcon() {
        $product = new product();
        $data = $_POST;
        $product->enname = $data['enname'];
        $product->zhname = $data['zhname'];
        $product->runame = $data['runame'];
        $product->endet = $data['endet'];
        $product->zhdet = $data['zhdet'];
        $product->rudet = $data['rudet'];
        $product->enprice = $data['enprice'];
        $product->zhprice = $data['zhprice'];
        $product->ruprice = $data['ruprice'];
        $product->sid = $data['sid'];

        $product->save();
        $prodid =  $product->id;

        $files = $this->getFiles();
        foreach ($files as $f) {
            $pimg = new pimg();
            $res = $this->uploadFile($f, ['jpg', 'png']);
            $pimg->path = $res['dest'];
//            url('/').'/'.$res['dest'];
            $pimg->pid = $prodid;
            $pimg->save();
        }

        return redirect(url('admin/prodlist'));
    }

    public function addindeximg () {
        return view('admin.addindeximg');
    }

    public function addindeximgcon () {
        $files = $this->getFiles();
//        var_dump($files);die;
        foreach ($files as $f) {
            $indeximg = new indeximg();
            $res = $this->uploadFile($f, ['jpg', 'png']);
//            var_dump($res);die;
            $indeximg->path = $res['dest'];
//            url('/').'/'.$res['dest'];
            $indeximg->save();
        }
        return redirect(url('admin/indeximglist'));
    }

    public function indeximglist () {
        $indeximg = new indeximg();
        $data = $indeximg->get();
        return view('admin.indeximglist')->with('data', $data);
    }

    public function delindeximg () {
        $id = $_GET['id'];
        $indeximg = new indeximg();
        $img = $indeximg->find($id);
        $path = $img->path;
        if(unlink($path)) {
            $indeximg->where('id', $id)->delete();
        }
        return redirect(url('admin/indeximglist'));
    }

    public function getFiles()
    {


        foreach ($_FILES as $file) {
            $fileNum = count($file['name']) + 1;
            if ($fileNum == 1) {

                $files = $file;
            } else {

                for ($i = 0; $i < $fileNum - 1; $i++) {
                    $files[$i]['name'] = $file['name'][$i];
                    $files[$i]['type'] = $file['type'][$i];
                    $files[$i]['tmp_name'] = $file['tmp_name'][$i];
                    $files[$i]['error'] = $file['error'][$i];
                    $files[$i]['size'] = $file['size'][$i];
                }
            }


        }
        return $files;
    }

    public function uploadFile($file, $allowExt, $path = './uploads') {
        if (!array_key_exists('name', $file)) {
            return;
        }
        $filename = $file['name'];
        $type = $file['type'];
        $temp_name = $file['tmp_name'];
        $error = $file['error'];
        $size = $file['size'];


        if ($error == UPLOAD_ERR_OK) {
//            if ($size > $max_size) {
//                $res['mes'] = $filename . "文件超过规定上传大小";
//            }
            $ext = $this->getExt($filename);
            if (!in_array($ext, $allowExt)) {
                $res['mes'] = $filename . '文件名不合乎规范';
            }
            if (!is_uploaded_file($temp_name)) {
                $res['mes'] = $filename . "文件不是通过HTTP POST 方法上传上传过来的";
            }


            if (@$res) {
                return $res;
            }


            if (!file_exists($path)) {
                mkdir($path, 0777, true);
                chmod($path, 0777);
            }
            $fname = $this->getUniName();


            $destination = $path . '/' . $fname . '.' . $ext;
            if (move_uploaded_file($temp_name, $destination)) {
                $res['mes'] = $filename . '上传成功';
                $res['dest'] = $destination;
            } else {
                $res['mes'] = $filename . "文件上传失败";
            }
        } else {
            switch ($error) {
                case '1':
                    $res['mes'] = "超过了配置文件上传文件的大小";
                    break;
                case '2':
                    $res['mes'] = "超过表单设置上传文件文件的大小";
                    break;
                case '3':
                    $res['mes'] = "文件部分被上传";
                    break;
                case '4':
                    $res['mes'] = "没有文件被上传";

                    break;
                case '6':
                    $res['mes'] = "没有找到临时目录";
                    break;
                case '7':
                    $res['mes'] = "文件不可写";

                    break;
                default:
                    $res['mes'] = "上传文件失败";
                    break;
            }
        }

        return $res;
    }

    public function getExt($filename){
        $arr=explode('.', basename($filename));

        return end($arr);
    }
    /**
     * 获得文件唯一扩展名
     * @return string 经过md5后生成32位唯一的上传文件名
     */
    public function getUniName(){

        return md5(uniqid(microtime(true),true));
    }
}

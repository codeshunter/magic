<?php

namespace App\Http\Controllers\Index;

use App\Model\indeximg;
use App\Model\pimg;
use App\Model\product;
use App\Model\sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    //
    public function index () {
        $indeximg = new indeximg();
        $imgs = $indeximg->get();
        return view('index.index', [
            'imgs' => $imgs
        ]);
    }

    public function getsorts () {
        $pid = $_GET['pid'];
        $sort = new sort();
        $data = $sort->where('pid', $pid)->where('state', 1)->get();
        return json_encode($data);
    }

    public function getallsorts () {
        //$pid = $_GET['pid'];
        $sort = new sort();
        $data = $sort->where('state', 1)->get();
        $resarr = $this->generateTree($data->toArray());
        return json_encode($resarr);
    }

    public function generateTree($items){
        $tree = array();
        foreach($items as $item){
            if(isset($items[$item['pid']])){
                $items[$item['pid']]['son'][] = &$items[$item['id']];
            }else{
                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
    }

    public function getprodsbysort () {
        $sid = $_GET['sid'];
        $product = new product();
        $pimg = new pimg();
        $res = [];
        $goods = $product->where('sid', $sid)->get();
        foreach ($goods as $k => $v) {
            $res[$k] = $v;
            $imgs = $pimg->where('pid', $v->id)->get();
            $res[$k]['imgs'] = $imgs;
        }
        return json_encode($goods);
    }

    public function getprods () {
        $product = new product();
        $pimg = new pimg();
        $res = [];
        $data = $product->orderBy('created_at', 'desc')->get();
        foreach ($data as $k => $v) {
            $res[$k] = $v;
            $imgs = $pimg->where('pid', $v->id)->get();
            $res[$k]['imgs'] = $imgs;
        }
        return json_encode($res);
    }
}

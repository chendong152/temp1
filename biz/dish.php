<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-05
 * Time: 1:38
 */
class Dish {
    public $stuff = 0;//原料
    public $technic = 0;//工艺
    public $culture = 0;//文化
    public $taste = 0;//口味
    public $costEffective = 0;//性价比
    public $img = "";//
    public $name = "";

    function  __construct($s = 0, $t = 0, $c = 0, $taste = 0, $cost = 0, $name = '', $img = '') {
        $this->stuff = $s;
        $this->technic = $t;
        $this->culture = $c;
        $this->taste = $taste;
        $this->costEffective = $cost;
        $this->img = $img;
        $this->name = $name;
    }

    public static function  merge($arr) {
        $merge = new Dish();
        foreach ($arr as $dish) {
            $merge->stuff += $dish->stuff;
            $merge->technic += $dish->technic;
            $merge->culture += $dish->culture;
            $merge->taste += $dish->taste;
            $merge->costEffective += $dish->costEffective;
        }
        return $merge;
    }
}

function cb($d1, $d2) {
    return $d1[key($d1)] - $d2[key($d2)];
}

function getText(Dish $dish) {
    $descs = array(
        'stuff'         => '原料好',
        'technic'       => '工艺好',
        'culture'       => '文化人',
        'taste'         => '口水从',
        'costEffective' => '按需求',
    );
    $ref = new ReflectionClass($dish);
    $kvs = array();
    foreach ($descs as $k => $v) {
        $p = $ref->getProperty($k);
        $kvs[] = array($k => empty($p) ? 0 : $p->getValue($dish));
    }
    //print_r($kvs);
    usort($kvs, "cb");
    $high = $kvs[count($kvs) - 1];
    $low = $kvs[0];
    //print_r($kvs);
    return "{$descs[key($low)]}的{$descs[key($high)]}";
}

//定义所有的菜品
$allDishes = array(
    new Dish(3, 13, 3, 3, 3, '黄河口四大缸', "0.jpg"),
    new Dish(11, 3, 4, 5, 6, '黄河口四大缸', "0.jpg"),
    new Dish(3, 3, 13, 3, 3, '黄河口四大缸', "0.jpg"),
    new Dish(1, 13, 3, 13, 3, '黄河口四大缸', "0.jpg"),
    new Dish(3, 3, 13, 3, 3, '黄河口四大缸', "0.jpg"),
    new Dish(13, 1, 13, 8, 1, '黄河口四大缸', "0.jpg"),
    new Dish(3, 3, 13, 13, 3, '黄河口四大缸', "0.jpg"),
    new Dish(3, 13, 3,13, 3, '黄河口四大缸', "0.jpg"),
    new Dish(13, 33, 3, 3, 3, '黄河口四大缸', "0.jpg"),
    new Dish(13, 3, 1, 3, 13, '黄河口四大缸', "0.jpg"),
    new Dish(3, 13, 3, 3, 3, '黄河口四大缸', "0.jpg"),
    new Dish(13, 13, 3, 3, 3, '黄河口四大缸', "0.jpg"),
);
<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-05
 * Time: 1:38
 */

require_once __DIR__ . '/../config/config.php';

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

    public function  toArray() {
        $ret = array();
        foreach (self::getPs() as $k)
            $ret[$k] = $this->$k;
        return $ret;
    }

    public static function  getPs() {
        return array(
            'stuff',
            'technic',
            'culture',
            'taste',
            'costEffective',
        );
    }


    public static function  merge(array $arr) {
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

    public static function  equal(Dish $d1, Dish $d2) {
        return $d1->name == $d2->name;
    }
}


function getScore(Dish $dish) {
    $ps = Dish::getPs();
    $ref = new ReflectionClass($dish);
    $kvs = array();
    foreach ($ps as $k) {
        $p = $ref->getProperty($k);
        $kvs[] = array($k => empty($p) ? 0 : $p->getValue($dish));
    }
    $cb = function ($d1, $d2) {
        return $d1[key($d1)] - $d2[key($d2)];
    };
    usort($kvs, $cb);
    return array('high' => $kvs[count($kvs) - 1], 'low' => $kvs[0]);
}

function getText(Dish $dish) {
    global $allDesc;
    $scores = getScore($dish);
    $k = key($scores['high']) . '-' . key($scores['low']);
    return $allDesc[$k];
}

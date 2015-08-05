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

function getScore(Dish $dish){
	$ps = array(
        'stuff'        ,
        'technic'      ,
        'culture'      ,
        'taste'        ,
        'costEffective',
    );
    $ref = new ReflectionClass($dish);
    $kvs = array();
    foreach ($ps as $k){
        $p = $ref->getProperty($k);
        $kvs[] = array($k => empty($p) ? 0 : $p->getValue($dish));
    }
    //print_r($kvs);
    usort($kvs, "cb");
    $high = $kvs[count($kvs) - 1];
    $low = $kvs[0];
    //print_r($kvs);
	//print_r($high);
	return array('high'=>$high,'low'=>$low);
}
function getText(Dish $dish) {
	global $allDesc;
	$scores=getScore($dish);
	$k=key($scores['high']).'-'.key($scores['low']);
    return $allDesc['taste-costEffective'];
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

//描述
$allDesc=array(
	'stuff-technic'=>array('美食家','牛X，小细节都不放过'),
	'stuff-culture'=>array('美食家','牛X2，小细节都不放过'),
	'stuff-taste'=>array('美食家','牛X10，小细节都不放过'),
	'stuff-costEffective'=>array('美食家','牛X，小细节都不放过'),
	'technic-stuff'=>array('美食家','牛X，小细节都不放过'),
	'technic-culture'=>array('美食家','牛X4，小细节都不放过'),
	'technic-taste'=>array('美食家','牛X，小细节都不放过'),
	'technic-costEffective'=>array('美食家','牛X，小细节都不放过'),
	'culture-stuff'=>array('美食家','牛X3，小细节都不放过'),
	'culture-technic'=>array('美食家','牛X，小细节都不放过'),
	'culture-taste'=>array('美食家','牛X，小细节都不放过'),
	'culture-costEffective'=>array('美食家','牛X，小细节都不放过'),
	'taste-stuff'=>array('美食家','牛X5，小细节都不放过'),
	'taste-technic'=>array('美食家','牛X11，小细节都不放过'),
	'taste-culture'=>array('美食家','牛X9，小细节都不放过'),
	'taste-costEffective'=>array('美食家','牛X，小细节都不放过'),
	'costEffective-stuff'=>array('美食家','牛X6，小细节都不放过'),
	'costEffective-technic'=>array('美食家','牛X7，小细节都不放过'),
	'costEffective-culture'=>array('美食家','牛X，小细节都不放过'),
	'costEffective-taste'=>array('美食家','牛X8，小细节都不放过'),
);
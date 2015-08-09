<?php
/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-05
 * Time: 23:13
 */
include_once __DIR__ . '/dish.php';

//定义所有的菜品
$allDishes = array(
    new Dish(3, 13, 3, 3, 3, '黄河口四大缸', "img/2/cai_simple.png"),
    new Dish(11, 3, 4, 5, 6, '长江口四大缸', "img/2/cai_simple.png"),
    new Dish(3, 3, 13, 3, 3, '工口四大缸', "img/2/cai_simple.png"),
    new Dish(1, 13, 3, 13, 3, '大清口四大缸', "img/2/cai_simple.png"),
    new Dish(3, 3, 13, 3, 3, '水上口四大缸', "img/2/cai_simple.png"),
    new Dish(13, 1, 13, 8, 1, '水到渠成口四大缸', "img/2/cai_simple.png"),
    new Dish(3, 3, 13, 13, 3, '以黄河口四大缸', "img/2/cai_simple.png"),
    new Dish(3, 13, 3, 13, 3, '中河口四大缸', "img/2/cai_simple.png"),
    new Dish(13, 33, 3, 3, 3, '为河口四大缸', "img/2/cai_simple.png"),
    new Dish(13, 3, 1, 3, 13, '清河口四大缸', "img/2/cai_simple.png"),
    new Dish(3, 13, 3, 3, 3, '江河口四大缸', "img/2/cai_simple.png"),
    new Dish(13, 13, 3, 3, 3, '海河口四大缸', "img/2/cai_simple.png"),
);
/**
 * 根据菜品ID获取菜品实体
 * @param $d  菜品id 或 菜品id数组
 * @return mixed
 */
function get_dish($d) {
    global $allDishes;
    if (is_string($d) && strpos($d, ',') > -1) $d = explode(',', $d);
    return is_array($d) ? array_map('get_dish', $d) : ($d instanceof Dish ? $d : $allDishes[$d]);
}

//描述
$allDesc = array(
    'stuff-technic'         => array('美食家', '牛X，小细节都不放过牛X，小细节都不放过牛X，小细节都不放过牛X，小细节都不放过'),
    'stuff-culture'         => array('美食家', '牛X2，小细节都不放过'),
    'stuff-taste'           => array('美食家', '牛X10，小细节都不放过'),
    'stuff-costEffective'   => array('美食家', '牛X，小细节都不放过'),
    'technic-stuff'         => array('美食家', '牛X，小细节都不放过'),
    'technic-culture'       => array('美食家', '牛X4，小细节都不放过'),
    'technic-taste'         => array('美食家', '牛X，小细节都不放过'),
    'technic-costEffective' => array('美食家', '牛X，小细节都不放过'),
    'culture-stuff'         => array('美食家', '牛X3，小细节都不放过'),
    'culture-technic'       => array('美食家', '牛X，小细节都不放过'),
    'culture-taste'         => array('美食家', '牛X，小细节都不放过'),
    'culture-costEffective' => array('美食家', '牛X，小细节都不放过'),
    'taste-stuff'           => array('美食家', '牛X5，小细节都不放过'),
    'taste-technic'         => array('美食家', '牛X11，小细节都不放过'),
    'taste-culture'         => array('美食家', '牛X9，小细节都不放过'),
    'taste-costEffective'   => array('美食家', '牛X，小细节都不放过'),
    'costEffective-stuff'   => array('美食家', '牛X6，小细节都不放过'),
    'costEffective-technic' => array('美食家', '牛X7，小细节都不放过'),
    'costEffective-culture' => array('美食家', '牛X，小细节都不放过'),
    'costEffective-taste'   => array('美食家', '牛X8，小细节都不放过'),
);
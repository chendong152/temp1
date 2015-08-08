<?php

/**
 * Created by Dong.
 * User: Dong(mailto:techdong@hotmail.com)
 * Date: 2015-08-05
 * Time: 20:09
 */
require_once __DIR__ . '/dish.php';

class User {

    public $openid;
    public $nickname;
    public $header_img;


    /**
     * 计算相似度
     * @param $friend
     *
     */
    public function match(User $friend) {
        $ud = Dish::merge($this->dishes);
        $fd = Dish::merge($friend->dishes);
        $score = min($ud->stuff, $fd->stuff) / max($ud->stuff, $fd->stuff)
            + min($ud->technic, $fd->technic) / max($ud->technic, $fd->technic)
            + min($ud->culture, $fd->culture) / max($ud->culture, $fd->culture)
            + min($ud->taste, $fd->taste) / max($ud->taste, $fd->taste)
            + min($ud->costEffective, $fd->costEffective) / max($ud->costEffective, $fd->costEffective);
        $dishScore = count(array_uintersect($this->dishes, $friend->dishes, 'Dish::equal'));

        return $score * 14 + $dishScore * 10;
    }

    public static function from($data) {
        $c = new ReflectionClass(self);
        $ps = $c->getProperties();
        $ret = new User();
        foreach ($ps as $p) {
            if (array_key_exists($p->name, $data)) $p->setValue($ret, $data[$p->name]);
        }
        return $ret;
    }
}

class Record {
    public $id;
    public $openid;

    /**
     * array of Dish，选中的菜品
     * @var array
     */
    public $dishes = array();

    /**
     * 选择的菜品总分集合
     * @var array
     */
    public $style = array();

    public $score_high;
    public $score_low;

    public $result_kind;
    public $result_detail;

    public $create_time;

    public static function from($data) {
        $c = new ReflectionClass(self);
        $ps = $c->getProperties();
        $ret = new User();
        foreach ($ps as $p) {
            if (array_key_exists($p->name, $data)) $p->setValue($ret, $data[$p->name]);
        }
        return $ret;
    }
}
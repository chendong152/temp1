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
    //new Dish(5, 2.5, 1.5, 3.5, 2.5, '红烧千岛湖鱼头', "img/cai/红烧千岛湖鱼头.png"),
    //new Dish(4.5, 2, 1, 3, 4.5, '红烧长江小杂鱼', "img/cai/红烧长江小杂鱼.png"),
    new Dish(4, 3.5, 3, 3, 1.5, '红透天龙虾', "img/cai/xia.png"),
    //new Dish(3, 4, 2, 5, 1, '花开富贵', "img/cai/花开富贵.png"),
    // new Dish(3, 4, 2.5, 3.5, 2, '黄河口四大缸', "img/cai/黄河口四大缸.png"),
    new Dish(4.5, 3, 3.5, 3, 1, '李鸿章烩菜', "img/cai/li.png"),
    new Dish(3.5, 2.5, 5, 1, 3, '全家福', "img/cai/quanjia.png"),
    //new Dish(4.5, 3.5, 1, 3, 3, '石锅酱焖牛肉', "img/cai/石锅酱焖牛肉.png"),
    new Dish(4, 2.5, 1, 4.5, 3, '酸菜鱼', "img/cai/suancai.png"),
    new Dish(3.5, 5, 1, 3.5, 2, '炭烤深海碟鱼头', "img/cai/yutou.png"),
    // new Dish(3.5, 4, 4, 2, 1.5, '鲜响螺冬瓜盅', "img/cai/鲜响螺冬瓜盅.png"),
    new Dish(3.5, 2.5, 3, 3, 3, '湘楚霸王鸡', "img/cai/ji.png"),
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
    'stuff-technic'         => array('无脑型吃货', '根本不在乎吃什么，吃东西就是为了满足最原始的需要，绝对的不挑食，不矫情，不拒绝三不原则。'),
    'stuff-culture'         => array('路人型吃货', '以填饱肚子为主，吃完了就离开现场，转瞬就忘记了是什么味道，挥一挥衣袖，不带走一片云彩。'),
    'stuff-taste'           => array('懵懂型吃货', '总会叫嚣着减肥，但是只能坚持几分钟，在吃饱和吃好之间已有了一丝模糊的界限'),
    'stuff-costEffective'   => array('土豪型吃货', '出入高档餐厅，在朋友圈一发吃，就被人评论“太有钱，太任性了”，在吃上毫不吝啬钱财。'),
    'technic-stuff'         => array('专家型吃货', '选择美食对象成熟，好奇心重，有了解未知领域的强烈企图。会去发现新的口味，做第一个品尝螃蟹的人。'),
    'technic-culture'       => array('文艺型吃货', '最看不起什么百年老店，一般会选择比较有逼格的餐馆。吃了一顿美食后，有文笔没文笔的，都想着要写一篇别人看不懂的文字。'),
    'technic-taste'         => array('文艺型吃货', '最好探险的吃货。新开的餐馆，很快有你的踪影，每当朋友循着你的足迹去那就餐，你会获得巨大的精神满足。'),
    'technic-costEffective' => array('文艺级吃货', '你常对热门餐馆嗤之以鼻，朋友圈高冷的几乎没有什么关于美食的长篇大论。你热衷光顾不起眼的小馆，总能吃到菜单上没有的菜式。'),
    'culture-stuff'         => array('无脑型吃货', '没什么可说的，每过一个小时就会饿一次，始终无法知晓自己爱吃什么，因为就没有自己不吃的。'),
    'culture-technic'       => array('内涵型吃货', '你的逼格源自出色的分寸感。你关注食材本身多于烹饪；会从学术角度分析菜品味道如何不足，不会溢于言表，身上充满哲人的气质。'),
    'culture-taste'         => array('专家型吃货', '别人眼中的美食家，见多识广，在精神上追求美食的由来与工艺，吃不多，但会花时间去分析食物，把研究食物转化成一种学术知识。'),
    'culture-costEffective' => array('路人型吃货', '吃东西是一次美妙体验，唯独睡觉与美食不可辜负，爱吃的东西很多，也喜欢自己动手烹饪。'),
    'taste-stuff'           => array('内涵型吃货', '吃货我最强，俨然超脱了吃的范畴，潜心于研究食物与味蕾的化学反应，令人肃然起敬。'),
    'taste-technic'         => array('普通级型货', '一种食物好不好吃并不是最关心的事，最在意的是能不能最大限度地尝遍身边的所有美食。吃出花样、吃出好心情。'),
    'taste-culture'         => array('底蕴型吃货', '懂得吃的礼仪和传统，譬如牛扒怎么点、葡萄酒怎么喝，不善言语，但只要说出关于吃的任何细节，总能招来无限信众。'),
    'taste-costEffective'   => array('土豪型吃货', '懂得如何吃，还比任何人都舍得花钱去吃，大有一种你吃不起，只有我吃得起的霸气。'),
    'costEffective-stuff'   => array('路人型吃货', '你很宽容，只要食物稍满足你，就能开心地点赞，从小吃摊吃到连锁店，自觉不装逼，每天都乐在吃中。'),
    'costEffective-technic' => array('文艺型吃货', '用餐环境、气氛及当时的心情，通常比食物本身来得更关键、更重要，可以说，吃的是一种感觉。'),
    'costEffective-culture' => array('专家型吃货', '开始探索菜系与门道，足够写出一本食谱，出席美食节目，做个美食评论家绰绰有余。'),
    'costEffective-taste'   => array('路人型吃货', '再怎么忍，也抑制不住拿出手机拍照。美食当前，即使已经食指大动，但还是会在吃饭前“咔嚓咔嚓”来几张！'),
);
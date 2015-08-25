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
    //new Dish(3, 13, 3, 3, 3, '红烧千岛湖鱼头', "img/cai/红烧千岛湖鱼头.png"),
    //new Dish(11, 3, 4, 5, 6, '红烧长江小杂鱼', "img/cai/红烧长江小杂鱼.png"),
    new Dish(3, 3, 13, 3, 3, '红透天龙虾', "img/cai/xia.png"),
    //new Dish(1, 13, 3, 13, 3, '花开富贵', "img/cai/花开富贵.png"),
   // new Dish(3, 3, 13, 3, 3, '黄河口四大缸', "img/cai/黄河口四大缸.png"),
    new Dish(13, 1, 13, 8, 1, '李鸿章烩菜', "img/cai/li.png"),
    new Dish(3, 3, 13, 13, 3, '全家福', "img/cai/quanjia.png"),
    //new Dish(13, 13, 3, 3, 3, '石锅酱焖牛肉', "img/cai/石锅酱焖牛肉.png"),
    new Dish(3, 13, 3, 13, 3, '酸菜鱼', "img/cai/suancai.png"),
    new Dish(13, 13, 3, 3, 3, '炭烤深海碟鱼头', "img/cai/yutou.png"),
   // new Dish(13, 3, 1, 3, 13, '鲜响螺冬瓜盅', "img/cai/鲜响螺冬瓜盅.png"),
    new Dish(3, 13, 3, 3, 3, '湘楚霸王鸡', "img/cai/ji.png"),
);
/**
 * 根据菜品ID获取菜品实体
 * @param $d  菜品id 或 菜品id数组
 * @return mixed
 */
function get_dish($d)
{
    global $allDishes;
    if (is_string($d) && strpos($d, ',') > -1) $d = explode(',', $d);
    return is_array($d) ? array_map('get_dish', $d) : ($d instanceof Dish ? $d : $allDishes[$d]);
}

//描述
$allDesc = array(
    'stuff-technic' => array('入门级吃货', '不在乎吃什么，对很多菜欣赏无能。吃东西是为了满足生存和生长的需要。但在我们眼里，吃，体现了最原始的乐趣。'),
    'stuff-culture' => array('普通级吃货', '吃饭以填饱肚子为主，吃完了就离开现场，转瞬就忘记了是什么味道，挥一挥衣袖，不带走一片云彩。'),
    'stuff-taste' => array('入门级吃货', '一时兴起要饮食减肥了，但是只能坚持几小时，在饿死和撑死之间毫不犹豫的选择后者'),
    'stuff-costEffective' => array('土豪级吃货', '你认为找饭馆一分钱一分货。你享受味觉刺激，也会审视餐馆的格调。你在朋友圈一发吃，就被人评论“太有钱了”，不认同但又习以为常，因为我就是我，是“和你们不一样的吃货”。'),
    'technic-stuff' => array('专家级吃货', '选择美食对象成熟，好奇心重，有了解未知领域的强烈企图。会去发现新的口味，并且能够掌握和实现他们。'),
    'technic-culture' => array('文艺级吃货', '一般会选择比较有腔调的餐馆。最好是什么百年老店，吃了一顿美食后，有文笔没文笔的，都想着要写一篇饭后感。'),
    'technic-taste' => array('文艺级吃货', '最精明的吃货。新开的餐馆，很快有你的踪影。你不爱装逼和高消费，但每当朋友循着你的足迹去那就餐，你会获得巨大的满足。'),
    'technic-costEffective' => array('文艺级吃货', '你常对热门餐馆嗤之以鼻，会淡淡地说，老字号不如从前了。你热衷光顾不起眼的小馆，也时常能吃到菜单上没有的菜式。'),
    'culture-stuff' => array('入门级吃货', '每过一个小时就会饿，始终无法知晓自己爱吃什么，因为就没有自己不吃的。'),
    'culture-technic' => array('底蕴级吃货', '你的逼格源自出色的分寸感。你关注食材本身多于烹饪；会提醒服务生少放味精，也会对旁人说，菜单的法文错了。就算吃得满意，也不会溢于言表，只是轻轻地说声“还行”。'),
    'culture-taste' => array('专家级吃货', '美食家，敏锐，见多识广，在精神上追求美食的至高愉悦，吃不多，但会花时间去分析食物，把食物转化成一种知识的话语。'),
    'culture-costEffective' => array('普通级吃货', '吃东西是一次美妙体验，正如书籍作为精神食粮能够丰富精神世界一样，爱吃的东西很多，也喜欢自己动手烹饪。'),
    'taste-stuff' => array('底蕴级吃货', '关注食材本身多于烹饪；就算吃得满意，也不会溢于言表，只是轻轻地说声“还行”。'),
    'taste-technic' => array('普通级吃货', '一种食物好不好吃并不是最关心的事，最在意的是能不能最大限度地尝遍身边的所有美食。吃出花样、吃出好心情。'),
    'taste-culture' => array('底蕴级吃货', '除了爱吃，还要求懂吃。不断学习牛扒怎么点、葡萄酒怎么喝，了解关于吃的任何细节，让别人对你刮目相看。'),
    'taste-costEffective' => array('土豪级吃货', '一般吃东西绝对不为填肚子。这群吃货，不仅懂得如何吃，还比任何人都有资本去吃。'),
    'costEffective-stuff' => array('普通级吃货', '你很宽容，只要食物稍满足你，就能开心地点赞，从小吃摊吃到连锁店，自觉不装逼，每天都乐在吃中。'),
    'costEffective-technic' => array('文艺级吃货', '用餐环境、气氛及当时的心情，通常比食物本身来得更关键、更重要，可以说，吃的是一种感觉。'),
    'costEffective-culture' => array('专家级吃货', '不再无脑吃吃吃，已经脱离了什么都吃的状态，开始探索菜系与门道。'),
    'costEffective-taste' => array('普通级吃货', '再怎么忍，也抑制不住拿出手机拍照。美食当前，即使已经食指大动，但还是会在吃饭前“咔嚓咔嚓”来几张！'),
);
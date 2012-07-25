YII-jqzoom
==========

wrapper the jquery jqzoom to yii community


in your view file :

~~~
[php]
<?php
/**
 * User: yiqing
 * Date: 11-12-3
 * Time: 下午4:07
 */

$this->widget(
    'common.widgets.jqzoom.JqZoom',
    array('selector'=>'.jqzoom' )
);
?>

<a href="http://www.zjol.com.cn/pic/0/02/07/71/2077119_039197.jpg" class="jqzoom" title="prettyGirl！" rel="gal1">
    <img src="http://www.zjol.com.cn/pic/0/02/07/71/2077119_039197.jpg" title="IMAGE TITLE">
</a>
~~~
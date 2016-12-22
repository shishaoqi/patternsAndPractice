<?php
/**
 * @Author: anchen
 * @Date:   2016-03-06 20:59:54
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-12 21:13:11
 * @Page 67
 */
header("Content-type: text/html; charset=utf-8"); 

class Product {
    public $name;
    public $price;

    function __Construct($name, $price) {
        $this->name = $name;
        $this->price = $price;
    }
}

class ProcessSale {
    private $callbacks;

    function  registerCallback( $callback ) {
        if(! is_callable(( $callback ))) {
            throw new Exception( "callback not callable" );
        }
        $this->callbacks[] = $callback;
    }

    function sale( $product ) {
        print ("{$product->name}: processing <br>");
        foreach( $this->callbacks as $callback ){
            call_user_func( $callback, $product );
        }
    }
}
/**
 * 回调为什么有用？利用回调，你可以在运行时将与组件的核心任务 没有直接关系的功能插入到组件中。有了组件回调，       * 你就能赋予了其他在你不知道的上下文中扩展你的代码的权利。
 */

/* demon1 */
$logger = create_function( '$product', 'print " logging({$product->name})<br> ";' );
$logger2 = create_function( '$product', 'print " logging2({$product->name})<br> ";' );


$processor = new ProcessSale();
$processor->registerCallback($logger);
$processor->registerCallback($logger2);

$processor->sale( new Product("shoes", 6));
print('<br>');
$processor->sale( new Product('coffee', 6));
print('<br>');


print("<br>php5.3及其后续版本提供了更好的方法来实现该功能。<br>你可以简单地在一条语句中声明并分配
    函数。使用了新语法后的create_function()示例如下所示：<br>");
$logger3 = function( $product ){
    print(" logging ({$product->name})<br>");
};

$processor = new ProcessSale();
$processor->registerCallback( $logger3 );

$processor->sale( new Product ("shoes", 6) );
print "<br>";
$processor->sale( new Product ("coffee", 6) );
print('<br>');

//------回调不一定要匿名------
print("<br>------回调不一定要匿名------<br>");
class Mailer {
    function doMail($product) {
        print("mailing({$product->name})<br>");
    }
}

$processor = new ProcessSale();
$processor->registerCallback(array(new Mailer(), "doMail"));
$processor->sale(new Product("shoes", 6));
print("<br>");
$processor->sale(new Product("coffee", 6));

print("<br>");
print("<br>方法 返回 匿名函数：<br>");

class Totalizer {
    static function warnAmount() {
        return function ( $product ){
            if($product->price > 5){
                print("reached high price:{$product->price}<br>");
            }
        };
    }
}

$processor = new ProcessSale();
$processor->registerCallback(Totalizer::warnAmount());

print("<br>");
print("<br>利用 use 子句，就可以让匿名函数追踪来自其父作用域的变量：<br>");

class Totalizer2 {
    static function warnAmount($amt) {
        return function ( $product ) use ($amt, &$count){
            $count += $product->price;
            print("count: $count <br>");
            if($count > $amt){
                print(" high price reached:{$count}<br>");
            }
        };
    }
}
$processor = new ProcessSale();
$processor->registerCallback(Totalizer2::warnAmount(8));

$processor->sale( new Product("shoes", 6));
print('<br>');
$processor->sale( new Product('coffee', 6));
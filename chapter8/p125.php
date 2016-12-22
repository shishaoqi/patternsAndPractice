<?php
/**
 * @Author: anchen
 * @Date:   2016-03-08 05:30:13
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-21 18:48:44
 */
abstract class Lesson {
    private $duration;
    private $costStrategy;

    function __construct($duration, CostStrategy $strategy){
        $this->duration = $duration;
        $this->costStrategy = $strategy;
    }

    //cost()方法需要一个Lesson实例，用于生成费用数据
    function cost() {
        return $this->costStrategy->cost($this);
    }

    function chargeType(){
        return $this->costStrategy->chargeType();
    }

    function getDuration(){
        return $this->duration;
    }
    //Lesson的更多 方法
}

class Lecture extends Lesson{
    //Lecture特定的实现
}

class Seminar extends Lesson{
    //seminar特定的实现
}

abstract class CostStrategy{
    abstract function cost(Lesson $lesson);
    abstract function chargeType();
}

class TimeedCostStrategy extends CostStrategy{
    function cost(Lesson $lesson){
        return ($lesson->getDuration() * 5);
    }

    function chargeType(){
        return "hourly rate";
    }
}

class FixedCostStrategy extends CostStrategy{
    function cost(Lesson $lesson){
        return 30;
    }

    function chargeType(){
        return "fixed rate";
    }
}

$lesson[] = new Seminar(4, new TimeedCostStrategy());
$lesson[] = new Lecture(4, new FixedCostStrategy());

foreach ($lessons as $lesson) {
    print("lesson charge {$lesson->cost()}.");
    print("Charge type: {$lesson->chargetype()}<br>");   
}
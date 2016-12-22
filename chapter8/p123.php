<?php
/**
 * @Author: anchen
 * @Date:   2016-03-08 05:00:08
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-21 18:14:50
 */
abstract class Lesson {
    protected $duration;
    const FIXED = 1;
    const TIMED = 2;
    private $costtype;

    function __construct($duration, $costtype=1){
        $this->duration = $duration;
        $this->costtype = $costtype;
    }

    function cost() {
        switch($this->costtype){
            case self::TIMED:
                retrun (5*$this->duration);
                break;
            case self::FIXED:
                return 30;
                break;
            default:
                $this->costtype = self::FIXED;
        }
    }

    function chargeType(){
        switch ($this->costtype){
            case self::TIMED:
                return 'hourly rate';
                break;
            case self::FIXED:
                return 'fixed rate';
                break;
            default:
                $this->costtype = self::FIXED;
                return 'fixed rate';
        }
    }
    //Lesson的更多 方法
}

class Lecture extends Lesson{
    //Lecture特定的实现
}

class Seminar extends Lesson{
    //seminar特定的实现
}

//下面演示如何使用这些类
$lecture = new Lecture(5, Lesson::FIXED);

print("{$lecture->const()} ({$lecture->chargeType()})<br>");

$seminar = new Seminar(3, Lesson::TIMED);
print("{$seminar->cost()} ({$seminar->chargeType()})<br>");
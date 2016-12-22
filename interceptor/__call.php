<?php
/**
 * @Author: anchen
 * @Date:   2016-03-13 19:58:38
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-19 17:38:58
 */

/**
 * __call() 方法对于实现委托也很作用。委托是指一个对象 转发或委托一个请求给另一个对象，
 * 被委托的一方法替原先对象处理请求。这类似于继承，和在子类中调用 父类的方法有点相似。
 * 但在继承时，父类与子类的关系是固定的，而使用委托则可以在代码时改变使用的对象，这意味着
 * 委托比继承具有更大的灵活性。
 */

class PersonWriter {
    function writeName(Person $p){
        print($p->getName()."<br>");
    }

    function writeAge(Person $p){
        print($p->getAge()."<br>");
    }
}

class Person {
    private $writer;

    function __construct(PersonWriter $writer){
        $this->writer = $writer;
    }

    function __call($methodname, $args) {
        if(method_exists($this->writer, $methodname)){
            return $this->writer->$methodname($this);
        }
    }

    function getName(){
        return "Bod";
    }

    function getAge(){
        return 44;
    }
}

$personWriterObj = new PersonWriter();
$person = new Person($personWriterObj); //new PersonWriter()
$person->writeName();
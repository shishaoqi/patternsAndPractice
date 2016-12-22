<?php
/**
 * @Author: anchen
 * @Date:   2016-03-07 01:12:55
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-19 18:19:36
 */
/**
 * 和许多示例代码一样，Conf类非常简单。特别是，它没有任何处理配置信息不不存在或不可写的策略。它看起来很乐观，假定XML文档的格式正确且包含了需要的元素。
 */
class Conf {
    private $file;
    private $xml;
    private $lastmatch;

    function __construct( $file ){
        $this->file = $file;
        $this->xml = simplexml_load_file($file);
    }

    function write(){
        file_put_contents($this->file, $this->xml->asXML());
    }

    function get ($str){
        $matches = $this->xml->xpath("/confitem[@name=\"$str\"]");
        if(count($matches)){
            $this->lastmatch = $matches[0];
            return (string)$matches[0];
        }
        return null;
    }

    function set($key, $value){
        if(!is_null($this->get($key))){
            return;
        }

        $conf = $this->xml->conf;
        $this->xml->addChild('item', $value->addAttribute('nmae', $key));
    }
}
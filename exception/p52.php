<?php
/**
 * @Author: anchen
 * @Date:   2016-03-07 01:32:10
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-19 18:41:58
 */
class Conf {
    private $file;
    private $xml;
    private $lastmatch;

    function __construct( $file ){
        $this->file = $file;
        if(! file_exists($file)){
            throw new Exception("file '$file' does not exist");
        }
        $this->xml = simplexml_load_file($file);
    }

    function write(){
        if(! is_writable(($this->file))){
            throw new Exception("file '{$this->file}' is not writeable");
        }
        file_put_contents($this->file, $this->xml->asXML());
    }

    function get ($str){
        $matches = $this->xml->xpath("/conf/item[@name=\"$str\"]");
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
        $this->xml->addChild('item', $value)->addAttribute('name', $key);
    }
}

//p53
try{
    $conf = new Conf(dirname(__FILE__)."/conf1.xml");
    print("user:".$conf->get('user')."<br>");
    print("host:".$conf->get('host')."<br>");
    $conf->set("pass","newpass");
    $conf->write();
}catch(Exception $e){
    die($e->__toString());
}
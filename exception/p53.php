<?php
/**
 * @Author: anchen
 * @Date:   2016-03-07 01:59:59
 * @Last Modified by:   anchen
 * @Last Modified time: 2016-03-19 20:15:54
 */
class XmlException extends Exception{
    private $error;

    function __construct(LibXmlError $error){
        $shortfile = basename($error->file);
        $msg = "[[[{$shortfile}, line {$error->line}, col {$error->column}]]] {$error->message}";
        $this->error = $error;
        parent::__construct($msg, $error->code);
    }

    function getLibXmlError(){
        return $this->error;
    }
}

class FileException extends Exception{}
class ConfException extends Exception{}

class Conf {
    private $file;
    private $xml;
    private $lastmatch;

    function __construct( $file ){
        $this->file = $file;
        if(! file_exists($file)){
            throw new FileException("file '$file' does not exist");
        }
        $this->xml = simplexml_load_file($file, null, LIBXML_NOERROR);

        if(! is_object($this->xml)){
            throw new XmlException(libxml_get_last_error());
        }

        print(gettype($this->xml));
        $matches = $this->xml->xpath("/conf");
        if(! count($matches)){
            throw new ConfException("could not find root element:conf");
        }
    }

    function write(){
        if(! is_writable(($this->file))){
            throw new FileException("file '{$this->file}' is not writeable");
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

//p54
class Runer {
    static function init(){
        try{
            $conf = new Conf(dirname(__FILE__)."/conf01.xml");
            print("user:".$conf->get('user')."<br>");
            print("host:".$conf->get('host')."<br>");
            $conf->set("pass","newpass");
            $conf->write();
        }catch(FileException $e){
            die($e->__toString());
        }catch(XmlException $e){
            die($e->__toString());
        }catch(ConfException $e){
            die($e->__toString());
        }
    }
}


Runer::init();

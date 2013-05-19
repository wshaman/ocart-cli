<?php
abstract class BasicCLIObject{
    protected  $registry;
    protected  $loader;
    protected  $config;
    protected  $db;
    protected  $db_prefix = DB_PREFIX;  //!There is no good reason to use this except of one - PHPStorm can't paint SQL with concatenations
    protected  $verbose_level = 0;

    public function __get($key) {
        return $this->registry->get($key);
    }

    public function __set($key, $value) {
        $this->registry->set($key, $value);
    }

    public function __construct($verbose=0){
        $this->setVerbose($verbose);
        $this->registry = new Registry();
        $this->loader = new Loader($this->registry);
        $this->registry->set('load', $this->loader);
        $this->config = new Config();
        $this->registry->set('config', $this->config);
        $this->db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $this->registry->set('db', $this->db);
    }

    public function  setVerbose($v){
        $this->verbose_level = $v;
    }

    protected function log($msg){
        switch ($this->verbose_level){
            case 1: echo $msg; break;
            case 3: debug_print_backtrace();
            case 2: var_dump($msg); break;
            default: break;
        }
    }
}
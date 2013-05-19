<?php
class CLIProcessor{

    private $args = array();
    private $errs = array(
        'NOPARAM'=>1,
        'WRONGPARAM'=>2,
        'NOCLASSFILE'=>3,
        'NOMETHOD'=>4,
        'WRONGVALUE'=>5,
    );

    private $params = array(
        '--as-admin'=>array('msg'=>'Load admin config file and set load dirs to admin'),
        '--as-catalog'=>array('msg'=>'Load catalog config file and set load dirs to admin'),
        '-h'=>array('msg'=>'Show this help text and exit', 'next'=>False),
        '-v'=>array('msg'=>'Sets verbose level to{1,2,3}', 'next'=>1)
    );

    private $verbose = 0;

    function __construct($arguments){
        $this->args = $arguments;
        array_shift($this->args);
    }

    private function show_error($err, $msg=Null, $do_exit=True){
        switch($err){
            case $this->errs['NOPARAM']: $do_exit=False; $this->show_help(); break;
            case $this->errs['WRONGPARAM']: echo "WRONG OPERATION GIVEN: ".$msg; break;
            case $this->errs['NOCLASSFILE']: echo "NO CLASS FILE FOUND: ".$msg; break;
            case $this->errs['NOMETHOD']: echo "NO CALLABLE METHOD FOUND:".$msg; break;
            case $this->errs['WRONGVALUE']: echo 'WRONG VALUE '.$msg."\n"; $this->show_help(); break;
        }
        if($do_exit){
            exit($err);
        }
    }

    private function show_help(){
        echo "\n\nAllows to call CLI commands\nOwn parameters:\n";
        foreach ($this->params as $k => $_) {
            echo $k.' '.$_['msg']."\n";
        }
        exit(0);
    }

    private function  __processParams(){
        $a_unset = array();
        foreach($this->args as $k => $_){
            if(in_array($_, array_keys($this->params))){
                switch($_){
                    case '-v' :
                        if(in_array(F::extract_numbers($this->args[$k+1], True), array(0,1,2,3), True)){
                            $this->verbose = $this->args[$k+1];
                            $a_unset[] = $k;
                            $a_unset[] = $k+1;
                        } else {
                            $this->show_error($this->errs['WRONGVALUE'], $_.':'.$this->args[$k+1]);
                        }
                        break;
                    case '-h': $this->show_help(); break;
                }
            }
        }
        foreach($a_unset as $_){
            unset($this->args[$_]);
        }
    }

    public function process(){
        if(count($this->args)<1){
            $this->show_error($this->errs['NOPARAM']);
        }
        $this->__processParams();
        $args = $this->args;
        $operation_full = array_shift($args);
        $operation = explode('_', $operation_full);
        if (count($operation)<2){
            $this->show_error($this->errs['WRONGPARAM'], $operation);
        }
        // __autoload ?
        $classname = ucfirst($operation[0]).ucfirst($operation[1]);
        $method_name = 'cli_'.$operation[2];
//        require_once($file_require);
        $obj = new $classname();
        $obj->setVerbose($this->verbose);

        if(!method_exists($obj, $method_name)){
            $this->show_error($this->errs['NOMETHOD']);
        }
        call_user_func_array(array($obj, $method_name), $args);
    }
}

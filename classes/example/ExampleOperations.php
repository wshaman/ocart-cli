<?php

class ExampleOperations extends BasicCLIObject{

    public function cli_currencies(){
        $this->loader->model('example/operations');
        var_dump($this->model_example_operations->getCurrencies());
    }
}
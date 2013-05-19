<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rakoth
 * Date: 5/17/13
 * Time: 9:23 PM
 * To change this template use File | Settings | File Templates.
 */


$conf_path = __DIR__.'/../cli/config.php';
//load as admin!
foreach($argv as $k => $_){
    if($_ =='--as-admin'){
        unset($argv[$k]);
        $argc--;
        $conf_path = __DIR__.'/../admin/config.php';
    }
    if($_ =='--as-catalog'){
        unset($argv[$k]);
        $argc--;
        $conf_path = __DIR__.'/../config.php';
    }
}


// Configuration
require_once($conf_path);

// Install
if (!defined('DIR_APPLICATION')) {
    die('OPENCART installation required');
    exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');


function run_autoloader($class) {
    $root_dir = dirname(__FILE__);
    $run_classes = implode(DIRECTORY_SEPARATOR, array($root_dir,'includes',$class.'.php'));
    if(is_file($run_classes)){
        include_once($run_classes);
        return;
    }
    preg_match_all('/[A-Z][^A-Z]*/', $class, $class_parts);
    $cli_classes = implode(DIRECTORY_SEPARATOR, array($root_dir, 'classes', strtolower($class_parts[0][0]), $class.'.php'));
    if(is_file($cli_classes)){
        include_once($cli_classes);
    }
}

spl_autoload_register('run_autoloader');

$p = new CLIProcessor($argv);
$p->process();



<?php

/*
 * db_setup.php: Sets up database access, required by all scripts that access the db
 */

require_once 'vendor/autoload.php';

//use Propel\Runtime\Propel;
//use Propel\Runtime\Connection\ConnectionManagerSingle;

//$serviceContainer = Propel::getServiceContainer();
//$serviceContainer->setAdapterClass('ioankabi_eam', 'mysql');
//$manager = new ConnectionManagerSingle();
//$manager->setConfiguration(array(
//    'dsn' => 'mysql:host=localhost;dbname=ioankabi_eam',
//    'user' => 'root',
//    'password' => 'ph4rm4s1t3',
//));
//$serviceContainer->setConnectionManager('ioankabi_eam', $manager);

//$conn = Propel::getConnection("ioankabi_eam");
//$sql = "SET NAMES utf8";
//$st = $conn->prepare($sql);
//$st->execute();
//$sql = "SET CHARACTER SET utf8";
//$st = $conn->prepare($sql);
//$st->execute();

require_once('generated-conf/config.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('defaultLogger');
//$logger->pushHandler(new StreamHandler('php://stderr'));
$logger->pushHandler(new \Monolog\Handler\RotatingFileHandler("log/log.txt"));
\Propel\Runtime\Propel::getServiceContainer()->setLogger('defaultLogger', $logger);

//$log = \Propel\Runtime\Propel::getServiceContainer()->getLogger('defaultLogger');
//$log->addInfo('This is a message');

$con = \Propel\Runtime\Propel::getWriteConnection('ioankabi_eam');
$con->useDebug(true);

?>

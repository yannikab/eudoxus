
<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('ioankabi_eam', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=ioankabi_eam',
  'user' => '',
  'password' => '',
  'settings' =>
  array (
    'charset' => 'utf8',
    'queries' =>
    array (
      'query' =>
      array (
        0 => 'SET NAMES utf8',
        1 => 'SET CHARACTER SET utf8',
      ),
    ),
  ),
));
$manager->setName('ioankabi_eam');
$serviceContainer->setConnectionManager('ioankabi_eam', $manager);
$serviceContainer->setDefaultDatasource('ioankabi_eam');

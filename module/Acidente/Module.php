<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Acidente;

use Acidente\Model\Acidente;
use Acidente\Model\AcidenteTable;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

 public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Acidente\Model\AcidenteTable' =>  function($sm) {
                    $tableGateway = $sm->get('AcidenteTableGateway');
                    $table = new AcidenteTable($tableGateway);
                    return $table;
                },
                'AcidenteTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Acidente());
                    return new TableGateway('acidente', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}

<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Missao;

use Missao\Model\Missao;
use Missao\Model\MissaoTable;

use Missao\Model\Recurso;
use Missao\Model\RecursoTable;



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
                'Missao\Model\MissaoTable' =>  function($sm) {
                    $tableGateway = $sm->get('MissaoTableGateway');
                    $table = new MissaoTable($tableGateway);
                    return $table;
                },
                'MissaoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Missao());
                    return new TableGateway('missao', $dbAdapter, null, $resultSetPrototype);
                },

                'Missao\Model\RecursoTable' =>  function($sm) {
                    $tableGateway = $sm->get('RecursoTableGateway');
                    $table = new RecursoTable($tableGateway);
                    return $table;
                },
                'RecursoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Recurso());
                    return new TableGateway('recurso', $dbAdapter, null, $resultSetPrototype);
                },

            ),
        );
    }
}

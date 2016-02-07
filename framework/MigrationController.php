<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 12/28/15
 * Time: 3:53 PM
 */
namespace hipersia\framework;

use hipersia\Base as base;

class MigrationController extends Controller
{
    protected function migrate($entityName) {
        $mapper = base::getDbLocator()->getMapper();
        $mapper->migrate();
        echo 'OK!';
    }

}
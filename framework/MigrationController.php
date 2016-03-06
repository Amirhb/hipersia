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
        $locator = base::getDbLocator();
        $mapper = $locator->mapper($entityName);
        $mapper->migrate();
        echo 'OK!';
    }
}
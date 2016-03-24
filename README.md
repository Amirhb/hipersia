# Hipersia
Hipersia is a Micro-MVC PHP-Framework. It's been developed as a technical research as journey for me to get a better idea of MVC Web-frameworks. Hipersia is completely functional as a framework and as an example, I developed a simple [Shoutbox app](https://github.com/Amirhb/hipersia-sample-shoutbox "Shoutbox Web-App by Hipersia Micro-MVC PHP-Framework") using it.
## Installation
### Composer
You could use [Composer](https://getcomposer.org/download/ "Composer") to install Hipersia and all needed dependencies.
```
{
  "require": {
    "amirhb/hipersia": "dev-master"
  },
...
```
## Configuration
### Database
The config folder of the project's root is the only hard-coded path you would need in your project due to database settings. There should be a config.yml file in the config folder of the project's root. You should customize it based your own environment.
```
dsn: mysql://username:password@localhost/databasename
name: mysql
```
Data-Mapping is supported by [Spot ORM](http://phpdatamapper.com "Spot ORM") and you can change dsn and name properties to support other databases which get supported by Spot ORM.
## Models
Each model-file is a php class which inherited from \Spot\Entity .
```
namespace app\models;

class Message extends \Spot\Entity
{
    protected static $table = 'messages';
    public static function fields()
    {
        return [
            'id'           => ['type' => 'integer', 'primary' => true, 'autoincrement' => true],
            'author'       => ['type' => 'string', 'required' => true],
            'message'      => ['type' => 'text', 'required' => true],
            'date_created' => ['type' => 'datetime', 'value' => new \DateTime()]
        ];
    }
}
```
For more information on how to develop a Spot Entity, visit [here](http://phpdatamapper.com/docs/entities/ "Working With Entities").
## Migration
You can run migration to create tables based on your models. Your migration class has to inherit MigrationController of Hipersia like the following example:
```
namespace app\controllers;

use hipersia\framework\MigrationController as Migration;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MigrationController extends Migration {

    public function index( Request $request, Response $response) {

        $this->migrate('app\models\Message');

        return $response;
    }
}
```

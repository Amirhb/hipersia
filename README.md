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
## Controllers
Each controller inherits hipersia\framework\Controller and it uses Response and Request object of HttpFoundation.
```
namespace app\controllers;

use hipersia\Base as base;
use hipersia\framework\Controller as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use hipersia\framework\AssetBundle;

class DefaultController extends Controller {

    public function index( Request $request, Response $response, $args) {

        echo 'You have called ' . $args['uri'];

        return $response;
    }
}
```
### Routing
As you see in the sample [Shoutbox app](https://github.com/Amirhb/hipersia-sample-shoutbox "Shoutbox Web-App by Hipersia Micro-MVC PHP-Framework"), you can define new routes. It's based on php league [Route](http://route.thephpleague.com "Route") package. You have to call something like the following when your app starts.
```
use Symfony\Component\HttpFoundation\Request;

$router = new League\Route\RouteCollection;

$router->addRoute('GET', '/migrate', 'app\controllers\MigrationController::index');
$router->addRoute('GET', '/welcome', 'app\controllers\DefaultController::welcome');
$router->addRoute('GET', '/', 'app\controllers\DefaultController::shoutBox');
$router->addRoute('POST', '/', 'app\controllers\DefaultController::shoutBox');
$router->addRoute('GET', '/{uri}', 'app\controllers\DefaultController::index');

$dispatcher = $router->getDispatcher();

$request = Request::createFromGlobals();
$response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

$response->send();
```
### Working with Models in your Controller
To access Spot's mapper. You should use base methods of Hipersia framework as follows:
```
$locator = base::getDbLocator();
$mapper = $locator->mapper('app\models\Message');
```
For more information on database queries provided by mappers, visit [here](http://phpdatamapper.com/docs/queries "Queries With Spot").
```
namespace app\controllers;

use hipersia\Base as base;
use hipersia\framework\Controller as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use hipersia\framework\AssetBundle;

class DefaultController extends Controller {

    public function shoutBox( Request $request, Response $response) {

        $locator = base::getDbLocator();
        $mapper = $locator->mapper('app\models\Message');

        if(!empty($_POST)) {
            $message = $mapper->insert([
                'author' => $_POST['author'],
                'message' => $_POST['message']
            ]);
        }

        $messages = $mapper->all();

        AssetBundle::registerCss('bootstrap', __DIR__ . '/../views/css/bootstrap.css');

        return $this->render('shoutbox', ['messages' => $messages]);
    }
}
```
## Assets
Hipersia provides the AssetBundle class to manage assets like css and javascript files. You can use the following syntax to add your asset to be used in the related view.
For Css files:
```
AssetBundle::registerCss($name, $source);
```
For Javascript files:
```
AssetBundle::registerJs($name, $source);
```
$name is the final name that you want to use for your script and $source is the physical address of the file.
## View
For the view, Hipersia uses [Twig](http://twig.sensiolabs.org/ "Twig") template engine. View files are located in View folder in project's root by default.
To render a view, you should use the render method that Hipersia provides like the following:
```
return $this->render($view, $data);
```
$view is the view file's name without it's extension and $data is an array which contains variables which is being passed to the view.
For more information how to create twig templates, visit [Twig Documentation](http://twig.sensiolabs.org/documentation "Twig Documentation").
### Using Assets in Views
To register css and javascript files, like we pointed earlier in this guide, you can use the AssetBundle class before rendering the view.
```
AssetBundle::registerCss('bootstrap', __DIR__ . '/../views/css/bootstrap.css');
```
And to generate related html tags for using assets, you should add the following to the appropriate view template file:
```
<!DOCTYPE html>
<html>
<head>
    <title>Shoutbox Web-App by Hipersia Micro-MVC PHP-Framework</title>
    {{ assets.renderAssets|raw }}
</head>
...
```
### Csrf Support
Hipersia forces csrf-protection for POST requests. You have to add the following field in your twig template when POST-ing a form:
```
<input type="hidden" name="hipersia_csrf" value="{{ hipersia_csrf }}">
```
Hipersia is in charge of ensuring that if you've sent the correct value. There's no need for you to add any code to check it.

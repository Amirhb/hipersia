<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 12/26/15
 * Time: 12:03 PM
 */

namespace hipersia\framework;

use \hipersia\Base as base;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller {

    private static $viewsFolder = 'views';
    public $layout = "/layout/default";

    public function __construct() {
        // check csrf
        Sec::csrfCheck();

        $this->beforeAction();
    }

    protected function beforeAction() {

    }

    public function __call($method, $args) {
        echo "The called action does not exist! ";

        return $args[1]; // Returning the Response Object.
    }

    public function render( $view, $data = [], $responseCode = 200) {
        $views_path = realpath(base::getBasePath() . DIRECTORY_SEPARATOR . self::$viewsFolder . DIRECTORY_SEPARATOR);
        $loader = new \Twig_Loader_Filesystem( $views_path);
        $twig = new \Twig_Environment($loader, ['debug' => true]);

        $data['assets'] = new AssetBundle;
        $data['hipersia_csrf'] = Sec::getCsrf();

        $content = $twig->render( $view . '.html', $data);

        $response = new Response();

        $response->setContent($content);
        $response->setStatusCode($responseCode);

        return $response;
    }
}
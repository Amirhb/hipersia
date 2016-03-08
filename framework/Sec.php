<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 1/2/16
 * Time: 10:32 AM
 */

namespace hipersia\framework;

class Sec
{

    protected static $checked = false;

    public static function csrfCheck()
    {

        self::getCsrf();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['hipersia_csrf'])) {
                if ($_POST['hipersia_csrf'] != $_SESSION[ 'hipersia_csrf' ]) {
                    throw new \Exception("Csrf Error!", 403);
                }
            }
            else {
                throw new \Exception("Csrf Error!", 403);
            }
        }
    }

    public static function getCsrf()
    {

        if (!isset($_SESSION[ 'hipersia_csrf' ])) {
            $token = base64_encode( time() . self::randomString( 32 ) );
            $_SESSION[ 'hipersia_csrf' ] = $token;
        }

        return $_SESSION[ 'hipersia_csrf' ];
    }

    protected static function randomString( $length )
    {
        // with a little help from https://github.com/BKcore/NoCSRF
        $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijqlmnopqrtsuvwxyz0123456789';
        $max = strlen( $seed ) - 1;
        $string = '';
        for ( $i = 0; $i < $length; ++$i )
            $string .= $seed{intval( mt_rand( 0.0, $max ) )};
        return $string;
    }
}
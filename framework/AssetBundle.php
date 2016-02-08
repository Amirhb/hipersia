<?php
/**
 * Created by PhpStorm.
 * User: Amir Hossein Babaeian
 * Date: 12/27/15
 * Time: 2:49 PM
 */

namespace hipersia\framework;

use \hipersia\Base as base;

class AssetBundle {

    private static $assetsFolder = 'assets';
    public static $assets = [];

    private static function register($assetType, $assetName, $assetFile){
        $assets_path = realpath(base::getBasePath() . DIRECTORY_SEPARATOR . self::$assetsFolder);

        if (!file_exists($assets_path)) {
            mkdir($assets_path, 0755, true);
        }

        if(copy($assetFile, $assets_path . DIRECTORY_SEPARATOR . $assetName . '.' . $assetType)) {
            self::$assets[$assetType][] =  self::$assetsFolder . DIRECTORY_SEPARATOR . $assetName . '.' . $assetType;
        }
    }

    public static function registerJs($assetName, $assetFile) {
        self::register($assetType='js', $assetName, $assetFile);
    }

    public static function registerCss($assetName, $assetFile) {
        self::register($assetType='css', $assetName, $assetFile);
    }

    public static function renderAssets()
    {
        $assets = self::$assets;
        $result = "";

        foreach ($assets as $key => $asset) {
            if ($key == 'js') {
                foreach ($asset as $js) {
                    $result .= <<<JS
    <script src='$js' language="javascript" type="text/javascript"></script>

JS;
                }

            } elseif ($key == 'css') {
                foreach ($asset as $css) {
                    $result .= <<<CSS
    <link rel="stylesheet" type="text/css" href="$css" />

CSS;
                }
            }
        }
        return $result;
    }


}
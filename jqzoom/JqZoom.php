<?php
/**
 * User: yiqing
 * Date: 12-7-20
 * Time: 下午11:40
 * To change this template use File | Settings | File Templates.
 *------------------------------------------------------------
 * @see http://www.mind-projects.it/projects/jqzoom/index.php#installation
 *------------------------------------------------------------
 */
class JqZoom  extends CWidget
{


    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var bool
     */
    public $debug = YII_DEBUG;

    /**
     * @var \CClientScript
     */
    protected $cs;

    /**
     * @var array|string
     * -------------------------
     * the options will be passed to the underlying plugin
     *   eg:  js:{key:val,k2:v2...}
     *   array('key'=>$val,'k'=>v2);
     * -------------------------
     */
    public $options = array();


    /**
     * @var array
     */
    public $defaultOptions = array (
        'zoomType' => 'standard',
        'lens' => true,
        'preloadImages' => false,
        'alwaysOn' => false,
    );


    /**
     * @var string
     * css selector which will apply this plugin effect
     */
    public $selector ;

    /**
     * @return JqZoom
     */
    public function publishAssets()
    {
        if (empty($this->baseUrl)) {
            $assetsPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
            if ($this->debug == true) {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            } else {
                $this->baseUrl = Yii::app()->assetManager->publish($assetsPath);
            }
        }
        return $this;
    }


    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->cs = Yii::app()->getClientScript();

        $this->cs->registerCoreScript('jquery');
        // publish assets and register css/js files
        $this->publishAssets();

        if ($this->debug) {
            $this->registerScriptFile('js/jquery.jqzoom-core.js', CClientScript::POS_HEAD);
        } else {
            // no the min file now !
            $this->registerScriptFile('js/jquery.jqzoom-core-pack.js', CClientScript::POS_HEAD);
        }
        $this->registerCssFile('css/jquery.jqzoom.css');

        if(empty($this->selector)) return ;

        //> encode it for initializing the current jquery  plugin
       // $options = empty($this->options) ? '' : CJavaScript::encode($this->options);
        $options =  CJavaScript::encode(CMap::mergeArray($this->defaultOptions,$this->options));

        $jsCode = '';

        //>  the js code for setup
        $jsCode .= <<<SETUP
        jQuery('{$this->selector}').jqzoom({$options});
SETUP;

        //> register jsCode
        $this->cs->registerScript(__CLASS__ . '#' . $this->getId(), $jsCode, CClientScript::POS_READY);

    }


    /**
     * @param string $name
     * @param mixed $value
     * @return mixed|void
     */
    public function __set($name, $value)
    {
        try {
            //shouldn't swallow the parent ' __set operation
            parent::__set($name, $value);
        } catch (Exception $e) {
            $this->options[$name] = $value;
        }
    }

    /**
     * @param $fileName
     * @param int $position
     * @return JqZoom
     * @throws InvalidArgumentException
     */
    protected function registerScriptFile($fileName, $position = CClientScript::POS_END)
    {
        if (is_string($fileName)) {
            $jsFiles = explode(',', $fileName);
        } elseif (is_array($fileName)) {
            $jsFiles = $fileName;
        } else {
            throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($fileName, true));
        }
        foreach ($jsFiles as $jsFile) {
            $jsFile = trim($jsFile);
            $this->cs->registerScriptFile($this->baseUrl . '/' . ltrim($jsFile, '/'), $position);
        }
        return $this;
    }

    /**
     * @param $fileName
     * @return JqZoom
     * @throws InvalidArgumentException
     */
    protected function registerCssFile($fileName)
    {
        $cssFiles = func_get_args();
        foreach ($cssFiles as $cssFile) {
            if (is_string($cssFile)) {
                $cssFiles2 = explode(',', $cssFile);
            } elseif (is_array($cssFile)) {
                $cssFiles2 = $cssFile;
            } else {
                throw new InvalidArgumentException('you must give a string or array as first argument , but now you give' . var_export($cssFiles, true));
            }
            foreach ($cssFiles2 as $css) {
                $this->cs->registerCssFile($this->baseUrl . '/' . ltrim($css, '/'));
            }
        }
        // $this->cs->registerCssFile($this->assetsUrl . '/vendors/' .$fileName);
        return $this;
    }

    /**
     * @static
     * @param bool $hashByName
     * @return string
     * return this widget assetsUrl
     */
    public static function getAssetsUrl($hashByName = false)
    {
        // return CHtml::asset(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName);
        return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets', $hashByName, -1, YII_DEBUG);
    }
}
<?php
/**
 * Smarty renderer for Yii
 *
 * Copy latest version of Smarty (libs contents) to vendors/Smarty/.
 *
 * Add the following to your config file 'components' section:
 *
 * 'viewRenderer'=>array(
 *     'class'=>'application.extensions.Smarty.CSmartyViewRenderer',
 *     'fileExtension' => '.tpl',
 *     //'pluginsDir' => 'application.smartyPlugins',
 *     //'configDir' => 'application.smartyConfig',
 *  ),
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @link http://www.yiiframework.com/
 * @link http://www.smarty.net/
 *
 * @version 0.9.5
 */
class ESmartyViewRenderer extends CApplicationComponent implements IViewRenderer {
    public $fileExtension='.tpl';
    public $filePermission=0755;
    public $pluginsDir = null;
    public $configDir = null;

    private $smarty;

    /**
     * Component initialization
     */
    function init(){
        // needed by Smarty 3
        define('SMARTY_SPL_AUTOLOAD', 1);

        Yii::import('application.vendors.*');
        require_once('Smarty/Smarty.class.php');

        $this->smarty = new Smarty();
        
        $this->smarty->template_dir = '';
        $compileDir = Yii::app()->getRuntimePath().'/smarty/compiled/';
        
        // create compiled directory if not exists
        if(!file_exists($compileDir)){
            mkdir($compileDir, $this->filePermission, true);
        }
                
        $this->smarty->compile_dir = $compileDir;
        
        
        $this->smarty->plugins_dir[] = Yii::getPathOfAlias('application.extensions.Smarty.plugins');
        if(!empty($this->pluginsDir)){
            $this->smarty->plugins_dir[] = Yii::getPathOfAlias($this->pluginsDir);
        }
        
        if(!empty($this->configDir)){
            $this->smarty->config_dir = Yii::getPathOfAlias($this->configDir);
        }
        
        $this->smarty->assign("TIME", sprintf('%0.5f',Yii::getLogger()->getExecutionTime()));
        $this->smarty->assign("MEMORY", round(Yii::getLogger()->getMemoryUsage()/(1024*1024),2)." MB");
    }

    /**
	 * Renders a view file.
	 * This method is required by {@link IViewRenderer}.
	 * @param CBaseController the controller or widget who is rendering the view file.
	 * @param string the view file path
	 * @param mixed the data to be passed to the view
	 * @param boolean whether the rendering result should be returned
	 * @return mixed the rendering result, or null if the rendering result is not needed.
	 */
	public function renderFile($context,$sourceFile,$data,$return) {
        // current controller properties will be accessible as {this.property}
        $data['this'] = $context;

        // check if view file exists
        if(!is_file($sourceFile) || ($file=realpath($sourceFile))===false)
            throw new CException(Yii::t('yiiext','View file "{file}" does not exist.', array('{file}'=>$sourceFile)));
            
        //assign data        
        foreach($data as $element => $value) {
            $this->smarty->assign($element, $value);
        }
        
        //render
        return $this->smarty->fetch($sourceFile);
	}
}

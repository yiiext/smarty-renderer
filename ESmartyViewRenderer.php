<?php
/**
 * Smarty view renderer
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @link http://code.google.com/p/yiiext/
 * @link http://www.smarty.net/
 *
 * @version 0.9.8
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
        Yii::import('application.vendors.*');

        // need this since Yii autoload handler raises an error if class is not found
        spl_autoload_unregister(array('YiiBase','autoload'));

        // including Smarty class and registering autoload handler
        require_once('Smarty/Smarty.class.php');

        // adding back Yii autoload handler
        spl_autoload_register(array('YiiBase','autoload'));

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
        $this->smarty->assign('Yii', Yii::app());
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
		$this->smarty->assign($data);

        //render or return
		if($return)
        	return $this->smarty->fetch($sourceFile);
		else
			$this->smarty->display($sourceFile);
	}
}

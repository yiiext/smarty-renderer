<?php
/**
 * Smarty view renderer
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @author Carsten Brandt <mail@cebe.cc>
 * @author Grigori Kochanov <public@grik.net>
 * @link http://yiiext.github.com/extensions/smarty-renderer/index.html
 * @link http://www.smarty.net/
 *
 * @version 1.0.2
 */
class ESmartyViewRenderer extends CApplicationComponent implements IViewRenderer
{
	/**
	 * @var string the file-extension for viewFiles this renderer should handle
	 * for smarty templates this usually is .tpl
	 */
	public $fileExtension='.tpl';

	/**
	 * @var int dir permissions for smarty compiled templates directory
	 */
	public $directoryPermission=0771;

	/**
	 * @var int file permissions for smarty compiled template files
	 * NOTE: BEHAVIOR CHANGED AFTER VERSION 0.9.8
	 */
	public $filePermission=0644;

	/**
	 * @var null|string yii alias of the directory where your smarty plugins are located
	 * application.extensions.Smarty.plugins is always added
	 */
	public $pluginsDir = null;

	/**
	 * A list of the prefilters to be attached
	 * 
	 * Elements are the callback identifiers (see call_user_func()).
	 * 
	 * If the filter is defined as a string, and the function is not defined,
	 * the file prefilter.[filtername].php is loaded with include()
	 * 
 	 * Callbacks defined as arrays, e.g. array('prefilterClass','foo')
	 * will utilize yii autoload routine to load filters for compilation only 
	 * 
	 * @var array 
	 */
	public $prefilters = array();
	
	/**
	 * List of postfilters to be registered
	 * @see $prefilters, replace 'prefilter' with 'postfilter' 
	 * @var array
	 */
	public $postfilters = array();
	
	/**
	 * @var null|string yii alias of the directory where your smarty template-configs are located
	 */
	public $configDir = null;

	/**
	 * @var array smarty configuration values
	 * this array is used to configure smarty at initialization you can set all
	 * public properties of the Smarty class e.g. error_reporting
	 *
	 * please note:
	 * compile_dir will be created if it does not exist, default is <app-runtime-path>/smarty/compiled/
	 *
	 * @since 0.9.9
	 */
	public $config = array();

	/**
	 * @var Smarty smarty instance for rendering
	 */
	protected $smarty = null;

	/**
	 * Component initialization
	 */
	public function init(){

		parent::init();

		Yii::import('application.vendors.*');

		// need this to avoid Smarty rely on spl autoload function,
		// this has to be done since we need the Yii autoload handler
		if (!defined('SMARTY_SPL_AUTOLOAD')) {
		    define('SMARTY_SPL_AUTOLOAD', 0);
		} elseif (SMARTY_SPL_AUTOLOAD !== 0) {
			throw new CException('ESmartyViewRenderer cannot work with SMARTY_SPL_AUTOLOAD enabled. Set SMARTY_SPL_AUTOLOAD to 0.');
		}

		// including Smarty class and registering autoload handler
		require_once('Smarty/sysplugins/smarty_internal_data.php');
		require_once('Smarty/Smarty.class.php');

		// need this since Yii autoload handler raises an error if class is not found
		// Yii autoloader needs to be the last in the autoload chain
		spl_autoload_unregister('smartyAutoload');
		Yii::registerAutoloader('smartyAutoload');

		$this->smarty = new Smarty();

		// configure smarty
		if (is_array($this->config)) {
			foreach ($this->config as $key => $value) {
				if ($key{0} != '_') { // not setting semi-private properties
					$this->smarty->$key = $value;
				}
			}
		}
		$this->smarty->_file_perms = $this->filePermission;
		$this->smarty->_dir_perms = $this->directoryPermission;

		$this->smarty->setTemplateDir(yii::app()->getViewPath());
		$compileDir = isset($this->config['compile_dir']) ?
					  $this->config['compile_dir'] : Yii::app()->getRuntimePath().'/smarty/compiled/';

		// create compiled directory if not exists
		if(!file_exists($compileDir)){
			mkdir($compileDir, $this->directoryPermission, true);
		}
		$this->smarty->setCompileDir($compileDir); // no check for trailing /, smarty does this for us


		$this->smarty->addPluginsDir(Yii::getPathOfAlias('ext.smarty.plugins'));
		if(!empty($this->pluginsDir)){
		    $plugin_path = Yii::getPathOfAlias($this->pluginsDir);
			$this->smarty->addPluginsDir($plugin_path);
		}

		if ($this->prefilters){
			foreach ($this->prefilters as $filter) {
			    $this->registerFilter('pre',$filter);
			}
		}
		
		if ($this->postfilters){
			foreach ($this->postfilters as $filter) {
			    $this->registerFilter('post',$filter);
			}
		}
		if(!empty($this->configDir)){
			$this->smarty->addConfigDir(Yii::getPathOfAlias($this->configDir));
		}
	}
	
	/**
	 * @return Smarty
	 */
	function getSmarty(){
	    return $this->smarty;
	}
	
	/**
	 * Add a pre or post filter defined in yii config
	 * 
	 * @param const $type
	 * @param callback $filter
	 */
	function registerFilter($type,$filter){
	    if (is_string($filter)){
		    if (!function_exists($filter)){
		        $filter_file = Yii::getPathOfAlias($this->pluginsDir).'/'.$type.'filter.'.$filter.'.php';
			    if (!file_exists($filter_file)){
			        throw new CException('Filter file '.$filter_file.' not found');
			    }
			    include $filter_file;
			    if (!function_exists($filter)){
			        throw new CException('Callback '.$filter.' was not found in the included file');
			    }
		    }
	    }
	    $this->smarty->registerFilter($type, $filter);
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
		// current controller properties will be accessible as {$this->property}
		$data['this'] = $context;
		// Yii::app()->... is available as {Yii->...} (deprecated, use {Yii::app()->...} instead, Smarty3 supports this.)
		$data['Yii'] = Yii::app();
		// time and memory information
		$data['TIME'] = sprintf('%0.5f',Yii::getLogger()->getExecutionTime());
		$data['MEMORY'] = round(Yii::getLogger()->getMemoryUsage()/(1024*1024),2).' MB';

		// check if view file exists
		if(!is_file($sourceFile) || ($file=realpath($sourceFile))===false)
			throw new CException(Yii::t('yiiext','View file "{file}" does not exist.', array('{file}'=>$sourceFile)));

		$template = $this->smarty->createTemplate($sourceFile, null, null, $data, true);
		/* @var $template Smarty_Internal_Template */
		
		//render or return
		if($return)
			return $template->fetch();
		else
			$template->display();
	}

	/**
	 * removes all files from compile dir
	 */
	public function clearCompileDir()
	{
		$this->smarty->clearCompiledTemplate();
	}
}
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
 * @version 1.0.5
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
	 * ext.Smarty.plugins is always added
	 */
	public $pluginsDir;

	/**
	 * @var string path alias of the directory where the Smarty.class.php file can be found.
	 * Also plugins and sysplugins directory should be there.
	 */
	public $smartyDir = 'application.vendors.Smarty';

	/**
	 * @var array A list of the prefilters to be attached
	 * @since 1.0.2
	 *
	 * Elements are the callback identifiers (see call_user_func()).
	 *
	 * If the filter is defined as a string, and the function is not defined,
	 * the file prefilter.[filtername].php is loaded with include()
	 *
 	 * Callbacks defined as arrays, e.g. array('prefilterClass','foo')
	 * will utilize yii autoload routine to load filters for compilation only
	 */
	public $prefilters = array();

	/**
	 * @var array List of postfilters to be registered
	 * @see $prefilters, replace 'prefilter' with 'postfilter'
	 * @since 1.0.2
	 */
	public $postfilters = array();

	/**
	 * @var array A list of the functions to be registered
	 * @since 1.0.4
	 *
	 * Element keys are the function names, values are the callback identifiers (see call_user_func()).
	 */
	public $functions = array();

	/**
	 * @var array List of modifiers to be registered
	 * @see $functions, replace 'function' with 'modifier'
	 * @since 1.0.4
	 */
	public $modifiers = array();

	/**
	 * @var null|string yii alias of the directory where your smarty template-configs are located
	 */
	public $configDir;

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
	private $_smarty;

	/**
	 * @since 1.0.2
	 * @return Smarty
	 */
	public function getSmarty()
	{
		if ($this->_smarty === null) {
			$this->_smarty = new Smarty();
		}
	    return $this->_smarty;
	}

	/**
	 * Component initialization
	 * @throws CException
	 */
	public function init(){

		parent::init();

		// adding Smarty library directory to include path
		Yii::import($this->smartyDir.'.*');

		// need this to avoid Smarty rely on spl autoload function,
		// this has to be done since we need the Yii autoload handler
		if (!defined('SMARTY_SPL_AUTOLOAD')) {
		    define('SMARTY_SPL_AUTOLOAD', 0);
		} elseif (SMARTY_SPL_AUTOLOAD !== 0) {
			throw new CException('ESmartyViewRenderer cannot work with SMARTY_SPL_AUTOLOAD enabled. Set SMARTY_SPL_AUTOLOAD to 0.');
		}

		// including Smarty class and registering autoload handler
		require_once('sysplugins/smarty_internal_data.php');
		require_once('Smarty.class.php');

		// need this since Yii autoload handler raises an error if class is not found
		// Yii autoloader needs to be the last in the autoload chain
		spl_autoload_unregister('smartyAutoload');
		Yii::registerAutoloader('smartyAutoload');

		// configure smarty
		if (is_array($this->config)) {
			foreach ($this->config as $key => $value) {
				if ($key{0} != '_') { // not setting semi-private properties
					$this->getSmarty()->$key = $value;
				}
			}
		}
		$this->getSmarty()->_file_perms = $this->filePermission;
		$this->getSmarty()->_dir_perms = $this->directoryPermission;

		$this->getSmarty()->setTemplateDir(Yii::app()->getViewPath());
		$compileDir = isset($this->config['compile_dir']) ?
					  $this->config['compile_dir'] : Yii::app()->getRuntimePath().'/smarty/compiled/';

		// create compiled directory if not exists
		if(!file_exists($compileDir)){
			mkdir($compileDir, $this->directoryPermission, true);
		}
		$this->getSmarty()->setCompileDir($compileDir); // no check for trailing /, smarty does this for us

		//Register default template handler. This allow us to use yii aliases in the smarty templates.
		//You shoud set path without extension
		//for example {include file="application.views.layout.main"}
		$this->getSmarty()->default_template_handler_func = create_function('$type, $name', 'return Yii::getPathOfAlias($name) . "' . $this->fileExtension .'";'); 
 
		$this->getSmarty()->addPluginsDir(Yii::getPathOfAlias($this->smartyDir.'.plugins'));
		if(!empty($this->pluginsDir)){
		    $plugin_path = Yii::getPathOfAlias($this->pluginsDir);
			$this->getSmarty()->addPluginsDir($plugin_path);
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

		if ($this->functions){
			foreach ($this->functions as $name => $plugin) {
			    $this->getSmarty()->registerPlugin('function',$name,$plugin);
			}
		}

		if ($this->modifiers){
			foreach ($this->modifiers as $name => $plugin) {
			    $this->getSmarty()->registerPlugin('modifier',$name,$plugin);
			}
		}

		if(!empty($this->configDir)){
			$this->getSmarty()->addConfigDir(Yii::getPathOfAlias($this->configDir));
		}
	}

	/**
	 * Add a pre or post filter defined in yii config
	 *
	 * @since 1.0.2
	 * @param string $type
	 * @param callback $filter
	 * @throws CException
	 */
	public function registerFilter($type,$filter){
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
	    $this->getSmarty()->registerFilter($type, $filter);
	}

	/**
	 * Renders a view file.
	 *
	 * This method is required by {@link IViewRenderer}.
	 *
	 * @param CBaseController $context the controller or widget who is rendering the view file.
	 * @param string $sourceFile the view file path
	 * @param mixed $data the data to be passed to the view
	 * @param boolean $return whether the rendering result should be returned
	 * @return mixed the rendering result, or null if the rendering result is not needed.
	 * @throws CException
	 */
	public function renderFile($context,$sourceFile,$data,$return)
	{
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

		/** @var Smarty_Internal_Template $template */
		$template = $this->getSmarty()->createTemplate($sourceFile, null, null, $data, true);

		// render or return
		if($return)
			return $template->fetch();
		else
			$template->display();
	}

	/**
	 * removes all files from compile dir
	 * @since 1.0.1
	 */
	public function clearCompileDir()
	{
		$this->getSmarty()->clearCompiledTemplate();
	}
}
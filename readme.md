Smarty view renderer
====================

This extension allows you to use [Smarty](http://www.smarty.net/) version 3 templates in Yii.

###Resources
* [Github](https://github.com/yiiext/smarty-renderer)
* [Smarty](http://www.smarty.net/)
* [Discuss](http://www.yiiframework.com/forum/index.php?/topic/4925-smarty-view-renderer/)
* [Report a bug](https://github.com/yiiext/smarty-renderer/issues)

###Requirements
* Yii 1.0 or above
* Smarty 3.0.6 or above

###Installation
* Extract the release file under `protected/extensions`.
* [Download](http://www.smarty.net/download.php) and extract `libs` folder contents of Smarty package under `protected/vendors/Smarty`.
* Add the following to your config file 'components' section:

~~~
[php]
'viewRenderer'=>array(
  'class'=>'application.extensions.yiiext.renderers.smarty.ESmartyViewRenderer',
    'fileExtension' => '.tpl',
    //'pluginsDir' => 'application.smartyPlugins',
    //'configDir' => 'application.smartyConfig',
    //'prefilters' => array(array('MyClass','filterMethod')),
    //'postfilters' => array(),
    //'config'=>array(
    //    'force_compile' => YII_DEBUG,
    //   ... any Smarty object parameter
    //)
),
~~~
There are some more options on configuring Smarty properties now. Will add documentation soon.

###Usage
* [Smarty documentation](http://www.smarty.net/docs.php).
* Current controller properties are accessible via {$this->pageTitle}.
* Yii properties are available as follows: {$Yii->theme->baseUrl}.
* Used memory is stored in {$MEMORY}, used time is in {$TIME}.

CSmartyViewRenderer
===================

This extension allows you to use [Smarty](http://www.smarty.net/) templates in Yii.

###Resources
* [SVN](http://code.google.com/p/yiiext/source/browse/#svn/trunk/app/extensions/CSmartyViewRenderer)
* [Smarty](http://www.smarty.net/)
* [Discuss](http://www.yiiframework.com/forum/index.php?/topic/4925-smarty-view-renderer/)
* [Report a bug](http://code.google.com/p/yiiext/issues/list)

###Requirements
* Yii 1.0 or above

###Installation
* Extract the release file under `protected/extensions`.
* [Download](http://www.smarty.net/download.php) and extract libs folder contents of Smarty package under `protected/vendors/Smarty`.
* Add the following to your config file 'components' section:
~~~
[php]
'viewRenderer'=>array(
  'class'=>'application.extensions.CSmartyViewRenderer.CSmartyViewRenderer',
    'fileExtension' => '.tpl',
    //'pluginsDir' => 'application.smartyPlugins',
    //'configDir' => 'application.smartyConfig',
),
~~~

###Usage
* [Smarty documentation](http://www.smarty.net/docs.php).
* Current controller properties are accessible via {$this->pageTitle}.

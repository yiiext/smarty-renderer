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
* Move files from `plugins` folder to `protected/vendors/Smarty/plugins`.
* Add the following to your config file 'components' section:

~~~php
<?php
// ...
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
* Current controller properties are accessible via `{$this->pageTitle}`.
* Yii properties are available as follows: `{Yii::app()->theme->baseUrl}`.
* Used memory is stored in `{$MEMORY}`, used time is in `{$TIME}`.

###Smarty Plugins
* You can use Yii widgets like this: 
~~~
  {widget name="path.to.widget.Class"} {*render widget without params*}
  
  {widget name="Breadcrumbs" links=['Library'=>'#', 'Data'] someParam="someValue"} {*You can set params by passing it in the widget-function*}
  
  {*Also you can use another widget syntax:*}
  {begin_widget name="bootstrap.widgets.TbModal" id='anotherModalDialog' options=[backdrop=>static] otherParam="value" [...]}
		{*Widget object are accessible via {$widget} variable inside the block*}
        {$widget->some_widget_method_or_variable} 
  {/begin_widget} 
~~~

* Syntax-sugar plugin for Yii ActiveForm
Syntax:
~~~
   {form name="product_form" id='form' type='horizontal' otherParam="value" [...]}
		{*Form object are accessible via variable with name equal to form name*}
        {$product_form->textFieldRow($this->model, 'name', ['class'=>'span5','maxlength'=>255])}
   {/form} 
~~~

* 't()' function allows to translate strings using Yii::t().
Syntax:
~~~
  {t text="text to translate" cat="app"}
  {t text="text to translate" cat="app" src="en" lang="ru"}
  {t text="text to translate" cat="app" params=$params}
~~~

* Allows to generate links using CHtml::link().
 Syntax:
 * Syntax:
 * {link text="test"}
 * {link text="test" url="controller/action?param=value"}
 * {link text="test" url="/absolute/url"}
 * {link text="test" url="http://host/absolute/url"}
Шаблонизатор Smarty для Yii
===========================

Данное расширение позволяет использовать [Smarty](http://www.smarty.net/) версии 3 в приложениях Yii.

###Полезные ссылки
* [Github](https://github.com/yiiext/smarty-renderer)
* [Smarty](http://www.smarty.net/)
* [Обсуждение](http://yiiframework.ru/forum/viewtopic.php?f=9&t=241)
* [Сообщить об ошибке](https://github.com/yiiext/smarty-renderer/issues)

###Требования
* Yii 1.0 и выше
* Smarty 3.0.6 и выше

###Установка
* Распаковать в `protected/extensions`.
* [Скачать](http://www.smarty.net/download.php) и распаковать содержимое директории
  `libs` в `protected/vendors/Smarty`.
* Добавить в конфигурацю в секцию 'components':

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
),
~~~
There are some more options on configuring Smarty properties now. Will add documentation soon.

###Использование
* [Документация Smarty](http://www.smarty.net/docs.php).
* Свойства текущего контроллера доступны как `{$this->pageTitle}`.
* Для подключения других отображений внутри шаблона Smarty можно использовать Yii алиасы. Пример: 
~~~ smarty
  Алиас необходимо указывать БЕЗ расширения
  {extends file="application.views.layout.main"} 
	{include file="application.views.controller._form"}
	
  Так же можно использовать обычный способ подключения файлов,
  При таком способе путь строится относительно текущей папки c шаблонами (по умолчанию protected/views)
	{extends file="layout/main.tpl"}
	{include file="controller/_form.tpl"}
~~~
Алиасы очень удобны если в вашем приложении используются модульный подоход и 
вам во вложенном модуле нужно подключить отображения из родительского приложения или модуля.

* Свойства Yii доступны как `{Yii::app()->theme->baseUrl}`.
* Использованную память можно вывести как `{$MEMORY}`, затраченное время как `{$TIME}`.

###Smarty Plugins
В комплекте с расширением поставляется несколько Smarty - плагинов, позволяющих более удобно использовать связку Yii + Smarty.
* Функция `widget` и блок `begin_widget` используются что бы подключать  Yii виджеты в шаблоне: 
~~~ smarty
	{*Свойства виджета можно задавать, передавая их как паараметры функции*}
	{widget name="Breadcrumbs" links=['Library'=>'#', 'Data'] someParam="someValue"}
  {widget name="path.to.widget.Class"} {*виджет без параметров*}
  
 	{*Для подключения виджета использующего beginWidget() и endWidget(), необходимо использовать блок {begin_widget}. 
   Параметры передаются точно так же как и в функции. *}
	{begin_widget name="bootstrap.widgets.TbModal" id='anotherModalDialog' options=[backdrop=>static] otherParam="value" [...]}
        	{*Внутри блока доступна переменная {$widget} в которой находится объект текущего виджета.*}
        	{$widget->some_widget_method_or_variable} 
  {/begin_widget} 
~~~

* `Form` плагин. Это синтаксический сахар над begin_widget, который позволяет использовать Yii ActiveForm без написания лишнего кода:
~~~ smarty
   {form name="product_form" id='form' type='horizontal' otherParam="value" [...]}
		    {*Объект формы доступен внутри блока через переменную имя которой такое же как имя формы*}
        {$product_form->textFieldRow($this->model, 'name', ['class'=>'span5','maxlength'=>255])}
   {/form} 
~~~

* Функция `t()` позволяет переводить строки, используя  Yii::t(). Синтаксис:
~~~ smarty
  {t text="text to translate" cat="app"}
  {t text="text to translate" cat="app" src="en" lang="ru"}
  {t text="text to translate" cat="app" params=$params}
~~~

* Функция `link` позволяет генерировать ссылки используя CHtml::link(). Синтаксис:
~~~ smarty
  {link text="test"}
  {link text="test" url="controller/action?param=value"}
  {link text="test" url="/absolute/url"}
  {link text="test" url="http://host/absolute/url"}
~~~

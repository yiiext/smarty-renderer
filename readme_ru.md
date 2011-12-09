Шаблонизатор Smarty для Yii
===========================

Данное расширение позволяет использовать [Smarty](http://www.smarty.net/) версии 3 в приложениях Yii.

###Полезные ссылки
* [Github](https://github.com/yiiext/smarty-renderer)
* [Smarty](http://www.smarty.net/)
* [Обсуждение](http://yiiframework.ru/forum/viewtopic.php?f=9&t=241)
* [Соощить об ошибке](https://github.com/yiiext/smarty-renderer/issues)

###Требования
* Yii 1.0 и выше
* Smarty 3.0.6 и выше

###Установка
* Распаковать в `protected/extensions`.
* [Скачать](http://www.smarty.net/download.php) и распаковать содержимое директории
  `libs` в `protected/vendors/Smarty`.
* Добавить в конфигурацю в секцию 'components':
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
),
~~~
There are some more options on configuring Smarty properties now. Will add documentation soon.

###Использование
* [Документация Smarty](http://www.smarty.net/docs.php).
* Свойства текущего контроллера доступны как {$this->pageTitle}.
* Свойства Yii доступны как {$Yii->theme->baseUrl}.
* Использованную память можно вывести как {$MEMORY}, затраченное время как {$TIME}.

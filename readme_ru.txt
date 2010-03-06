Шаблонизатор Smarty для Yii
===========================

Данное расширение позволяет использовать [Smarty](http://www.smarty.net/) версии 2
или 3 в шаблонах Yii.

###Полезные ссылки
* [SVN](http://code.google.com/p/yiiext/source/browse/trunk/app/extensions#extensions/yiiext/renderers/smarty)
* [Smarty](http://www.smarty.net/)
* [Обсуждение](http://yiiframework.ru/forum/viewtopic.php?f=9&t=241)
* [Соощить об ошибке](http://code.google.com/p/yiiext/issues/list)

###Требования
* Yii 1.0 и выше

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
),
~~~

###Использование
* [Документация Smarty](http://www.smarty.net/docs.php).
* Свойства текущего контроллера доступны как {$this->pageTitle}.
* Свойства Yii доступны как {$Yii->theme->baseUrl}.
* Использованную память можно вывести как {$MEMORY}, затраченное время как {$TIME}.
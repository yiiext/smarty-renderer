Генерация файлов перевода
=========================

Данная команда позволяет сгенерировать файлы перевода, учитывая синтаксис Smarty расширения.

### Установка
* Поместить файл `SmartyMessageCommand.php` в папку `protected/commands`
* Создать или обновить файл конфигурации команды `yiic message`, добавив к сканированию тип файла `tpl`

### Пример конфигурации
```php
<?php
// protected/messages/config.php

/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic smartymessage' command.
 */

return array(
    'sourcePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'messagePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'messages',
    'sourceLanguage' => 'en',
    'languages' => array('ru'),
    'fileTypes' => array('php', 'tpl'),
    'overwrite' => true,
    'exclude' => array(
        '.svn',
        '.gitignore',
        'yiilite.php',
        'yiit.php',
        '/i18n/data',
        '/runtime',
        '/messages',
        '/vendors',
        '/extensions',
        '/web/js',
    ),
);
```

### Использование
```bash
cd protected
yiic smartymessage /path/to/config.php
```


### Полезные ссылки
* [Статья на русском](http://devpad.ru/post/item/12/generate-file-translate-using-yii-ext-smarty-syntax.html)
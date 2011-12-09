1.0.2
-----
- Added ability to define pre- and postfilters in yii config (grigori)
  Filters defined with a class-method array callback syntax are lazy-loaded by yii autoloader
  for compilation only.

1.0.1
-----
- Smarty 3.1 compatibility (Sam Dark, cebe)
- Fixed a problem with nested template rendering and Smarty 3.1.x (cebe)
- Tested compatibility with Smarty 3.0.6-9 and Smarty 3.1.0-5 [ci](http://yiiext.cebe.cc:8080/job/yiiext-smarty-renderer-phpunit/) (cebe)

1.0.0
-----
- changed autoload handling to work also when Yii autoloader has been modified (cebe)
- ensure Smarty does not use SMARTY_SPL_AUTOLOAD (cebe)

0.9.9
-----
- Added possiblity to configure smarty properties (CAUTION: behavior of $filePermission changed) (cebe)
- Fixed issue with rendering Widgets within a template (cebe)

0.9.8
-----
- Fixed renderFile method (Sam Dark)
- Fixed bug with autoload Smarty 3 files in *nix (maksimgrib)

0.9.7
-----
- Code cleanup and minor fixes.

0.9.6
-----
- Changed translation category to 'yiiext'.
- New naming conventions.
- readme_ru.
- Added $Yii variable.

0.9.5
-----
- YiiExt naming conventions changed, see readme.
- Smarty 3 comatibility.
- t and link plugin functions.

0.9
---
- Initial public release (Sam Dark)

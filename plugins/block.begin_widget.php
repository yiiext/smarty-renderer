<?php

/**
 * Allows to use Yii beginWidget() and endWidget() methods in a simple way.
 * There is a variable inside a block wich has 'widget' name and represent widget object
 *
 * Example:
 *  {begin_widget name="activeForm" foo="" bar="" otherParam="" [...]}
 *      {$widget->some_method_or_variable}
 *  {/begin_widget}
 *
 * Yii syntax:
 * {begin_widget className="some.widget.path" properties=['someProperty'=>$someValue]}
 *      {$widget->some_method_or_variable}
 * {/begin_widget}
 *
 * Shorted syntax:
 * {begin_widget c="some.widget.path" p=['someProperty'=>$someValue]}
 *      {$widget->some_method_or_variable}
 * {/begin_widget}
 *
 * @param array                    $params   parameters
 * @param string                   $content  contents of the block
 * @param Smarty_Internal_Template $template template object
 * @param boolean                  &$repeat  repeat flag
 * @return string
 * @throws CException
 * @author t.yacenko (thekip)
 */
function smarty_block_begin_widget($params, $content, $template, &$repeat)
{
    // aliases
    $aliases = array(
        'c'      => 'className',
        'name'   => 'className',
        'p'      => 'properties',
    );

    foreach ($aliases as $alias => $original) {
        if (array_key_exists($alias, $params) && !array_key_exists($original, $params)) {
            $params[$original] = &$params[$alias];
        }
    }

    /** @var $controller CController */
    $controller = $template->getTemplateVars('this');
    if (!is_object($controller) || !($controller instanceof CController)) {
        throw new CException("Can't get controller object from template. Error.");
    }

    if ($repeat) { //tag opened
        if (!isset($params['className'])) {
            throw new CException("className, name or c parameter should be specified.");
        }
<<<<<<< HEAD
        
        $widgetName = $params['name'];
        unset($params['name']);
		
		//some widgets has 'name' as property. You can pass it by '_name' parameter
        if (isset($params['_name'])) {
            $params['name'] = $params['_name'];
            unset($params['_name']);
        }
		
        $template->assign('widget', $controller_object->beginWidget($widgetName, $params, false));
    } else { //tag closed
       echo $content;
       
       $controller_object->endWidget(); 
       $template->clearAssign('widget');
=======

        // transfer params to variables with default values
        $className = $params['className'];
        $properties = empty($params['properties']) ? array() : $params['properties'];

        // unset widget input params
        unset(
            $params['className'], $params['properties'],
            $params['c'], $params['p'], $params['name']
        );

        // some widgets has 'name' property. You can pass it by '_name' parameter
        if (isset($params['_name'])) {
            $properties['name'] = $params['_name'];
            unset($params['_name']);
        }

        // params which are left are moved into widget properties
        $properties = array_merge($properties, $params);
        unset($params);

        $template->assign('widget', $controller->beginWidget($className, $properties));
    }
    else { //tag closed
        echo $content;

        $controller->endWidget();
        $template->clearAssign('widget');
>>>>>>> Yii syntax in widget and begin_widget
    }
}

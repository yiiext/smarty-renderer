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
 * @param array                    $params   parameters
 * @param string                   $content  contents of the block
 * @param Smarty_Internal_Template $template template object
 * @param boolean                  &$repeat  repeat flag
 * @return string 
 * @author t.yacenko (thekip)
 */
function smarty_block_begin_widget($params, $content, $template, &$repeat) {

    $controller_object = $template->tpl_vars['this']->value;

    if ($controller_object == null) {
        throw new CException("Can't get controller object from template. Error.");
    }
    
    if ($repeat) { //tag opened
        if (!isset($params['name'])) {
            throw new CException("Name parameter should be specified.");
        }
        
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
    }
}

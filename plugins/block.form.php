<?php
require_once 'block.begin_widget.php';

/**
* Allows to render a form (Yii ActiveForm) 
 * Technicaly this plugin do the same as block.widget. this is a little syntax sugar for form widget.
 * 
 * There is a variable inside a block with name equalent a form name that represents Form object.
 * 
 * Example:
 *  {form name="product_form" param1="value1" param2="value2" [...]}
 *      {$product_form->some_method_or_variable}
 *  {/form} 
 * 
 * @param array                    $params   parameters
 * @param string                   $content  contents of the block
 * @param Smarty_Internal_Template $template template object
 * @param boolean                  &$repeat  repeat flag
 * @return string 
 * @author t.yacenko (thekip)
 */
function smarty_block_form($params, $content, $template, &$repeat) {
    $formName = $params['name'];
    
    $params['name'] = 'CActiveForm';
    smarty_block_begin_widget($params, $content, $template, $repeat);
    
    if ($repeat) { //assign variable only on open tag
        $template->assign($formName, $template->getVariable('widget')->value);
    }  else {
       $template->clearAssign($formName);
    }
   
}

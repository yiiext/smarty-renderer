<?php
/**
 * Allows to register package via CClientScript
 *
 *
 * @see CClientScript::registerPackage().
 * 
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_register_package($params, &$smarty){
    Yii::app()->clientScript->registerPackage($params['name']);
}
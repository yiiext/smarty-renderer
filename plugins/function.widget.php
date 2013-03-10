<?php
/**
 * Allows to render a widget
 *
 * Syntax:
 * {widget name="path.to.widget.Class" propertyName="propertyValue" anotherProperty=['arrayKey'=>'arrayValue']}
 *
 * Yii syntax:
 * {widget className="some.widget.path" properties=['someProperty'=>$someValue] captureOutput="assignVar"}
 *
 * Shorted syntax:
 * {widget c="some.widget.path"}
 * {widget c="some.widget.path" p=['someProperty'=>$someValue]}
 * {widget c="some.widget.path" p=['someProperty'=>$someValue] assign="assignVar"}
 *
 * Examples:
 * {widget name="bootstrap.widgets.TbBreadcrumbs" links=['Library'=>'#', 'Data']}
 * {widget className="zii.widgets.CListView" properties=['dataProvider'=>$dataProvider, 'itemView'=>'_view']}
 * {widget c="zii.widgets.CListView" p=['dataProvider'=>$dataProvider, 'itemView'=>'_view']}
 *
 * @see CBaseController::widget().
 *
 * @param array $params
 * @param Smarty_Internal_Template $smarty
 * @return string Widget output
 * @throws CException
 */
function smarty_function_widget($params, Smarty_Internal_Template &$smarty)
{
    // aliases
    $aliases = array(
        'c'      => 'className',
        'name'   => 'className',
        'p'      => 'properties',
        'assign' => 'captureOutput',
    );

    foreach ($aliases as $alias => $original) {
        if (array_key_exists($alias, $params) && !array_key_exists($original, $params)) {
            $params[$original] = &$params[$alias];
        }
    }

    /** @var $controller CController */
    $controller = $smarty->getTemplateVars('this');
    if (!is_object($controller) || !($controller instanceof CController)) {
        throw new CException("Can't get controller object from template. Error.");
    }

    if (!isset($params['className'])) {
        throw new CException("className, name or c parameter should be specified.");
    }

<<<<<<< HEAD
    //some widgets has 'name' property. You can pass it by '_name' parameter
    if (isset($params['_name'])) {
        $params['name'] = $params['_name'];
        unset($params['_name']);
    }
    
    return $controller_object->widget($widgetName, $params, true);
=======
    // transfer params to variables with default values
    $className = $params['className'];
    $properties = empty($params['properties']) ? array() : $params['properties'];
    $captureOutput = empty($params['captureOutput']) ? false : $params['captureOutput'];

    // unset widget input params
    unset(
        $params['className'], $params['properties'], $params['captureOutput'],
        $params['c'], $params['p'], $params['name'], $params['assign']
    );

    // some widgets has 'name' property. You can pass it by '_name' parameter
    if (isset($params['_name'])) {
        $properties['name'] = $params['_name'];
        unset($params['_name']);
    }

    // params which are left are moved into widget properties
    $properties = array_merge($properties, $params);
    unset($params);

    $output = $controller->widget($className, $properties, true);
    if ($captureOutput !== FALSE && !empty($captureOutput)) {
        $smarty->assign($captureOutput, $output);
    }
    else {
        return $output;
    }
>>>>>>> Yii syntax in widget and begin_widget
}
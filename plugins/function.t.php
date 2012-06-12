<?php
/**
 * Allows to translate strings using Yii::t().
 *
 * Syntax:
 * {t text="text to translate" cat="app"}
 * {t text="text to translate" cat="app" src="en" lang="ru"}
 * {t text="text to translate" cat="app" params=$params}
 *
 * @see Yii::t().
 *
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_t($params, &$smarty) {
    if (empty($params['text']) || empty($params['cat'])) {
        throw new CException(Yii::t('yiiext', "You should specify both cat and text parameters."));
    }

    $text = $params['text'];
    $category = $params['cat'];
    $source = !empty($params['src']) ? $params['src'] : null;
    $language = !empty($params['lang']) ? $params['lang'] : null;
    $par = !empty($params['params']) ? $params['params'] : array();

    return Yii::t($category, $text, $par, $source, $language);
}
<?php
/**
 * Allows to generate links using CHtml::link().
 *
 * Syntax:
 * {link text="test"}
 * {link text="test" url="controller/action?param=value"}
 * {link text="test" url="/absolute/url"}
 * {link text="test" url="http://host/absolute/url"}
 *
 * @see CHtml::link().
 *
 * @param array $params
 * @param Smarty $smarty
 * @return string
 */
function smarty_function_link($params, &$smarty){
    if(empty($params['text'])){
        throw new CException("Function 'text' parameter should be specified.");
    }
    
    $text = empty($params['text']) ? '#' : $params['text'];
    $options = empty($params['options']) ? array() : $params['options'];    
    $url = '';

    if(!empty($params['url'])){
        $parts = parse_url($params['url']);
        if(!isset($parts['host']) && $parts['path'][1]!='/'){
            $par = array();
            parse_str($parts['query'], $par);
            $url = array(
                $parts['path'],
                $par
            );
        }
        else {
            $url = $params['url'];
        }        
    }     
     
    return CHtml::link($text, $url, $options);
}
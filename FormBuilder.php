<?php

function addInput($type, $name, $value = '', $label = '', $attr = '')
{
    return '<input name="' . $name . '" type="' . $type . '" value="' . $value . '" ' . $attr . '>';
}

function addHtml($html)
{
    return $html;
}
?>
<?php

function stringToIntArray($array) {
    return array_map('intval', $array);
}

function phpToJsWeekdayArray($array) {
    return array_map(function ($ele) {
        return $ele % 7;
    }, $array);
}

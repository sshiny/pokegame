<?php
    
    function displayTypes($types) {
        $str = ucfirst($types[0]);
        if (!empty($types[1])) {
            $str .= ' - ' . ucfirst($types[1]);
        }
        return $str;
    }

    function getTypesAsArray($type1, $type2) {
        $types = array($type1);
        if (isset($type2)) {
            array_push($types, $type2);
        }
        return $types;
    }

    function notification($message, $status) {
        $html = '<div class="notification ' . $status . '">';
        $html .= '<button class="delete"></button>';
        $html .= $message;
        $html .= '</div>';
        return $html;
    }
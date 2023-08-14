<?php
function formatDate($inputDate){
    $dateObj = DateTime::createFromFormat('d/m/Y', $inputDate);

    if($dateObj){
        return $dateObj->format('Y-m-d');
    }
    else{
        return false;
    }
}

function reverseFormatDate($inputDate){
    $dateObj = DateTime::createFromFormat('Y-m-d', $inputDate);

    if($dateObj) {
        return $dateObj->format('d-m-Y');
    }
    else{
        return false;
    }
}
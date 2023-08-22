<?php
//date formatting
function formatDate($inputDate): false|string
{
    $dateObj = DateTime::createFromFormat('d-m-Y', $inputDate);

    if($dateObj){
        return $dateObj->format('Y-m-d');
    }
    else{
        return false;
    }
}
//same as last just reverse
function reverseFormatDate($inputDate): false|string
{
    $dateObj = DateTime::createFromFormat('Y-m-d', $inputDate);

    if($dateObj) {
        return $dateObj->format('d-m-Y');
    }
    else{
        return false;
    }
}
//Check if email is already used for a different account
function validEmail($email): bool
{
    require_once "db_config.php";

    $conn = connect();

    $stmt = $conn->prepare("SELECT email FROM accounts WHERE email=?");
    $stmt -> execute([$email]);
    $result = $stmt->fetch();

    if($result != NULL){
        return false; //is NOT valid
    }
    return true; // is valid
}
//Date of birth validation valid if(13>=DOB<=130)
function validDOB($dob): bool
{
    $dob = new DateTime($dob);

    $min = new DateTime();
    $min->sub(new DateInterval('P13Y')); // Subtract 13 years

    $max = new DateTime();
    $max->sub(new DateInterval('P130Y')); // Subtract 130 years

    $diffToMin = $dob->diff($min);
    $diffToMax = $dob->diff($max);

    return $diffToMin->invert === 0 && $diffToMax->invert === 1;
}
// Check if the date is not in the past or today
function validDate($date): bool
{
    $selectedDate = new DateTime($date);
    $today = new DateTime();

    if($selectedDate > $today){
        return true;
    }
    return false;
}
function getReservationTime($time): false|string
{
    return match ($time) {
        'Breakfast' => '07:00',
        'Lunch' => '12:00',
        'Dinner' => '18:00',
        default => false,
    };
}

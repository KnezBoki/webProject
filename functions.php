<?php
//Generate a random verification code
function generateVerificationCode($length = 20): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}
//Date formatting
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
//Same as last just reverse
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
function getSeats($guests): int
{
    if($guests < 3){$seatsNeeded = 2;}
    elseif ($guests < 5){$seatsNeeded = 4;}
    else($seatsNeeded = 8);

    return $seatsNeeded;
}
//Retrieves user role from database
function getUserRoleFromDB($email, $password): ?string
{
    $conn = connect();

    $stmt = $conn->prepare("SELECT id, password FROM accounts WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if ($result && password_verify($password, $result['password'])) {
        $stmt = $conn->prepare("SELECT role_id FROM user_role WHERE account_id = ?");
        $stmt->execute([$result['id']]);
        $role = $stmt->fetch();
        $stmt = $conn->prepare("SELECT id FROM accounts WHERE email = ?");
        $stmt->execute([$email]);
        $id = $stmt->fetch();
        $_SESSION['id'] = $id;

        switch ($role['role_id']) {
            case 1:
                return "admin";
            case 2:
                return "worker";
            default:
                return "user";
        }
    } else {
        return null;
    }
}
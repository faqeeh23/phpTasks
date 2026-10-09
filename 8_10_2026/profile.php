<?php
session_start();

if (isset($_SESSION["registration"])) {

    $registration = $_SESSION["registration"];

    echo "Name: " . $registration->name . "<br>";
    echo "Email: " . $registration->email . "<br>";
    echo "Mobile: " . $registration->mobile . "<br>";
    echo "Governorate: " . $registration->governorate . "<br>";
    echo "Track: " . $registration->track . "<br>";
    echo "Skills: " . implode(", ", $registration->skills) . "<br>";
    echo "Message: " . $registration->message . "<br>";


} else {

    echo "No registration data found";
}
?>
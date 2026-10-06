<?php
// ============== Array =================

//  Q1  =====================================

$colors = array('white', 'green', 'red');

sort($colors);

echo "<ul>";

foreach ($colors as $color) {
    echo "<li>$color</li>";
}

echo "</ul>" ;

//  Q2  ====================================

$cities = array(
    "Italy" => "Rome",
    "Luxembourg" => "Luxembourg",
    "Belgium" => "Brussels",
    "Denmark" => "Copenhagen",
    "Finland" => "Helsinki",
    "France" => "Paris",
    "Slovakia" => "Bratislava",
    "Slovenia" => "Ljubljana",
    "Germany" => "Berlin",
    "Greece" => "Athens",
    "Ireland" => "Dublin",
    "Netherlands" => "Amsterdam",
    "Portugal" => "Lisbon",
    "Spain" => "Madrid"
);

asort($cities);

foreach ($cities as $contry => $capital) {
    echo "The capital of $contry is $capital <br>";
}

//  Q3 =================================

$color = array(
    4 => 'white',
    6 => 'green',
    11 => 'red'
);

$firstColor = reset($color);

echo $firstColor;

//  Q4 ==================================

$array = array(1, 2, 3, 4, 5);

$location = 4;
$newItem = "$";

$index = $location - 1;
array_splice($array, $index, 0, $newItem);

print_r($array);

//  Q5 ===================================

$fruits = array(
    "d" => "lemon",
    "a" => "orange",
    "b" => "banana",
    "c" => "apple"
);

asort($fruits);


foreach ($fruits as $key => $fruit) {
    echo "<li>$key => $fruit</li>";
}

// Q6 ======================================
$temperatures = array(
    78, 60, 62, 68, 71, 68, 73, 85, 66, 64,
    76, 63, 75, 76, 73, 68, 62, 73, 72, 65,
    74, 62, 62, 65, 64, 68, 73, 75, 79, 73
);
$total = array_sum($temperatures);
$count = count($temperatures);
$avg = $total / $count;

echo "Average Temperature is : " . round($avg, 1) , "<br>";


sort($temperatures);

$lowest = array_slice($temperatures, 0, 5);

echo "lowest : " . implode(", ", $lowest) . "<br>";

$bigest = array_slice($temperatures, -5, -1);

echo "bigest : " . implode(", ", $bigest) . "<br>";

//  Q7 ========================================

$array1 = array("color" => "red", 2, 4);

$array2 = array(
    "a",
    "b",
    "color" => "green",
    "shape" => "trapezoid",
    4
);

$result = array_merge($array1, $array2) ;

print_r($result);
echo "<br>";

//  Q8 =============================================

$colors = array("red", "blue", "white", "yellow");

function toUpper($color) {
    return strtoupper($color);
}

$upperColors = array_map("toupper" , $colors) ;

array_map("toUpper" , $colors);

print_r($upperColors);
echo "<br>";

// ==================== Function  ==================

// Q1 

function isPrime($number) {
    if ($number %2 !== 0) {
        echo "$number is Prime";

    } else {
        echo "$number is not Prime";
    }
}

isPrime(3);
echo "<br>";
isPrime(4);
echo "<br>";

// Q2 

function revStr($text) {
    echo strrev($text);
}
echo "<br>";

revStr("Mohammed");
echo "<br>";

// Q3

function swap(&$x , &$y) {
    $temp = $x;
    $x = $y;
    $y = $temp;
    echo "X : $x  Y : $y";
}
$x = 10 ;
$y = 20 ;
swap($x, $y );
echo "<br>";

// Q4 

function isArmstrong($number) {
    $original = $number;
    $sum = 0;
    while ($number > 0) {
        $digit = $number % 10;
        $sum += $digit **3;
        $number = intdiv($number, 10);
    }
    return $sum === $original ;
}

$number = 407;
if (isArmstrong($number)) {
    echo "$number is Amstrong Number";
}else {
    echo "$number is not Armstrong Number" ;
}
echo "<bR>";
// Q5 

function isPalindrome($text) {
    $text = strtolower($text);
    $text = preg_replace("/[^a-z0-9]/", "", $text);
    return $text === strrev($text);
}

$text = "Eva, can I see bees in a cave?";
if (isPalindrome($text)) {
    echo "Yes it is a palindrome";
} else {
    echo "No it is not a palindrome";
}
echo "<br>";
// Q6

function removDup($array) {
    return array_unique($array);
}
$array1 = array(2, 4, 7, 4, 8, 4);
removDup($array1);


// =================== Logical Statements and Operators ===================

// Q1

$firstInteger = 10;
$secondInteger = 10;

$sum = $firstInteger + $secondInteger;

if ($sum == 30) {
    echo $sum;
} else {
    echo "false";
}

echo "<br>";

// Q2

$number = 20;

if ($number > 0 && $number % 3 == 0) {
    echo "true";
} else {
    echo "false";
}

echo "<br>";

// Q3

$number = 50;

if ($number >= 20 && $number <= 50) {
    echo "true";
} else {
    echo "false";
}

echo "<br>";

// Q4

$numbers = array(1, 5, 9);

$largest = $numbers[0];

if ($numbers[1] > $largest) {
    $largest = $numbers[1];
}

if ($numbers[2] > $largest) {
    $largest = $numbers[2];
}

echo $largest;

echo "<br>";


// Q5

$units = 180;
$bill = 0;

if ($units <= 50) {

    $bill = $units * 2.50;

} elseif ($units <= 150) {

    $bill = (50 * 2.50) + (($units - 50) * 5.00);

} elseif ($units <= 250) {

    $bill =
        (50 * 2.50)
        + (100 * 5.00)
        + (($units - 150) * 6.20);

} else {

    $bill =
        (50 * 2.50)
        + (100 * 5.00)
        + (100 * 6.20)
        + (($units - 250) * 7.50);
}

echo "Electricity Bill : $bill JOD";

echo "<br>";


// Q6

$num1 = 20;
$num2 = 5;

$operator = "*";

if ($operator == "+") {

    echo $num1 + $num2;

} elseif ($operator == "-") {

    echo $num1 - $num2;

} elseif ($operator == "*") {

    echo $num1 * $num2;

} elseif ($operator == "/") {

    if ($num2 != 0) {
        echo $num1 / $num2;
    } else {
        echo "Cannot divide by zero";
    }

} else {

    echo "Invalid operator";
}

echo "<br>";

// Q7

$age = 15;

if ($age >= 18) {
    echo "is eligible to vote";
} else {
    echo "is not eligible to vote";
}

echo "<br>";

// Q8

$number = -60;

if ($number > 0) {

    echo "Positive";

} elseif ($number < 0) {

    echo "Negative";

} else {

    echo "Zero";
}

echo "<br>";


// Q9

$scores = array(60,86,95,63,55,74,79,62,50);

$total = array_sum($scores);
$count = count($scores);

$average = $total / $count;

if ($average >= 90) {

    echo "A";

} elseif ($average >= 80) {

    echo "B";

} elseif ($average >= 70) {

    echo "C";

} elseif ($average >= 60) {

    echo "D";

} else {

    echo "F";
}

echo "<br>";


// ========================== Loops ==========================

// Q1

for ($i = 1; $i <= 10; $i++) {

    echo $i;

    if ($i < 10) {
        echo "-";
    }
}

echo "<br>";


// Q2

$total = 0;

for ($i = 0; $i <= 30; $i++) {
    $total += $i;
}

echo $total;

echo "<br>";

// Q5

for ($i = 1; $i <= 5; $i++) {

    for ($j = 1; $j <= 5; $j++) {

        if ($i == $j) {
            echo $i . " ";
        } else {
            echo "0 ";
        }
    }

    echo "<br>";
}

// Q6

$number = 5;
$factorial = 1;

for ($i = 1; $i <= $number; $i++) {
    $factorial *= $i;
}

echo $factorial;

echo "<br>";

// ================= PHP String and String Functions =================

// Q1

$text = "orange coding academy";

echo strtoupper($text);
echo "<br>";

echo strtolower($text);
echo "<br>";

echo ucfirst($text);
echo "<br>";

echo ucwords($text);
echo "<br>";


// Q2

$time = "085119";

$result =
    substr($time, 0, 2)
    . ":"
    . substr($time, 2, 2)
    . ":"
    . substr($time, 4, 2);

echo $result;

echo "<br>";


// Q3

$sentence = "I am a full stack developer at orange coding academy";

$word = "Orange";

if (stripos($sentence, $word) !== false) {

    echo "Word Found!";

} else {

    echo "Word Not Found!";
}

echo "<br>";

// Q4

$url = "www.orange.com/index.php";

$fileName = basename($url);

echo $fileName;

echo "<br>";


// Q5

$email = "info@orange.com";

$username = strstr($email, "@", true);

echo $username;

echo "<br>";

// Q6

$email = "info@orange.com";

$lastThree = substr($email, -3);

echo $lastThree;

echo "<br>";

// Q7

$characters = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

$shuffled = str_shuffle($characters);

$password = substr($shuffled, 0, 10);

echo $password;

echo "<br>";


// Q8

$sentence = "That new trainee is so genius.";

$newWord = "Our";

$words = explode(" ", $sentence);

$words[0] = $newWord;

$result = implode(" ", $words);

echo $result;
?>
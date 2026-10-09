<?php

// ================= PHP String and String Functions =================

// Q1

$string = "Twinkle, twinkle, little star.";
$array = explode(" ", $string);
var_dump($array);

echo "<br><br>";

// Q2

$char = 'a';
if ($char == 'z') {
    echo 'a';
} else {
    echo ++$char;
}

echo "<br><br>";

// Q3

$string = "The brown fox";
$insert = "quick ";
$position = 4;
$result = substr_replace($string, $insert, $position, 0);

echo $result;
echo "<br><br>";


// Q4

$string = "The quick brown fox";
$firstWord = strstr($string, " ", true);

echo $firstWord;
echo "<br><br>";


// Q5
$number = "0000657022.24";
$result = ltrim($number, "0");

echo $result;
echo "<br><br>";


// Q6

$string = "The quick brown fox jumps over the lazy dog---";
$result = rtrim($string, "-");

echo $result;

echo "<br><br>";


// Q7

$string = "The quick brown fox jumps over the lazy dog";
$words = explode(" ", $string);
$firstFive = array_slice($words, 0, 5);
$result = implode(" ", $firstFive);

echo $result;

echo "<br><br>";


// Q8

$number = "2,543.12";
$result = str_replace(",", "", $number);

echo $result;

echo "<br><br>";



// ================= LOOP =================

// Q1

$first = 0;
$second = 1;

echo $first . ", " . $second;

for ($i = 3; $i <= 10; $i++) {
    $next = $first + $second;
    echo ", " . $next;
    $first = $second;
    $second = $next;
}

echo "<br><br>";


// Q2

$n = 5;

$number = 1;

for ($i = 1; $i <= $n; $i++) {
    for ($j = 1; $j <= $i; $j++) {
        echo $number . " ";
        $number++;
    }
    echo "<br>";
}

echo "<br>";


// Q3
for ($i = 1; $i <= 5; $i++) {
    for ($j = 0; $j < $i; $j++) {
        echo chr(65 + $j) . " ";
    }
    echo "<br>";
}
for ($i = 4; $i >= 1; $i--) {
    for ($j = 0; $j < $i; $j++) {

        echo chr(65 + $j) . " ";
    }
    echo "<br>";
}

echo "<br>";

// ================= Logical Statements and Operators =================

// Q1

$year = 2013;

if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) {
    echo "This year is a leap year";
} else {
    echo "This year is not a leap year";
}

echo "<br><br>";

// Q2

$temperature = 27;
if ($temperature < 20) {
    echo "It is wintertime!";
} else {
    echo "It is summertime!";
}

echo "<br><br>";


// Q3

$firstInteger = 2;
$secondInteger = 2;
$sum = $firstInteger + $secondInteger;
if ($firstInteger == $secondInteger) {
    echo $sum * 3;
} else {
    echo $sum;
}
echo "<br><br>";


// Q4

for ($i = 200; $i <= 250; $i++) {
    if ($i % 4 == 0) {
        echo $i;
        if ($i < 248) {
            echo ",";
        }
    }
}

echo "<br><br>";


// Q5

$numbers = range(11, 20);
shuffle($numbers);
foreach ($numbers as $number) {
    echo $number . " ";
}

?>
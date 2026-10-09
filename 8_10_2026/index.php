<?php
  session_start();

  

  $name = "";
  $email = "";
  $mobile = "";
  $governorate = "";
  $track = "" ;
  $message = "";
  $agree = "";
  $skills = array();
  $nameError = "";
  $emailError = "";
  $mobileError = "" ;
  $governorateError = "";
  $trackError = "";
  $skillsError = "";
  $agreeError = "";

  $savedName = $_COOKIE["userName"] ?? "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $governorate = trim($_POST["governorate"] ?? "");
    $message = trim($_POST["message"] ?? "");

    $track = $_POST["track"] ?? "";
    $skills = $_POST["skills"] ?? array();
    $agree = $_POST["agree"] ?? "";

    if ($name == "") {
        $nameError = "Full name is required";
    }

    if ($email == "") {
        $emailError = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    }

    if ($mobile == "") {
        $mobileError = "Mobile is required";
    } elseif (!preg_match("/^07[789][0-9]{7}$/", $mobile)) {
        $mobileError = "Invalid mobile number";
    }

    if ($governorate == "") {
        $governorateError = "Governorate is required";
    }

    if ($track == "") {
        $trackError = "Track is required";
    }

    if (count($skills) == 0) {
        $skillsError = "Select at least one skill";
    }

    if ($agree == "") {
        $agreeError = "You must accept the terms";
    }

    if (
        $nameError == "" &&
        $emailError == "" &&
        $mobileError == "" &&
        $governorateError == "" &&
        $trackError == "" &&
        $skillsError == "" &&
        $agreeError == ""
    ) {

        $registration = new stdClass();

        $registration->name = $name;
        $registration->email = $email;
        $registration->mobile = $mobile;
        $registration->governorate = $governorate;
        $registration->track = $track;
        $registration->skills = $skills;
        $registration->message = $message;

        $_SESSION["registration"] = $registration;

        setcookie(
            "userName",
            $name,
            time() + (86400 * 30),
            "/"
        );

        header("Location: profile.php");
        exit;
    }
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
   <form action="" method="post">

  <label for="name">Full Name</label>
  <input type="text" id="name" name="name" value = "<?php echo htmlspecialchars($name);?>">
  <span><?php echo $nameError?></span>

  <label for="email">Email</label>
  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
  <span><?php echo $emailError; ?></span>

  <label for="mobile">Mobile</label>
  <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="0791234567">
  <span><?php echo $mobileError; ?></span>
  
  <label for="governorate">Governorate</label>
  <select id="governorate" name="governorate">
    <option value="">Choose a governorate</option>
    <option value="Amman" <?php if ($governorate == "Amman") echo "selected"; ?>>Amman</option>
    <option value="Irbid" <?php if ($governorate == "Irbid") echo "selected"; ?>>Irbid</option>
    <option value="Aqaba" <?php if ($governorate == "Aqaba") echo "selected"; ?>>Aqaba</option>
    <option value="Zarqa" <?php if ($governorate == "Zarqa") echo "selected"; ?>>Zarqa</option>
  </select>
  <span><?php echo $governorateError; ?></span>

  <label>Track</label>

  <label class="inline">
    <input type="radio" name="track" value="Full Stack"
      <?php if ($track == "Full Stack") echo "checked"; ?>>
    Full Stack
  </label>

  <label class="inline">
    <input type="radio" name="track" value="Frontend" <?php if ($track == "Frontend") echo "checked"; ?>>
    Frontend
  </label>

  <label class="inline">
    <input type="radio" name="track" value="Backend" <?php if ($track == "Backend") echo "checked"; ?>>
    Backend
  </label>
  <span><?php echo $trackError; ?></span>

  <label>Skills you already have</label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="HTML"
    <?php if (in_array("HTML", $skills)) echo "checked"; ?>>
    HTML
  </label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="CSS" <?php if (in_array("CSS", $skills)) echo "checked"; ?>>
    CSS
  </label>

  <label class="inline">
    <input type="checkbox" name="skills[]" value="JavaScript" <?php if (in_array("JavaScript", $skills)) echo "checked"; ?>>
    JavaScript
  </label>
  <span><?php echo $skillsError; ?></span>


  <label for="message">Why do you want to join? (optional)</label>
  <textarea id="message" name="message" rows="4"><?php echo htmlspecialchars($message); ?></textarea>

  <label class="inline terms">
    <input type="checkbox" name="agree"
    <?php if ($agree != "") echo "checked"; ?>>
    I agree to the academy terms
  </label>

  <span><?php echo $agreeError; ?></span>

  <button type="submit">Register</button>

</form> 
</body>
</html>
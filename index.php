<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if(isset($_POST["btn"])){
    $name = $_POST["Name"];
    $aadhar = $_POST["Aadhar"];
    $number = $_POST["Phone"];
    setcookie("Name", $name, time() + (86400), "/"); // 86400 = 1 day
    setcookie("Aadhar", $aadhar, time() + (86400), "/");
    setcookie("Phone", $number, time() + (86400), "/");

    // Redirect to part 2 of the form
    header("Location: part2.php");
    exit();
}
?>
<form method ="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Enter Name:</label>
    <input type="text" name="Name">
    <br>
    <label>Enter Aadhar card:</label>
    <input type="number" name="Aadhar">
    <br>
    <label>Enter Phone number:</label>
    <input type="number" name="Phone">
    <button name="btn" type="submit">Submit</button>
</form>
</body>
</html>

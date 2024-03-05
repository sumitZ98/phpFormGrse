<?php
if (!isset($_COOKIE["Name"]) || !isset($_COOKIE["Aadhar"]) || !isset($_COOKIE["Phone"])) {
    header("location:index.php");
} else {
    $name = $_COOKIE["Name"];
    $aadhar = $_COOKIE["Aadhar"];
    $number = $_COOKIE["Phone"];
}

include "./connect.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST" enctype="multipart/form-data">
    <label> Enter pic-1: </label>
    <input type="file" name="pic-one" accept="image/jpeg, image/png, image/gif" required><br>
    <label> Enter pic-1: </label>
    <input type="file" name="pic-two" accept="image/jpeg, image/png, image/gif" required><br>
    <label> Enter pic-1: </label>
    <input type="file" name="pic-three" accept="image/jpeg, image/png, image/gif" required><br>
    <button name="btn" type="submit">Submit</button>
    </form>
    <?php
if(isset($_POST["btn"])) {
    // Check if file upload fields were submitted
    if(isset($_FILES["pic-one"]) && isset($_FILES["pic-two"]) && isset($_FILES["pic-three"])) {
        // Check for errors in file uploads
        if ($_FILES["pic-one"]["error"] === UPLOAD_ERR_OK &&
            $_FILES["pic-two"]["error"] === UPLOAD_ERR_OK &&
            $_FILES["pic-three"]["error"] === UPLOAD_ERR_OK) {
            
            // Check if the directory exists, if not create it
            $aadharDirectory = 'images/' . $_COOKIE["Aadhar"];
            if (!is_dir($aadharDirectory)) {
                mkdir($aadharDirectory, 0777, true);
            }

            // Upload and move the files to the directory
            $targetDir = $aadharDirectory . '/';
            if(basename($_FILES["pic-one"]["name"])==basename($_FILES["pic-two"]["name"]) && basename($_FILES["pic-one"]["name"])==basename($_FILES["pic-three"]["name"])){
                $extension = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_EXTENSION);
                $filename = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_FILENAME);
                
            $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
            $picTwoPath = $targetDir . $filename . "(1).".$extension;
            $picThreePath = $targetDir . $filename . "(2).".$extension;

            }
             else if (basename($_FILES["pic-one"]["name"])==basename($_FILES["pic-two"]["name"])) {
                $extension = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_EXTENSION);
                $filename = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_FILENAME);
                
            $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
            $picTwoPath = $targetDir . $filename . "(1).".$extension;
            $picThreePath = $targetDir . basename($_FILES["pic-three"]["name"]);
            }
 
            else if(basename($_FILES["pic-one"]["name"])==basename($_FILES["pic-three"]["name"])){
                $extension = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_EXTENSION);
                $filename = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_FILENAME);
                
            $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
            $picTwoPath = $targetDir . basename($_FILES["pic-two"]["name"]);
            $picThreePath = $targetDir . $filename . "(1).".$extension;
            }
            else if(basename($_FILES["pic-three"]["name"])==basename($_FILES["pic-two"]["name"])){
                $extension = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_EXTENSION);
                $filename = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_FILENAME);
                
            $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
            $picTwoPath = $targetDir . basename($_FILES["pic-two"]["name"]);
            $picThreePath = $targetDir . $filename . "(1).".$extension;
            }
            else {
            $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
            $picTwoPath = $targetDir . basename($_FILES["pic-two"]["name"]);
            $picThreePath = $targetDir . basename($_FILES["pic-three"]["name"]);
            }
            $picOnePath = uniqueFilename($picOnePath, $targetDir);
            $picTwoPath = uniqueFilename($picTwoPath, $targetDir);
            $picThreePath = uniqueFilename($picThreePath, $targetDir);

            move_uploaded_file($_FILES["pic-one"]["tmp_name"], $picOnePath);
            move_uploaded_file($_FILES["pic-two"]["tmp_name"], $picTwoPath);
            move_uploaded_file($_FILES["pic-three"]["tmp_name"], $picThreePath);
        } else {
            echo "Error uploading files.";
        }
    } else {
        echo "One or more files not uploaded.";
    }

    $query = "INSERT INTO `liveform`(`name`, `aadhar`, `phone`, `pic-one`, `pic-two`, `pic-three`) VALUES ('$name','$aadhar','$number','$picOnePath','$picTwoPath','$picThreePath')";
    $res = mysqli_query($con, $query);
    if ($res) {
        setcookie("Name", $name, time() -3600, "/"); // 86400 = 1 day
    setcookie("Aadhar", $aadhar, time() -3600, "/");
    setcookie("Phone", $number, time() -3600, "/");
        ?>
            <script>alert("Successfully Submitted")</script>
        <?php
        header("location:index.php");
    }
}

function uniqueFilename($path, $targetDir) {
    $count = 1;
    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $filename = pathinfo($path, PATHINFO_FILENAME);

    while(file_exists($path)) {
        $path = $targetDir . $filename . '(' . $count . ').' . $extension;
        $count++;
    }

    return $path;
}
?>
</body>
</html>
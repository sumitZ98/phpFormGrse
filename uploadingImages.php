//Here we upload images one at a time
//next-step- to crop the images given by the user

<!DOCTYPE html>
<html>
<head>
    <title>Image Upload</title>
</head>
<body>

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    unset($_SESSION['count']);
}

// Initialize upload count if it's not set
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    $_SESSION['count']++;
    $name = $_POST['num'];
    echo "Counter:" . $_SESSION['count'];
    if ($_SESSION['count'] < 3){


        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
            $dir = "images-demo/".$name;

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $target_dir = $dir . '/';
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            echo $target_file;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
                echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
            }
        }

    }
    if($_SESSION['count']==3){
        header("location:crop.php");
    }
}
?>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="image" id="image">
    <input type="text" name="num">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>

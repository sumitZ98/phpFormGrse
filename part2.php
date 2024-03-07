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
    <style>
    #popup{
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #f44336;
    color: white;
    padding: 15px 20px;
    border-radius: 5px;
    z-index: 1000;
  }
  .content{
    display:flex;
    align-items: center;
    justify-content: center;
    gap:15px
 }
  h1 > span{
    font-size: 30px;
    color: black;
    cursor:pointer;
  }
    </style>
    <!-- <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script> -->
<!-- <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" /> -->
</head>
<body>

    <div id="popup">
        <div class="content">
            <h1>Please Enter an image <span id="cut">x</span></h1>   
        </div>
    </div>




    <form class="drpzone" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method = "POST" enctype="multipart/form-data">
    <label> Enter pic-1: </label>
    <input id="img-1" type="file" name="pic-one" required><br>
    <label> Enter pic-1: </label>
    <input id="img-2" type="file" name="pic-two"  required><br>
    <label> Enter pic-1: </label>
    <input id="img-3" type="file" name="pic-three"  required><br>
    <!-- <div class="fallback">
    <label> Enter pic-1: </label>
    <input type="file" name="pic-one"  required><br>
    <label> Enter pic-1: </label>
    <input type="file" name="pic-two"  required><br>
    <label> Enter pic-1: </label>
    <input type="file" name="pic-three" required><br>
    </div> -->
    <button name="btn" type="submit">Submit</button>
    </form>
    <?php
    if (isset($_POST["btn"])) {
        // Check if file upload fields were submitted
        if (isset($_FILES["pic-one"]) && isset($_FILES["pic-two"]) && isset($_FILES["pic-three"])) {
            // Check for errors in file uploads
            if (
                $_FILES["pic-one"]["error"] === UPLOAD_ERR_OK &&
                $_FILES["pic-two"]["error"] === UPLOAD_ERR_OK &&
                $_FILES["pic-three"]["error"] === UPLOAD_ERR_OK
            ) {

                // Check if the directory exists, if not create it
                $aadharDirectory = 'images/' . $_COOKIE["Aadhar"];
                if (!is_dir($aadharDirectory)) {
                    mkdir($aadharDirectory, 0777, true);
                }

                // Upload and move the files to the directory
                $targetDir = $aadharDirectory . '/';
                if (basename($_FILES["pic-one"]["name"]) == basename($_FILES["pic-two"]["name"]) && basename($_FILES["pic-one"]["name"]) == basename($_FILES["pic-three"]["name"])) {
                    $extension = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_EXTENSION);
                    $filename = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_FILENAME);

                    $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
                    $picTwoPath = $targetDir . $filename . "(1)." . $extension;
                    $picThreePath = $targetDir . $filename . "(2)." . $extension;

                }
                //checking
                else if (basename($_FILES["pic-one"]["name"]) == basename($_FILES["pic-two"]["name"])) {
                    $extension = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_EXTENSION);
                    $filename = pathinfo(basename($_FILES["pic-two"]["name"]), PATHINFO_FILENAME);

                    $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
                    $picTwoPath = $targetDir . $filename . "(1)." . $extension;
                    $picThreePath = $targetDir . basename($_FILES["pic-three"]["name"]);
                } else if (basename($_FILES["pic-one"]["name"]) == basename($_FILES["pic-three"]["name"])) {
                    $extension = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_EXTENSION);
                    $filename = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_FILENAME);

                    $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
                    $picTwoPath = $targetDir . basename($_FILES["pic-two"]["name"]);
                    $picThreePath = $targetDir . $filename . "(1)." . $extension;
                } else if (basename($_FILES["pic-three"]["name"]) == basename($_FILES["pic-two"]["name"])) {
                    $extension = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_EXTENSION);
                    $filename = pathinfo(basename($_FILES["pic-three"]["name"]), PATHINFO_FILENAME);

                    $picOnePath = $targetDir . basename($_FILES["pic-one"]["name"]);
                    $picTwoPath = $targetDir . basename($_FILES["pic-two"]["name"]);
                    $picThreePath = $targetDir . $filename . "(1)." . $extension;
                } else {
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
            setcookie("Name", $name, time() - 3600, "/"); // 86400 = 1 day
            setcookie("Aadhar", $aadhar, time() - 3600, "/");
            setcookie("Phone", $number, time() - 3600, "/");
            ?>
                            <script>alert("Successfully Submitted")</script>
                        <?php
                        header("location:index.php");
        }
    }

    function uniqueFilename($path, $targetDir)
    {
        $count = 1;
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $filename = pathinfo($path, PATHINFO_FILENAME);

        while (file_exists($path)) {
            $path = $targetDir . $filename . '(' . $count . ').' . $extension;
            $count++;
        }

        return $path;
    }
    ?>

<script>
        document.getElementById('img-1').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var fileType = file.type;
                if (!fileType.startsWith('image/')) {
                    // alert('Please select an image file.');
                    document.getElementById("popup").style.display = "block";
                    this.value = ''; // Clear the input field
                }
            }
        });

        document.getElementById('img-2').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var fileType = file.type;
                if (!fileType.startsWith('image/')) {
                    alert('Please select an image file.');
                    this.value = ''; // Clear the input field
                }
            }
        });

        document.getElementById('img-3').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var fileType = file.type;
                console.log(fileType);
                if (!fileType.startsWith('image/')) {
                    alert('Please select an image file.');
                    this.value = ''; // Clear the input field
                }
            }
        });

        document.getElementById("cut").addEventListener("click",()=>{
            document.getElementById("popup").style.display = "none";
            
        })
</script>

<!-- <script>
    let myDropzone = new Dropzone(".drpzone");
</script> -->
</body>
</html>
<?php
// session start
session_start();
?>

<html>
    <head>
        <link rel="stylesheet" href="overallCascadeStyleSheet.css">
    </head>
    <body>
        <br> <br>
        <!-- Uploading Files -->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"  enctype="multipart/form-data" >
            Select image to upload:
            <input type="file" name="fileToUpload[]" id="fileToUpload" accept="image/*" multiple>
            &ensp;
            <input type="submit" value="Submit" name="submit">
        </form>
        <br>

        <?php
        $id = $_SESSION['id'];
        if (isset($_FILES['fileToUpload'])) {
            $files = $_FILES['fileToUpload']['name'];
            $count = count($files);
            $x = 0;

            while ($x < $count) {

                $target_dir = "Uploads/";
                $target_file = $target_dir . $id . basename($_FILES["fileToUpload"]["name"][$x]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,
                                PATHINFO_EXTENSION
                        )
                );

                if (!empty($_FILES["fileToUpload"]["tmp_name"][$x])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$x]);
                    if ($check !== false) {
                        // echo "File is an image = " . $check["mime"] . ". <br>";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }



                    // Check if file already exists
                    if (file_exists($target_file)) {
                        echo "Sorry, file already exists. <br>";
                        $uploadOk = 0;
                    }

                    // Check  file size
                    if ($_FILES["fileToUpload"]["size"][$x] > 500000) {
                        echo "Sorry, your file is too large.<br>";
                        $uploadOk = 0;
                    }

                    // Allow certain file formats
                    if ($imageFileType != "webp" && $imageFileType != "png" && $imageFileType != "jpeg"
                            && $imageFileType != "gif") {
                        echo "Sorry, only Webp, JPEG, PNG & GIF files are allowed.<br>";
                        $uploadOk = 0;
                    }

                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file is was not uploaded.<br>";
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$x],
                                        $target_file
                                )) {
                            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"][$x])) . " is uploaded. <br>";

                            // Upload Picture
                            require "networkValidation.php";
                            
                            $name = $_FILES["fileToUpload"]['name'][$x];

                            //insert file into pictures
                            $stmt = $conn->prepare("INSERT INTO `pictures$id` (`ID`,`Owner`,`Name`)VALUES (?, ?, ?);");
                            $stmt->bind_param("sis", $pID, $owner, $pName);
                            // set paramenters
                            $pID = "NULL";
                            $owner = $_SESSION['id'];
                            $pName = $owner . $name;
                            $stmt->execute();

                            $conn->close();

                            echo "Return to the <a href='gallery.php'>gallery</a> to find your picture. <br>";
                        } else {
                            echo "Sorry, there was an error uploading your file.<br>";
                        }
                    }
                } else {
                    echo "Choose a file.";
                }
                $x++;
            }
        }

        echo "<br> Return to your <a href='gallery.php'>gallery</a>";
        ?>
    </body>
</html>
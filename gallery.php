<?php
// session start
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="overallCascadeStyleSheet.css">
    </head>
    <body>
        <?php
        $id = $_SESSION['id'];

        echo "<h2>Picture Gallery</h2>";

        echo "Add a <a href='uploadFile.php'>picture</a> to the gallery <br>";

        require "networkValidation.php";

        // sql to create table
        $sql = "CREATE TABLE IF NOT EXISTS pictures$id (
ID INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
Owner int(3) NOT NULL,
Name VARCHAR(40) NOT NULL
)";
        $conn->query($sql);
        $conn->close();

        // Connection Closed
        // Resize Images
        function image_resize($file_name, $height) {
            $orgfile = 'Uploads/' . $file_name;
            list($wid, $ht) = getimagesize($orgfile);
            $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION
                    )
            );
            if ($imageFileType == "jpeg") {
                $source = imagecreatefromjpeg($orgfile);
            } elseif ($imageFileType == "png") {
                $source = imagecreatefrompng($orgfile);
            } elseif ($imageFileType == "webp") {
                $source = imagecreatefromwebp($orgfile);
            } else {
                $source = imagecreatefromgif($orgfile);
            }
            // ratio for 300px new height
            $rh = ($height / $ht);
            $newwidth = $wid * $rh;
            $newheight = $ht * $rh;
            $thumb = 'Uploads/_' . $file_name;
            $dst = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($dst, $source, 0, 0, 0, 0, $newwidth, $newheight,
                    $wid, $ht
            );
            imagejpeg($dst, $thumb, 100);
            // free up memory
        }

        // downloadFile($fileSource);
        //$img_to_resize = image_resize($fileSource, 2400, 1600);
        // Find the User
  
        require "networkValidation.php";
        // set the PDO error mode to exception
        $conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM pictures$id WHERE Owner = '$id'";

        $result = $conn->query($sql);
        // set fetch mode
        $result->setFetchMode(PDO::FETCH_ASSOC);

        if ($result->rowCount() != 0) {
            echo "<form action='downloadFile.php' method='POST' enctype='multipart/form-data' multiple>";
            // output data of each row
            while ($row = $result->fetch()) {
                $name = $row["Name"];
                image_resize("$name", 250);
                echo "<div class='gallerybox'><img src='Uploads/_$name'><br>
            <input type='checkbox' name='checked[]' id='checked' value='$name' multiple><label for='checked'>Selection</label><br><br></div>";
            }
            echo "<br><input type='submit' value='Download Files' name='submit'> &ensp; <input type='submit' value='Delete' name='submit' formaction='deletePictures.php'>
        </form>";
        } else {
            echo "";
        }

        $conn2 = null;

        echo "<br><br>";
        echo "Return your <a href='profile.php'>profile</a> <br>";
        ?>

    </body>
</html>

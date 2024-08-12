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
        <?PHP
        // Delete Picture from Gallery Table

        function deletepic($name) {
            require "networkValidation.php";

            if (!empty($_POST['checked'])) {
                // MySQLi Object-oriented

                $id = $_SESSION['id'];
                $path = "Uploads/" . $name;
                $path2 = "Uploads/_" . $name;

                // "Connected successfully for CST8257 <br>";
                // prepare and bind statment
                $sql2 = "DELETE FROM `pictures$id` WHERE Name = '$name' ";
                $conn->query($sql2);
                unlink($path);
                unlink($path2);

                $conn->close();
                // End of server connection

                echo "$name Deleted <br>";
            } else {
                echo "There was an error. Try Again<br>";
            }
        }

        
        $files = $_POST["checked"];
        $filesLength = count($files);
        
        for($x =0; $x < $filesLength; $x++) {
            $fileSource = $files[$x];
            deletepic($fileSource);
        }
        
        echo "Return your the <a href='gallery.php'>gallery</a> <br>";
        echo "<br><br>";
        ?>
    </body>
</html>

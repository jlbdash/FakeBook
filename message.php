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
        if (!empty($_POST['message'])) {
            require "networkValidation.php";

            $id = $_POST['friends'];

            // Find Friend ID
            $selectUser = "SELECT * FROM project WHERE `ID` = '$id'";
            $row = $conn->query($selectUser);
            $result = $row->fetch_assoc();

            // prepare and bind statment
            $stmt = $conn->prepare("INSERT INTO `message` (`Number`,`ID`,`Friend ID`,`Message`,`Date`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $number, $id, $idf, $message, $date
            );

            // set parameters and execute
            $number = "Null";
            $id = $_SESSION['id'];
            $idf = $result['ID'];
            $message = $_POST['message'];
            $date = date("Y-m-d");
            $stmt->execute();

            $stmt->close();
            $conn->close();

            echo "Message Sent!";
        } else {
        
         echo "No message to be sent!";
        }
            echo "<br><br>";
            echo "Return your <a href='profile.php'>profile</a> <br>";
        ?>

    </body>
</html>
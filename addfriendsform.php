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
        require "networkValidation.php";

        // Friend Request
        if (!empty($_POST['Request'])) {
            // Check connection

            // prepare and bind statment
            $stmt = $conn->prepare("INSERT INTO `frequests` (`ID`,`Project ID`,`Friend ID`,`Accepted`,`Date`, `Message`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiiss", $request, $id, $idf, $accepted, $date,
                    $message
            );

            // set parameters and execute
            $request = "NULL";
            $id = $_SESSION['id'];
            $idf = $_POST['idf'];
            $accepted = 0;
            $date = date("Y-m-d");
            $message = "You have a friend request from " . $_SESSION["firstName"] . " " . $_SESSION["lastName"];
            $stmt->execute();

            $conn->close();
            
            echo "Request Sent! <br>";
        }

        // Submission for Friend Request
        // Accepting
        // MySQLi Object-oriented
        else if (!empty($_POST['Accept'])) {

            $idf = $_POST['idf'];
            $id = $_SESSION['id'];

            $sql = "SELECT * FROM project WHERE ID = '$idf'";
            $result = $conn->query($sql);
            $person = $result->fetch_assoc();

            $sql2 = "SELECT * FROM friends WHERE `Friend ID` = '$idf' AND `Project ID` = '$id' ";
            $result2 = $conn->query($sql2);
            $person2 = $result2->num_rows;
            
            $sql3 = "SELECT * FROM friends WHERE `Project ID` = '$idf' AND `Friend ID` = '$id'";
            $result3 = $conn->query($sql3);
            $person3 = $result3->num_rows;

            if ($person2 !=0 OR $person3 != 0) {
                echo "They are already a friend<br><br>";
            } else {
                // prepare and bind statment
                $stmt = $conn->prepare("INSERT INTO `friends` (`Transaction`,`Project ID`,`First Name`,`Last Name`,`Friend ID`,`Date`, `Friend First Name`,`Friend Last Name`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sississs", $transaction, $id, $firstName,
                        $lastName, $idF, $date, $firstNameF, $lastNameF
                );

                // set parameters and execute
                $transaction = "NULL";
                $id = $_SESSION["id"];
                $firstName = $_SESSION["firstName"];
                $lastName = $_SESSION["lastName"];
                $idF = $_POST["idf"];
                $date = date("Y-m-d");
                $firstNameF = $person["First Name"];
                $lastNameF = $person["Last Name"];
                $stmt->execute();
                $stmt->close();

                echo "Friend Added Successfully <br><br>";
                
                $sql = "DELETE FROM `frequests` WHERE `Friend ID` = '$id'";
            $conn->query($sql);
            }
            $conn->close();
        } 
        
        
        // Rejecting
        // MySQLi Object-oriented
        else {
  
            $id = $_SESSION['id'];
            $sql = "DELETE FROM `frequests` WHERE `Friend ID` = '$id'";
            $conn->query($sql);
            $conn->close();
            
            
        } 
        
        echo "Return to the <a href='listDisplay.php'>user's list</a> or your <a href='profile.php'>profile</a>.";
        ?>
    </body>
</html>
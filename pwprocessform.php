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
        if (!empty($_POST['passcode']) && !empty($_POST['email'])) {
            require "networkValidation.php";

            // "Connected successfully for CST8257 <br>";
            // prepare and bind statment
            $stmt = $conn->prepare("INSERT INTO `project` (`ID`,`First Name`,`Last Name`,`Email`,`Password`) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $id, $firstName, $lastName, $email,
                    $hashedPassword
            );

            // set parameters and execute
            $id = "NULL";
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $hashedPassword = hash("sha256", $_POST['passcode']);
            $stmt->execute();

            $_SESSION["email"] = $_POST['email'];
            $_SESSION["lastName"] = $_POST['lastName'];
            $_SESSION["firstName"] = $_POST['firstName'];

            // "New record created successfully in the books table in the cst8257 Obj_DB <br>";
            $stmt->close();
            $conn->close();

            echo "You have made an account! <br> Please <a href='LogIn.php'>Log In</a> to access those pictures and friends!";
        }


        echo "<br><br>";

        if (!empty($_POST['email']) && !empty($_POST['pass'])) {
            echo "Authenticate Password: <br>";
            require "networkValidation.php";

            $id = "NULL";
            $passcode = hash("sha256", $_POST['pass']);
            $email = $_POST['email'];

            //get the hash of the user entered password
            $selectUser = "SELECT * FROM project WHERE Email = '$email' ";
            $row = $conn->query($selectUser);
            $result = $row->fetch_assoc();

            if ($result == 0){
                echo "Incorrect Email or Password. Please <a href='LogIn.php'>Try Again</a>";
            } else if ($passcode === $result["Password"]) {
                $_SESSION["id"] = $result['ID'];
                $_SESSION["email"] = $result['Email'];
                $_SESSION["firstName"] = $result['First Name'];
                $_SESSION["lastName"] = $result['Last Name'];
                header("location:profile.php");
            } else {
                echo "Failed";
            }

            $conn->close();
        }
        ?>

    </body>
</html>
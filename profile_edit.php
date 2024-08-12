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
        $fnameErr = $lnameErr = "";
        $firstName = $lastName = "";
        $servername = "localhost";
        $username = "CST8257";
        $password = "cakeall";
        $dbname = "mydbsqliobj";

        $conn = new mysqli($servername, $username, $password, $dbname);
        ?>

        <h3>Edit Your Profile</h3>
        <form method ="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            First Name: <input type="text" name="firstName" value="<?php echo $firstName; ?>"> <span class="error"><?php echo$fnameErr; ?> </span> <br><br>
            Last Name: <input type="text" name="lastName" value="<?php echo $lastName; ?>"> <span class="error"><?php echo $lnameErr; ?> </span> <br><br>
            <input type="submit" name="submit" value="Submit" >
        </form>

        <?PHP
        // Form Validation
        //define variables and set to empty values
        if (!empty($_POST['lastName']) OR!empty($_POST['firstName'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_POST["firstName"])) {
                    $firstName = test_input($_POST["firstName"]);
                    //check if price only contains digits
                    if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
                        $fnameErr = "One or more letters, - or '";
                    }
                }

                if (!empty($_POST["lastName"])) {
                    $lastName = test_input($_POST["lastName"]);
                    //check if price only contains digits
                    if (!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
                        $lnameErr = "One or more letters, - or '";
                    }
                }

                if ($fnameErr == "" && $lnameErr == "") {
                    echo "Submitted Successfully";
                } else {
                    echo "<span class='error'>One or more letters, - or '</span>";
                }
            }
        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (!empty($_POST['firstName']) && !empty($_POST['lastName'])) {
            // MySQLi Object-oriented
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // set parameters and execute
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $id = $_SESSION["id"];

            // "Connected successfully for CST8257 <br>";
            // prepare and bind statment
            $sql = "UPDATE project SET `First Name` = '$firstName' ,`Last Name` = '$lastName' WHERE ID = '$id' ";
            $conn->query($sql);

            $_SESSION["firstName"] = $firstName;
            $_SESSION["lastName"] = $lastName;
            $conn->close();
            // End of server connection
        }

        echo "<br><br>";
        $passcode = $passcodeErr = "";
        ?>

        <h3>Change Your Password</h3>
        <form method ="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            Password: <input type="text" name="passcode" value="<?php echo $passcode; ?>"> <span class="error"><?php echo $passcodeErr; ?> </span> <br><br>
            <input type="submit" name="submit" value="Submit" >
        </form>

        <?PHP
        // Form Validation
        //define variables and set to empty values
        if (!empty($_POST['passcode'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_POST["passcode"])) {
                    $passcode = test_input($_POST["passcode"]);
                    //check if price only contains digits
                    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/",
                                    $passcode
                            )) {
                        $passcodeErr = "Minimum eight characters, at least one letter, one number and one special character";
                    }
                }

                if ($passcodeErr == "") {
                    echo "Submitted Successfully";
                } else if ($passcode == "") {
                    echo "<span class='error'>Not Blank</span>";
                } else {
                    echo "<span class='error'>$passcodeErr</span>";
                }
            }
        }

        if (!empty($_POST['passcode'])) {
            // MySQLi Object-oriented
            // Check connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $id = $_SESSION['id'];
            $pass = hash("sha256", $_POST['passcode']);

            // "Connected successfully for CST8257 <br>";
            // prepare and bind statment
            $sql2 = "UPDATE project SET `Password` = '$pass' WHERE ID = '$id' ";
            $conn->query($sql2);

            $conn->close();
            // End of server connection
        }

        echo "<br><br>";
        $email = $emailErr = "";
        ?>

        <h3>Change Your Email</h3>
        <form method ="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            Email: <input type="text" name="email" value="<?php echo $email; ?>"> <span class="error"><?php echo $emailErr; ?> </span> <br><br>
            <input type="submit" name="submit" value="Submit" >
        </form>

        <?PHP
        // Form Validation
        //define variables and set to empty values
        if (!empty($_POST['email'])) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["email"])) {
                    $emailErr = "E-mail is required";
                } else {
                    $email = test_input($_POST["email"]);
                    //check if email address is well formed
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }

                if ($emailErr == "") {
                    echo "Submitted Successfully";
                } else {
                    echo "<span class='error'>Invalid email format</span>";
                }
            }
        }

        if (!empty($_POST['email'])) {
            // MySQLi Object-oriented
            // Check connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $id = $_SESSION['id'];

            // "Connected successfully for CST8257 <br>";
            // prepare and bind statment
            $sql3 = "UPDATE `project` SET `Email` = '$email' WHERE ID = '$id' ";
            $conn->query($sql3);

            $_SESSION['email'] = $email;
            $conn->close();
            // End of server connection
        } 

        echo "<br><br><br><br> Return to your <a href='profile.php'>profile</a>";
        ?>


    </body>
</html>
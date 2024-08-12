<!DOCTYPE html>
<html>
    <header>
        <style>
            .error {
                color: #FF0000;
            }
        </style>
    </header>
    <body>
        <?php
        $fnameErr = $lnameErr = $passcodeErr = $emailErr = "";
        $firstName = $lastName = $passcode = $email = "";

        // Form Validation
        //define variables and set to empty values

        if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
            if (empty($_POST["firstName"])) {
                $fnameErr = "";
            } else {
                $firstName = test_input($_POST["firstName"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
                    $fnameErr = "Only letters, -, ' and white spaces are allowed";
                }
            }

            if (empty($_POST["lastName"])) {
                $lnameErr = "";
            } else {
                $lastName = test_input($_POST["lastName"]);
                // check if name only contains letters and whitespace
                if (!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
                    $lnameErr = "Only letters, -, ' and white spaces are allowed";
                }
            }

            if (empty($_POST["passcode"])) {
                $passcodeErr = "Password is required";
            } else {
                $passcode = test_input($_POST["passcode"]);
                //check if price only contains digits
                if (!preg_match("/^[a-zA-Z-' ]*$/", $passcode)) {
                    $passcodeErr = "One or more characters";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "E-mail is required";
            } else {
                $email = test_input($_POST["email"]);
                //check if email address is well formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

        }

        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>

        <h2>Sign Up Form</h2>
        <form method ="post" action="pwprocessform.php">
            <p><span class="error">* required field</span></p>
            First Name: <input type="text" name="firstName" value="<?php echo $firstName; ?>"> <span class="error"> <?php echo $fnameErr; ?> </span> <br><br>
            Last Name: <input type="text" name="lastName" value="<?php echo $lastName; ?>"> <span class="error"> <?php echo $lnameErr; ?> </span> <br><br>
            E-mail: <input type="text" name="email" value="<?php echo $email; ?>" required> <span class="error">* <?php echo $emailErr; ?> </span> <br><br>
            Password: <input type="text" name="passcode" value="<?php echo $passcode; ?>" required> <span class="error">* <?php echo $passcodeErr; ?> </span> <br><br>
            <input type="submit" name="submit" value="Submit" >
        </form>

    </body>
</html>

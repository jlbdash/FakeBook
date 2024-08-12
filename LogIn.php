<?php
// session start
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <style>
            .error {
                color: #FF0000;
            }
        </style>
    </head>
    <body>
        <?php
        $passErr = $emailErr = "";
        $pass = $email = "";

        ?>

        <h2>Log In Form</h2>
        <form method ="post" action="pwprocessform.php">
            <p><span class="error">* required field</span></p>
            E-mail: <input type="text" name="email" value="<?php echo $email; ?>"> <span class="error" required>* <?php echo $emailErr; ?> </span> <br><br>
            Password: <input type="text" name="pass" value="<?php echo $pass; ?>"> <span class="error" required>* <?php echo $passErr; ?> </span> <br><br>
            <input type="submit" name="submit" value="Submit">
        </form>

    </body>
</html>

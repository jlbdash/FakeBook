<?php
// session start
session_start();

// Combining cookie and session
if ($_SESSION['id']) {
    setcookie('id', $_SESSION['id'], time() + (60 * 60 * 24 * 7), "/");
}
if (isset($_COOKIE['id]'])) {
    $id = $_COOKIE['id'];
} else if (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = "0";
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="overallCascadeStyleSheet.css">
</head>

<body>
    <?PHP
    if (!empty($_SESSION["firstName"]) && !empty($_SESSION["lastName"])) {
        echo "Your name: " . $_SESSION["firstName"] . " " . $_SESSION["lastName"] . "<br>";
    } else {
        echo "Show off your name to get some friends! ";
    }
    echo "Edit your profile <a href='profile_edit.php'>here</a>";
    echo "<br><br>";
    echo "<div class='box'>Check out a photo gallery  <a href='gallery.php'>here</a> </div> <br> or see <a href='listDisplay.php'>all the users</a> to find your friends.";
    echo "<br><br>";
    echo "Speaking of which, you have friends! Look at them all: ";
    echo "<br>";

    // Server Connection
    
    // Finding Friends
    // MySQLi Object-oriented
    require "networkValidation.php";

    // set parameters and execute
    $id = $_SESSION['id'];
    $friend = "SELECT * FROM friends WHERE `Project ID` = '$id' OR `Friend ID` = '$id' ";
    $friendly = $conn->query($friend);

    if ($friendly->num_rows > 0) {
        // output data of each row
        while ($friender = $friendly->fetch_assoc()) {
            if ($id == $friender['Project ID']) {
                echo "- Name: " . $friender["Friend First Name"] . " " . $friender["Friend Last Name"] . "<br>";
            } else {
                echo "- Name: " . $friender["First Name"] . " " . $friender["Last Name"] . "<br>";
            }
        }
    } else {
        echo "0 results";
    }

    $conn->close();

    //
    // Friend Requests!
    // MySQLi Object-oriented
    // Check connection
    require "networkValidation.php";

    // find Request and Sender
    $id = $_SESSION['id'];
    $request = "SELECT * FROM frequests WHERE `Friend ID` = '$id' ";
    $friends = $conn->query($request);

    echo "<form method ='post' action='addfriendsform.php'>";
    if ($friends->num_rows > 0) {
        while ($friend = $friends->fetch_assoc()) {
            $idf = $friend["Project ID"];
            echo "<h4>You have Friend Requests!</h4>";
            echo $friend['Message'] . " " . "<input type='hidden' id='idf' name='idf' value='$idf'>&ensp;
  <input type='submit' name='Accept' value='Accept'> &nbsp; <input type='submit' name='Reject' value='Reject'>
</form>" . "<br>";
        }
    } else {
        echo "</form>";
    }

    $conn->close();

    // Messages from Friends
    echo "<br>
        <div class='box'> <h4> Messages for your Friends!</h4>";

    function checkFriend()
    {
        // Server Connection
        require "networkValidation.php";

        // find Request and Sender
        $id = $_SESSION['id'];
        $request = "SELECT * FROM friends WHERE (`Friend ID` = '$id' OR `Project ID` = '$id')";
        $friends = $conn->query($request);

        $sql = "SELECT * FROM friends WHERE `Project ID` = '$id' ";
        $result = $conn->query($sql);

        $sql2 = "SELECT * FROM friends WHERE `Friend ID` = '$id' ";
        $result2 = $conn->query($sql2);

        while ($friend = $friends->fetch_assoc()) {
            if ($friend["Project ID"] == $id) {
                $idf = $result->fetch_assoc()["Friend ID"];

                $sql2 = "SELECT * FROM project WHERE ID = '$idf' ";
                $person = $conn->query($sql2);
                $person = $person->fetch_assoc();
                $name = $person["First Name"] . " " . $person["Last Name"];
            } else {
                $idf = $result2->fetch_assoc()["Project ID"];

                $sql2 = "SELECT * FROM project WHERE ID = '$idf' ";
                $person = $conn->query($sql2);
                $person = $person->fetch_assoc();
                $name = $person["First Name"] . " " . $person["Last Name"];
            }
            echo "<option value='$idf'> $name</option>";
        }
        $conn->close();
    }
    ?>


    <form method="post" action="message.php">
        <label for='friends'>Choose a friend: </label> &nbsp;<select id='friends' name='friends'>
            <option value=''> Choose a Friend</option>
            <?php checkFriend(); ?>
        </select>
        <br>
        <label>Message: </label> <br>
        <textarea name="message" rows="5" cols="44"></textarea>
        <br>
        <input type="submit" name="submit" value="Submit">
    </form>
    </div>
    <br><br>
    <h4> Read Your Messages to and from Friends: </h4>

    <form method="post" action="" <?php echo $_SERVER['PHP_SELF']; ?>">
        <label for='friendly'>Choose a friend to sort: </label> &nbsp;<select id='friendly' name='friendly'>
            <option value=''> All </option>
            <?php checkFriend(); ?>
        </select><input type="submit" name="friend" value="Submit">
    </form> <br>
    <?php
    //
// Reading the Messages
require "networkValidation.php";
    // Find Message
    $sql = "SELECT * FROM message";
    $row = mysqli_Query($conn, $sql);
    if (!empty($_POST['friendly'])) {
        // Find My Friend's Name
        $idf = $_POST['friendly'];
        $selectUser = "SELECT * FROM project WHERE `ID` = '$idf'";
        $row2 = $conn->query($selectUser);
        $result = $row2->fetch_assoc();
    } else {
        echo "";
    }

    // Find My Name
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];

    if (mysqli_num_rows($row) > 0) {
        //output data of each row
        while ($rows = $row->fetch_assoc()) {
            if (empty($_POST['friendly'])) {
                // Session ID
                if ($rows['ID'] == $_SESSION['id']) {
                    echo $firstName . " " . $lastName . ":  " . $rows["Message"] . "<br><br>";
                } elseif ($rows['Friend ID'] == $_SESSION['id']) {
                    $idf2 = $rows['ID'];
                    // Find Friend Name
                    $selectUser2 = "SELECT * FROM project WHERE `ID` = '$idf2'";
                    $row2 = $conn->query($selectUser2);
                    $result2 = $row2->fetch_assoc();

                    //Friend ID
                    echo $result2["First Name"] . " " . $result2["Last Name"] . ":  " . $rows["Message"] . "<br><br>";
                }
            } else if (($rows['Friend ID'] == $idf) or ($rows['ID'] == $idf)) {
                // Session ID
                if ($rows['Friend ID'] == $id) {
                    $idf2 = $rows['ID'];
                    // Find Friend Name
                    $selectUser2 = "SELECT * FROM project WHERE `ID` = '$idf2'";
                    $row2 = $conn->query($selectUser2);
                    $result2 = $row2->fetch_assoc();

                    //Session ID
                    echo $result2["First Name"] . " " . $result2["Last Name"] . ":  " . $rows["Message"] . "<br><br>";
                } else if (($rows['Friend ID'] == $_SESSION['id']) or ($rows['ID'] == $_SESSION['id'])) {
                    // Find Friend Name
                    $selectUser2 = "SELECT * FROM project WHERE `ID` = '$idf'";
                    $row2 = $conn->query($selectUser2);
                    $result2 = $row2->fetch_assoc();

                    //Friend ID
                    echo $firstName . " " . $lastName . ": " . $rows["Message"] . "<br><br>";
                }
            }
        }
    }

    mysqli_close($conn);
    ?>
    <br><br>
    <form action="logoff.php">
        <input type="submit" name="logoff" value="Log Off">
    </form>

</body>

</html>
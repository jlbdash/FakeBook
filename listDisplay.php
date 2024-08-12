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
        echo "<h3>See All Users</h3>";
        echo "Return your <a href='profile.php'>profile</a> <br><br><br>";

        // Find the User
        require "networkValidation.php";

        try {
            // Find Person
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM project WHERE ID != '$id' ";
            $result = $conn->query($sql);

            // List of Users
            if ($result->num_rows > 0) {
                echo "<form method ='post' action='addfriendsform.php'>";
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    $idf = $row['ID'];

                    // Find Friends Connections
                    $sql2 = "SELECT * FROM friends WHERE (`Friend ID` = '$id' AND `Project ID` = '$idf') OR (`Friend ID` = '$idf' AND `Project ID` = '$id')";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows != 0) {
                        $result2 = $result2->fetch_assoc();
                        $trial = $result2['Friend ID'];
                        $trial2 = $result2['Project ID'];
                    } else {
                        $trial = 0;
                        $trial2 = 0;
                    }


                    // Find Connections
                    $sql3 = "SELECT * FROM frequests WHERE (`Project ID` = '$id') OR (`Friend ID` = '$id') ";
                    $result3 = $conn->query($sql3);
                    if ($result3->num_rows != 0) {
                        $result3 = $result3->fetch_assoc();
                        $trial3 = $result3['Friend ID'];
                        $trial4 = $result3['Project ID'];
                    } else {
                        $trial3 = 0;
                        $trial4 = 0;
                    }

                    echo "<div class='formbox'> Name: " . $row["First Name"] . " " . $row["Last Name"] . "&ensp;" . "<input type='hidden' id='idf' name='idf' value='$idf'>";
                    if ($trial !== $row["ID"] AND $trial2 !== $row["ID"] AND $trial3 !== $row["ID"] AND $trial4 !== $row["ID"]) {
                        echo "<input type='submit' name='Request' value='Add friend'>";
                    } else {
                        echo "";
                    }
                    echo "</form></div>" . "<br>";
                }
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }

        $conn = null;
        ?>

    </body>
</html>
<!DOCTYPE html>
<html>

<head>
    <title>6e2de61f</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>

<?php 
    require "pdo.php";

    session_start();

?>

<body>
    <h1>Sujal Jain</h1>

    <?php
        if (isset($_SESSION['success'])) {
            echo "<p style='color: green'>".$_SESSION['success']."</p>";
            unset($_SESSION['success']);
        }
        if ( isset($_SESSION['name'])) {
            echo "<a href='logout.php'>Logout</a><br />";
           
        } else {
            echo "<a href='login.php'>Please log in</a>";
        }

        $statement = $pdo->query("SELECT * FROM profile");

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row != false) {

            $statement = $pdo->query("SELECT * FROM profile");
            
            echo "<table border='1'>
            <tbody>
            <tr> 
            <th>Name</th>
            <th>Headline</th>";

            if (isset($_SESSION['name'])) {
                echo "<th>Action</th>";
            }
            
            echo "</tr>";
            while ( $row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".$row['first_name']." ".$row['last_name']."</td>";
                echo "<td>".$row['headline']."</td>";
                if (isset($_SESSION['name'])) {
                    echo "<td>
                        <a href='edit.php?profile_id=".$row['profile_id']."'>Edit </a>
                        <a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a>
                    </td>";
                }
                echo "</tr>";
            }
            echo "</tbody></table>";
        }
        

        if ( isset($_SESSION['name'])) {
            echo "<a href='add.php'>Add New Entry</a>";
        } 

    ?>
</body>

</html>
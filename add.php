<?php
    require "pdo.php";
    session_start();

    if (!isset($_SESSION['name'])) {
        die("ACCESS DENIED");
    }

    if (isset($_POST['cancel'])) {
        header("Location: index.php");
        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])  ) {
        if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
            $_SESSION['error'] = "All fields are required";
            header("Location: add.php");
            return;
        }

        if (strpos($_POST['email'], "@") === false) {
            $_SESSION['error'] = "Email address must contain @";
            header("Location: add.php");
            return;
        }

        $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":uid" => $_SESSION['user_id'],
            ":fn" => $_POST['first_name'],
            ":ln" => $_POST['last_name'],
            ":em" => $_POST['email'],
            ":he" => $_POST['headline'],
            ":su" => $_POST['summary']
        ));

        $_SESSION['success'] = "Profile added";
        header("Location: index.php");
        return;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Profile</title>
</head>
<body>

    <h1>Adding Profile for <?php if (isset($_SESSION['name'])) {
        echo $_SESSION['name']; 
    } ?> </h1>

    <?php 
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
    ?>

    <form method="POST">
        <p>
            First Name: <input type="text" name="first_name" />
        </p>
        <p>
            Last Name: <input type="text" name="last_name" />
        </p>
        <p>
            Email: <input type="text" name="email" />
        </p>
        <p>
            Headline: <input type="text" name="headline" />
        </p>
        <p>
            Summary: <textarea name="summary" rows="8" cols="80"></textarea>
        </p>
        <input type="submit" name="submit" value="Add" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
</body>

</html>
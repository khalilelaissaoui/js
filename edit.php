<?php 

    require_once "pdo.php";
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("ACCESS DENIED");
    }

    if (isset($_POST['cancel'])) {
        header("Location: index.php");
        return;
    }

    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])  ) {
        if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['headline']) || empty($_POST['summary'])) {
            $_SESSION['error'] = "All fields are required";
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return;
        }

        if (strpos($_POST['email'], "@") == false) {
            $_SESSION['error'] = "Invalid email address";
            header("Location: edit.php?profile_id=".$_GET['profile_id']);
            return; 
        }

        $sql = "UPDATE profile SET first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su 
                WHERE profile_id = :pi AND user_id = :uid";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":fn" => $_POST['first_name'],
            ":ln" => $_POST['last_name'],
            ":em" => $_POST['email'],
            ":he" => $_POST['headline'],
            ":su" => $_POST['summary'],
            ":pi" => $_GET['profile_id'],
            ":uid" => $_SESSION['user_id']
        ));

        $_SESSION['success'] = "Profile updated";
        header("Location: index.php");
        return;
    }
    
    if (!isset($_GET['profile_id'])) {
        $_SESSION['error'] = "Could not load profile";
        header("Location: index.php");
        return;
    }
    
    $statement = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
    
    $statement->execute(array(
        ":profile_id" => $_GET["profile_id"],
    ));

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row == false) {
        $_SESSION['error'] = "Could not load profile";
        header("Location: index.php");
        return;
    }

    $first_name = htmlentities($row["first_name"]);
    $last_name = htmlentities($row["last_name"]);
    $email = htmlentities($row["email"]);
    $headline = htmlentities($row["headline"]);
    $summary = htmlentities($row["summary"]);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>

    <h1>Editing Profile for <?php if (isset($_SESSION['name'])) {
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
            First Name: <input type="text" name="first_name" value="<?= $first_name; ?>" />
        </p>
        <p>
            Last Name: <input type="text" name="last_name" value="<?= $last_name; ?>" />
        </p>
        <p>
            Email: <input type="text" name="email" value="<?= $email; ?>" />
        </p>
        <p>
            Headline: <input type="text" name="headline" value="<?= $headline; ?>" />
        </p>
        <p>
            Summary: <textarea name="summary" rows="8" cols="80"><?= $summary; ?></textarea>
        </p>
        <input type="submit" name="submit" value="Save" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
</body>

</html>
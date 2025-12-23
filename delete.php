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

    if (isset($_POST['delete']) && isset($_POST['profile_id'])) {
        $sql = "DELETE FROM profile WHERE profile_id = :pi AND user_id = :uid";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":pi" => $_POST['profile_id'],
            ":uid" => $_SESSION['user_id']
        ));

        $_SESSION['success'] = "Profile deleted";
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
    <title>Delete Profile</title>
</head>
<body>

    <h1>Deleting Profile</h1>

    <?php 
        if (isset($_SESSION['error'])) {
            echo "<p style='color: red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
    ?>

    <p>
        First Name: <?php echo $first_name; ?>
    </p>
    <p>
        Last Name: <?php echo $last_name; ?>
    </p>

    <form method="POST">
        <p>
            <input type="hidden" name="profile_id" value="<?php echo $_GET['profile_id']; ?>" />
        </p>
        <input type="submit" name="delete" value="Delete" />
        <input type="submit" name="cancel" value="Cancel" />
    </form>
</body>

</html>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Styled Table with Update and Delete</title>
<link rel="stylesheet" href="display.css">
</head>
<body>
    <table border="1">
        <tr>
            <th>id</th>
            <th>full_name</th>
            <th>email</th>
            <th>Actions</th>
        </tr>
        <?php
        // Connect to the database
        $conn = mysqli_connect("localhost", "root", "", "login_register");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle DELETE request
        if (isset($_GET['delete'])) {
            $delete_id = $_GET['delete'];
            $delete_sql = "DELETE FROM users WHERE id = $delete_id";
            if (mysqli_query($conn, $delete_sql)) {
                echo "<script>alert('Record Deleted Successfully!');</script>";
                echo "<script>window.location.href = '".$_SERVER['PHP_SELF']."';</script>";
            }
        }

        // Handle UPDATE request
        if (isset($_POST['update'])) {
            $update_id = $_POST['id'];
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];

            $update_sql = "UPDATE users SET full_name='$full_name', email='$email' WHERE id=$update_id";
            if (mysqli_query($conn, $update_sql)) {
                echo "<script>alert('Record Updated Successfully!');</script>";
                echo "<script>window.location.href = '".$_SERVER['PHP_SELF']."';</script>";
            }
        }

        // Fetch Data to Display in Table
        $sql = "SELECT id, full_name, email FROM users";
        $result = $conn->query($sql);

        // Display data in table rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["full_name"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>
                    <a href='?delete=" . $row["id"] . "' onclick=\"return confirm('Are you sure you want to delete this record?');\">Delete</a>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='id' value='" . $row["id"] . "'>
                        <input type='text' name='full_name' placeholder='Update Full Name' value='" . $row["full_name"] . "'>
                        <input type='text' name='email' placeholder='Update Email' value='" . $row["email"] . "'>
                        <button type='submit' name='update'>Update</button>
                    </form>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 results</td></tr>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </table>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juice Management</title>
    <style>
        body {
            background-color: #9dd3ffff;
            font-family: Arial, sans-serif;
        }
        form, table {
            background-color: #a3c486ff;
            padding: 20px;
            border-radius: 15px;
            margin: 20px auto;
            width: 350px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select {
            width: 90%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 9px;
            border: 1px solid #302929ff;
        }
        button {
            padding: 8px 17px;
            margin: 10px 5px 0 0;
            border: none;
            border-radius: 8px;
            background-color: #487b5fff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #040b12ff;
        }
        table {
            width: 90%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #302929ff;
            padding: 8px;
            text-align: center;
        }
        h1 {
            text-align: center;
            color: #201f1fff;
        }
    </style>
</head>
<body>

<?php
$connect = mysqli_connect('localhost', 'root', '', 'juice_bar');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables
$jname = $price = $quantity = $quality = $expired_date = "";
$edit_id = null;

// -------- POST Handlers (Add, Update, Delete) --------




// Add Juice
if (isset($_POST['add'])) {
    $jname = $_POST['jname'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $quality = $_POST['quality'];
    $expired_date = $_POST['expired_date'];

    $query = "INSERT INTO juices (jname, price, quantity, quality, expired_date)
              VALUES ('$jname', '$price', '$quantity', '$quality', '$expired_date')";
    mysqli_query($connect, $query);

    // Redirect to prevent resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}



// Update Juice
if (isset($_POST['update'])) {
    $edit_id = $_POST['edit_id'];
    $jname = $_POST['jname'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $quality = $_POST['quality'];
    $expired_date = $_POST['expired_date'];

    $query = "UPDATE juices SET 
                jname='$jname', 
                price='$price', 
                quantity='$quantity', 
                quality='$quality', 
                expired_date='$expired_date' 
              WHERE jid=$edit_id";
    mysqli_query($connect, $query);

    // Redirect to prevent resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}




// Delete Juice
if (isset($_POST['delete'])) {
    $id = $_POST['jid'];
    $query = "DELETE FROM juices WHERE jid=$id";
    mysqli_query($connect, $query);

    // Redirect to prevent resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}




// -------- Load data for editing --------
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $result = mysqli_query($connect, "SELECT * FROM juices WHERE jid=$edit_id");
    if ($row = mysqli_fetch_assoc($result)) {
        $jname = $row['jname'];
        $price = $row['price'];
        $quantity = $row['quantity'];
        $quality = $row['quality'];
        $expired_date = $row['expired_date'];
    }
}

// Fetch all juices for display
$result = mysqli_query($connect, "SELECT * FROM juices");
?>

<h1>Juice Management</h1>

<!-- Juice Form -->
<form action="#" method="POST">
    <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">

    <label for="juice_name">Juice Name:</label>
    <input type="text" id="juice_name" name="jname" required value="<?php echo $jname; ?>"><br><br>

    <label for="price">Price ($):</label>
    <input type="number" id="price" name="price" required value="<?php echo $price; ?>"><br><br>

    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required value="<?php echo $quantity; ?>"><br><br>

    <label for="quality">Quality:</label>
    <select id="quality" name="quality">
        <option value="Excellent" <?php if($quality=="Excellent") echo "selected";?>>Excellent</option>
        <option value="Good" <?php if($quality=="Good") echo "selected";?>>Good</option>
        <option value="Average" <?php if($quality=="Average") echo "selected";?>>Average</option>
        <option value="Poor" <?php if($quality=="Poor") echo "selected";?>>Poor</option>
    </select><br><br>

    <label for="expired_date">Expired Date:</label>
    <input type="date" id="expired_date" name="expired_date" required value="<?php echo $expired_date; ?>"><br><br>

    <?php if($edit_id): ?>
        <button type="submit" name="update">Update Juice</button>
    <?php else: ?>
        <button type="submit" name="add">Add Juice</button>
    <?php endif; ?>
</form>

<!-- Juice Table -->
<?php if(mysqli_num_rows($result) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Quality</th>
            <th>Expired</th>
            <th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['jid']; ?></td>
                <td><?php echo $row['jname']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['quality']; ?></td>
                <td><?php echo $row['expired_date']; ?></td>
                <td>
                    <a href="?edit=<?php echo $row['jid'];?>"><button type="button">Update</button></a>
                        <form method="POST" style="display:inline;">
                        <input type="hidden" name="jid" value="<?php echo $row['jid']; ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">No juices found.</p>
<?php endif; ?>
</body>
</html>

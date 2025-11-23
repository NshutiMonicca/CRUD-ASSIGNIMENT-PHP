<?php
// juices.php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}


include 'connection.php'; // expects $conn (mysqli)



$email=$_SESSION['email'];
// initialize
$jname = $price = $quantity = $quality = $expired_date = "";
$edit_id = null;
$errors = [];

// -------- Handle Add (POST) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $jname = trim($_POST['jname'] ?? '');
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $quality = $_POST['quality'] ?? 'Average';
    $expired_date = $_POST['expired_date'] ?? '';

    if ($jname === '') $errors[] = "Juice name is required.";
    if ($expired_date === '') $errors[] = "Expired date is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO juices (jname, price, quantity, quality, expired_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sddss", $jname, $price, $quantity, $quality, $expired_date);
        $stmt->execute();
        $stmt->close();

        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// -------- Handle Update (POST) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $edit_id = intval($_POST['edit_id'] ?? 0);
    $jname = trim($_POST['jname'] ?? '');
    $price = $_POST['price'] ?? 0;
    $quantity = $_POST['quantity'] ?? 0;
    $quality = $_POST['quality'] ?? 'Average';
    $expired_date = $_POST['expired_date'] ?? '';

    if ($edit_id <= 0) $errors[] = "Invalid record id.";
    if ($jname === '') $errors[] = "Juice name is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE juices SET jname = ?, price = ?, quantity = ?, quality = ?, expired_date = ? WHERE jid = ?");
        $stmt->bind_param("sddssi", $jname, $price, $quantity, $quality, $expired_date, $edit_id);
        $stmt->execute();
        $stmt->close();

        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// -------- Handle Delete (POST) --------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $del_id = intval($_POST['delete_id'] ?? 0);
    if ($del_id > 0) {
        $stmt = $conn->prepare("DELETE FROM juices WHERE jid = ?");
        $stmt->bind_param("i", $del_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// -------- Load edit data (GET) --------
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    if ($edit_id > 0) {
        $stmt = $conn->prepare("SELECT jname, price, quantity, quality, expired_date FROM juices WHERE jid = ?");
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $stmt->bind_result($jname, $price, $quantity, $quality, $expired_date);
        $stmt->fetch();
        $stmt->close();
    } else {
        $edit_id = null;
    }
}

// -------- Fetch all juices --------
$juices = [];
$res = $conn->query("SELECT jid, jname, price, quantity, quality, expired_date FROM juices ORDER BY jid DESC");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $juices[] = $r;
    }
    $res->free();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Juice Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background-color: #9dd3ffff; font-family: Arial, sans-serif; }
        .container { max-width:900px; margin:20px auto; padding:10px; }
        .card { background:#a3c486ff; padding:20px; border-radius:15px; box-shadow:0 0 10px rgba(0,0,0,0.1); margin-bottom:20px; }
        input, select { width: 90%; padding:8px; margin-top:5px; border-radius:9px; border:1px solid #302929ff; }
        button, .btn { padding:8px 12px; border-radius:8px; border:none; cursor:pointer; background:#487b5fff; color:#fff; }
        button:hover, .btn:hover { background:#040b12ff; }
        table { width: 100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #302929ff; padding:8px; text-align:center; }
        .actions form { display:inline-block; margin:0 3px; }
        .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
        .logout-link { text-decoration:none; padding:8px 12px; background:#d9534f; color:#fff; border-radius:8px; }
    </style>
    <script>
        function confirmDelete(id, name) {
            if (confirm('Delete "' + name + '"? This cannot be undone.')) {
                // create and submit a form to POST delete
                const f = document.createElement('form');
                f.method = 'POST';
                f.action = '';
                const a = document.createElement('input');
                a.type = 'hidden'; a.name = 'action'; a.value = 'delete'; f.appendChild(a);
                const b = document.createElement('input');
                b.type = 'hidden'; b.name = 'delete_id'; b.value = id; f.appendChild(b);
                document.body.appendChild(f);
                f.submit();
            }
        }
    </script>
</head>
<body>
<div class="container">
    <div class="topbar">
        <h1 style="margin:0;">Juice Management</h1>
        <div>
            <span>Logged in as: <?php echo htmlspecialchars($_SESSION['email']); ?></span>
            &nbsp;
            <a class="logout-link" href="logout.php">Logout</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="card" style="background:#ffc7c7;">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="post" action="">
            <input type="hidden" name="edit_id" value="<?php echo $edit_id ? (int)$edit_id : ''; ?>">
            <label>Juice Name:</label><br>
            <input type="text" name="jname" required value="<?php echo htmlspecialchars($jname); ?>"><br><br>

            <label>Price ($):</label><br>
            <input type="number" step="0.01" name="price" required value="<?php echo htmlspecialchars($price); ?>"><br><br>

            <label>Quantity:</label><br>
            <input type="number" name="quantity" required value="<?php echo htmlspecialchars($quantity); ?>"><br><br>

            <label>Quality:</label><br>
            <select name="quality">
                <?php $qualities = ['Excellent','Good','Average','Poor']; ?>
                <?php foreach ($qualities as $q): ?>
                    <option value="<?php echo $q; ?>" <?php if ($quality === $q) echo 'selected'; ?>><?php echo $q; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Expired Date:</label><br>
            <input type="date" name="expired_date" required value="<?php echo htmlspecialchars($expired_date); ?>"><br><br>

            <?php if ($edit_id): ?>
                <input type="hidden" name="action" value="update">
                <button type="submit" class="btn">Update Juice</button>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn" style="background:#6c757d;">Cancel</a>
            <?php else: ?>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="btn">Add Juice</button>
            <?php endif; ?>
        </form>
    </div>

    <div class="card">
        <?php if (count($juices) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Price</th><th>Quantity</th><th>Quality</th><th>Expired</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($juices as $row): ?>
                        <tr>
                            <td><?php echo (int)$row['jid']; ?></td>
                            <td><?php echo htmlspecialchars($row['jname']); ?></td>
                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['quality']); ?></td>
                            <td><?php echo htmlspecialchars($row['expired_date']); ?></td>
                            <td class="actions">
                                <a href="?edit=<?php echo (int)$row['jid']; ?>"><button type="button" class="btn">Edit</button></a>
                                <button type="button" class="btn" style="background:#d9534f;" onclick="confirmDelete(<?php echo (int)$row['jid']; ?>, '<?php echo addslashes(htmlspecialchars($row['jname'])); ?>')">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center;">No juices found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

<?php
require_once 'user.php';
require_once '../db_module.php';

$eventid = isset($_GET['id']) ? $_GET['id'] : null;
$link = null;
taoKetNoi($link);

$query = "SELECT s.MaStartup, s.TenStartup
FROM startup s
WHERE MaStartup NOT IN (SELECT MaStartup FROM DangKy WHERE MaSuKien = $eventid)";

$result = chayTruyVanTraVeDL($link, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Startups</title>
    <style>
        .request-mng-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            margin-top: 30px;
            font-family: Roboto, sans-serif;
        }
        .request-mng-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .request-mng-container th, .request-mng-container td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .request-mng-container th {
            background-color: #f2f2f2;
        }
      
        .action {
    display: flex;
    margin-top: 30px;
    gap: 20px;
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }

.action .button {
    justify-content: center;
    width: 216px;
    border-radius: 5.067px;
    border-color: rgba(90, 99, 122, 1);
    border-style: solid;
    border-width: 1px;
    background-color: #244585;
    color: white;
    white-space: nowrap;
    padding: 12px 8px;
  }
    </style>
</head>
<body>

<div class='request-mng-container'>
    <h2>Thêm startup cho sự kiện</h2>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>Tên Startup</th>
                    <th>Mời tham dự</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if the query returned any results
                if (mysqli_num_rows($result) > 0) {
                    // Loop through each startup
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['TenStartup']) . "</td>";
                        echo "<td><input type='checkbox' name='selected_startups[]' value='" . htmlspecialchars($row['MaStartup']) . "'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No startups available to register.</td></tr>";
                }
                // Free memory and close connection
                giaiPhongBoNho($link, $result);
                ?>
            </tbody>
        </table>
        <div class="action">
            <input class = "button" type="submit" value="Xác nhận mời tham dự">
        </div>
    </form>
</div>

</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedStartups = isset($_POST['selected_startups']) ? $_POST['selected_startups'] : [];
    $maSuKien = $eventid; // Ensure $eventid is defined before use
    $tinhTrangXacNhan = 'Chưa xác nhận';
    // Check if any startups were selected
    if (!empty($selectedStartups)) {
        // Connect to the database
        $link = null;
        taoKetNoi($link);

        // Thêm selected startup
        foreach ($selectedStartups as $maStartup) {
            $sql = "INSERT INTO DangKy (MaStartup, TinhTrangXacNhan, MaSuKien) VALUES (?,?,?)";
            $stmt = mysqli_prepare($link, $sql);
        
            mysqli_stmt_bind_param($stmt, "isi", $maStartup, $tinhTrangXacNhan, $maSuKien);

            //Thực thi câu lệnh
            $insert_result = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        // Close the database connection
        giaiPhongBoNho($link, null);

        // Redirect or display a success message
        echo "Thêm startup thành công";
    } else {
        echo "No startups were selected.";
    }
} else {
    // Handle the case where the form is not submitted properly
    echo "";
}
?>
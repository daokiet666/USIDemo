<style>
    .request-mng-container {
        width: 100%;
        max-width: 1075px;
        margin: 0 auto;
        margin-top: 30px;
        font-family: Roboto, sans-serif;
    }

    .request-mng-container .heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 100px;
    }

    .request-mng-container .heading .title {
        color: #4F4F4F;
        font-size: 40px;
    }

    .request-mng-container .heading .button button {
        padding: 12px 14px;
        background-color: #EF6E35;
        color: white;
        outline: none;
        border-radius: 8px;
        border: none;
    }

    .request-mng-container .table-container {
        margin-top: 20px;
    }

    .request-mng-container table {
        width: 100%;
        border-collapse: collapse;
    }

    .request-mng-container th,
    td {
        padding: 8px;
        text-align: center;

    }

    .request-mng-container th:not(:last-child),
    td:not(:last-child) {


        border-right: 1px solid #ddd;
    }

    .request-mng-container th {
        background-color: #244585;
        color: white;
    }

    .request-mng-container tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .request-mng-container table .edit-btn,
    .request-mng-container table .delete-btn {
        padding: 6px 10px;
        margin-right: 5px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
        color: white;
    }

    .request-mng-container table .edit-btn {
        background-color: #244585;
        color: white;
        border: solid 1px #244585;
    }

    .request-mng-container table .delete-btn {
        background-color: white;
        color: #244585;
        border: solid 1px black;
    }

     /* Style for the overlay */
    .overlay {
            display: none; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            justify-content: center;
            align-items: center;
    }
        /* Style for the iframe container */
    .popup-content {
        width: 40%;
        height: 80%;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
}
        
    .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            color: #333;
    }
</style>

<?php
require_once 'user.php';


require_once '../db_module.php';

// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $startupid = $_GET['startupid'];
}

// Truy vấn để lấy dữ liệu từ bảng cities với phân trang
$query = "SELECT m.MaMucTieu, m.TenMucTieu, m.GiaTriMucTieu, m.GiaTri, m.MaStartup, s.TenStartup
FROM muctieu m 
RIGHT JOIN startup s ON m.MaStartup = s.MaStartup
WHERE s.MaStartup = $startupid";


$result = chayTruyVanTraVeDL($link, $query);

$table_body = "";

// Kiểm tra xem truy vấn có thành công không

    // Bắt đầu vòng lặp để xây dựng chuỗi HTML
    while ($row = mysqli_fetch_assoc($result)) {
        $table_body .= "<tr>";
        $table_body .= "<td>" . $row['TenMucTieu'] . "</td>";
        $table_body .= "<td>" . $row['GiaTriMucTieu'] . "</td>";
        $table_body .= "<td class='editable' contenteditable='true' data-id='" . $row['MaMucTieu'] . "'>" . htmlspecialchars($row['GiaTri']) . "</td>";
        if ($row['GiaTri']>=$row['GiaTriMucTieu']) {$complete = 'Hoàn thành';} else {$complete = 'Chưa hoàn thành';}
        $table_body .= "<td>" . $complete . "</td>";
        $table_body .= "</tr>";
        $name = $row['TenStartup'];
    }
    
// Giải phóng bộ nhớ sau khi sử dụng
giaiPhongBoNho($link, $result);

//xử lý update giá trị
if (isset($_POST['id']) && isset($_POST['value'])) {
    $id = $_POST['id'];
    $updatedvalue = $_POST['value'];
    taoKetNoi($link);

    // Prepare the SQL update query
    $query = "UPDATE muctieu SET GiaTri = ? WHERE MaMucTieu = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "si", $updatedvalue, $id);
    
    // Execute the query and return the result
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success_message'] = "Update thành công!";
    } else {
        $_SESSION['success_message'] = "Update thất bại!";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

?>

<div class='request-mng-container'>
    <div class='heading'>
        <div class='title'>
            Danh sách mục tiêu của <?php echo $name?>
        </div>
        <div class='button'>
            <button onclick='redirectToCreateRec(<?php echo $startupid; ?>)'>
                Tạo Mục tiêu mới
            </button>
        </div>
    </div>
    <div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>Tên mục tiêu</th>
                    <th>Giá trị mục tiêu</th>
                    <th>Giá trị</th>
                    <th>Hoàn thành</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $table_body; ?>
            </tbody>
        </table>
        <div>
        </div>
    </div>
    <div id="overlay" class="overlay">
        <div class="popup-content">
            <span class="close-btn" onclick="hidePopup()">&times;</span>
            <iframe id="php-frame" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>
<script>
    function redirectToCreateRec(id) {
        document.getElementById('php-frame').src = 'new-okr.php?startup-id='+id;
            
            // Display the overlay
        document.getElementById('overlay').style.display = 'flex';
    }

    //xử lý inline editing & ajax
    document.querySelectorAll('.editable').forEach(cell => {
    cell.addEventListener('blur', function() {
        var id = this.getAttribute('data-id');
        var updatedValue = this.textContent;

        // gửi giá trị tới server
        var xhr = new XMLHttpRequest();
        const responseText = 'Thành công';
        xhr.open('POST', 'okr-mgt.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // You can handle the response here
            }
        };
        xhr.send('id=' + id + '&value=' + encodeURIComponent(updatedValue));
    });
});
</script>


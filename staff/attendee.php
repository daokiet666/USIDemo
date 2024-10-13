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
    .request-mng-container table .view-btn {
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

    .request-mng-container table .view-btn {
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
<style>
    /* Include existing styles here */
</style>

<?php
require_once 'user.php';
require_once '../db_module.php';

$eventid = isset($_GET['id']) ? $_GET['id'] : null;

// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);

$query = "SELECT d.TinhTrangXacNhan, d.Checkin, d.SoLuongThamGia, d.MaStartup, d.MaSuKien, s.TenStartup
FROM DangKy d 
LEFT JOIN Startup s ON d.MaStartup = s.MaStartup
WHERE MaSuKien = $eventid";

$result = chayTruyVanTraVeDL($link, $query);

$table_body = "";

// Kiểm tra xem truy vấn có thành công không
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $table_body .= "<tr>";
        $table_body .= "<td>" . $row['TenStartup'] . "</td>";
        $table_body .= "<td>" . $row['SoLuongThamGia'] . "</td>";

        // 'Tình trạng xác nhận' checkbox
        $confirmChecked = ($row['TinhTrangXacNhan'] === 'Chưa xác nhận') ? "" : "checked disabled";
        $table_body .= "<td><input type='checkbox' onclick='confirmStatus(this, {$row['MaStartup']})' $confirmChecked></td>";


        // 'Checkin' checkbox
        $checkinChecked = $row['Checkin'] ? "checked disabled" : "";
        $table_body .= "<td><input type='checkbox' onclick='updateCheckinStatus(this, {$row['MaStartup']})' $checkinChecked></td>";

        $table_body .= "</tr>";
    }
} else {
    $table_body .= "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
}

// Giải phóng bộ nhớ sau khi sử dụng
giaiPhongBoNho($link, $result);
?>

<div class='request-mng-container'>
    <div class='heading'>
        <div class='title'>Danh sách startup tham dự</div>
        <div class='button'>
            <button onclick='showPopUp(<?php echo $eventid; ?>)'>Thêm startup tham dự</button>
        </div>
    </div>
    <div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>Startup</th>
                    <th>Số người tham dự</th>
                    <th>Tình trạng xác nhận</th>
                    <th>Checkin</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $table_body; ?>
            </tbody>
        </table>
    </div>
    <div id="overlay" class="overlay">
        <div class="popup-content">
            <span class="close-btn" onclick="hidePopup()">&times;</span>
            <iframe id="php-frame" src="" width="100%" height="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>

<script>
    function showPopUp(id) {
        document.getElementById('php-frame').src = 'add-attendee.php?id=' + id;
        document.getElementById('overlay').style.display = 'flex';
    }

    function hidePopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('php-frame').src = '';
    }

    // JavaScript function to handle 'Tình trạng xác nhận' checkbox change
    function confirmStatus(checkbox, startupId) {
        if (checkbox.checked) {
            if (confirm("Are you sure to confirm this status?")) {
                setTimeout(() => {
                    // Simulate server response
                    var response = { success: true };
                    if (response.success) {
                        checkbox.disabled = true;
                        alert("Tình trạng xác nhận updated successfully!");
                    } else {
                        alert("Failed to update the status. Try again.");
                        checkbox.checked = false;
                    }
                }, 500);
            } else {
                checkbox.checked = false;
            }
        }
    }

    // JavaScript function to handle 'Checkin' checkbox change
    function updateCheckinStatus(checkbox, startupId) {
        if (checkbox.checked) {
            if (confirm("Xác nhận checkin startup?")) {
                setTimeout(() => {
                    // Simulate server response
                    var response = { success: true };
                    if (response.success) {
                        checkbox.disabled = true;
                        alert("Checkin startup thành công!");
                    } else {
                        alert("Checkin startup không thành công.");
                        checkbox.checked = false;
                    }
                }, 500);
            } else {
                checkbox.checked = false;
            }
        }
    }
</script>


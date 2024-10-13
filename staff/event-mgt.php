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

<?php
require_once 'user.php';

require_once '../db_module.php';

// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);

// Số lượng dòng trên mỗi trang
$rowsPerPage = 4;

// Lấy trang hiện tại từ biến currentPage
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startRow = ($currentPage - 1) * $rowsPerPage;

$query = "SELECT 
    SuKien.MaSuKien,                        -- Event ID
    SuKien.TenSuKien,                       -- Event Name
    SuKien.MoTa,                            -- Description
    SuKien.ThoiGian,                        -- Event Time
    SuKien.DiaDiem,                         -- Event Location
    COUNT(DangKy.MaDangKy) AS SoLuongDangKy, -- Total count of related registrations
    -- Counting specific values in TinhTrangXacNhan
    SUM(CASE WHEN DangKy.TinhTrangXacNhan = 'Xác nhận' THEN 1 ELSE 0 END) AS SoLuongXacNhan,
    SUM(CASE WHEN DangKy.TinhTrangXacNhan = 'Chưa xác nhận' THEN 1 ELSE 0 END) AS SoLuongChoXacNhan,
    SUM(CASE WHEN DangKy.TinhTrangXacNhan = 'Từ chối' THEN 1 ELSE 0 END) AS SoLuongBiTuChoi
FROM 
    SuKien
LEFT JOIN 
    DangKy ON SuKien.MaSuKien = DangKy.MaSuKien
GROUP BY 
    SuKien.MaSuKien, SuKien.TenSuKien, SuKien.MoTa, SuKien.ThoiGian, SuKien.DiaDiem
ORDER BY SuKien.ThoiGian DESC
LIMIT $startRow, $rowsPerPage";

$queryCount = "SELECT COUNT(*) AS total FROM SuKien s";
$resultCount = chayTruyVanTraVeDL($link, $queryCount);
$rowCount = mysqli_fetch_assoc($resultCount);
$totalPages = ceil($rowCount['total'] / $rowsPerPage);


$result = chayTruyVanTraVeDL($link, $query);

$table_body = "";

// Kiểm tra xem truy vấn có thành công không
if (mysqli_num_rows($result) > 0) {
    $status = '';
    // Bắt đầu vòng lặp để xây dựng chuỗi HTML
    while ($row = mysqli_fetch_assoc($result)) {
        $table_body .= "<tr>";
        $table_body .= "<td>" . $row['TenSuKien'] . "</td>";
        $table_body .= "<td>" . $row['SoLuongXacNhan'] . " / ".$row['SoLuongDangKy'] . "</td>";
        $table_body .= "<td>" . $row['ThoiGian'] . "</td>";
        $table_body .= "<td>" . $row['DiaDiem'] . "</td>";
        if (strtotime($row['ThoiGian']) < strtotime('-3 days')) { //Cho phép quản lý người tham gia sau 3ngayf sự kiện xảy ra
            $table_body .= '<td>
                        <button class="view-btn" onclick="redirectToAttendeeList(' . $row['MaSuKien'] . ')">Xem</button>
                       </td>';
        }
        else {
            $table_body .= '<td>
                        <button class="edit-btn" onclick="redirectToAttendeeList(' . $row['MaSuKien'] . ')">Quản lý</button>
                       </td>';
        }
        $table_body .= "</tr>";
    }
} else {
    // Nếu không có dữ liệu, hiển thị dòng thông báo
    $table_body .= "<tr><td colspan='4'>Không có dữ liệu</td></tr>";
}
// Giải phóng bộ nhớ sau khi sử dụng
giaiPhongBoNho($link, $result);

?>

<div class='request-mng-container'>
    <div class='heading'>
        <div class='title'>
            Danh sách sự kiện
        </div>
        <div class='button'>
            <button onclick='redirectToCreateReq()'>
                Tạo sự kiện mới
            </button>
        </div>
    </div>
    <div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>Tên sự kiện</th>
                    <th>Tình trạng xác nhận</th>
                    <th>Thời gian</th>
                    <th>Địa điểm</th>
                    <th>Startup tham gia</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $table_body; ?>
            </tbody>
        </table>
        <div>
            <?php
            include '../pagination.php'; // Trang mặc định khi không có tham số trong URL
            ?>
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
    function redirectToAttendeeList(id) {
        window.location.href = 'attendee.php?id='+id;
    }

</script>

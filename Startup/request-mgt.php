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

// Số lượng dòng trên mỗi trang
$rowsPerPage = 4;

// Lấy trang hiện tại từ biến currentPage
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startRow = ($currentPage - 1) * $rowsPerPage;

// Truy vấn để lấy dữ liệu từ bảng cities với phân trang
$query = "SELECT MaYeuCauHoTro, TieuDeHoTro, NgayYeuCau, KetQua, MaStartup
FROM yeucauhotro WHERE MaStartup = $demo_user
ORDER BY NgayYeuCau DESC
LIMIT $startRow, $rowsPerPage";

$queryCount = "SELECT COUNT(*) AS total FROM yeucauhotro WHERE MaStartup = $demo_user";
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
        if ($row['KetQua'] == "") {$status = 'Chưa có kết quả';} else {$status = 'Đã xử lý';}
        $table_body .= "<tr>";
        $table_body .= "<td>" . $row['TieuDeHoTro'] . "</td>";
        $table_body .= "<td>" . $row['NgayYeuCau'] . "</td>";
        $table_body .= "<td>" . $status . "</td>";
        $table_body .= '<td>
                        
                        <button class="edit-btn" onclick="showPopUp(' . $row['MaYeuCauHoTro'] . ')">Xem</button>
                       </td>';
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
            Danh sách yêu cầu
        </div>
        <div class='button'>
            <button onclick='redirectToCreateReq()'>
                Tạo Yêu cầu mới
            </button>
        </div>
    </div>
    <div class='table-container'>
        <table>
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Ngày yêu cầu</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
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
    function redirectToCreateReq() {
        // Chuyển hướng đến URL chứa tham số "handle=create-cinema"
        document.getElementById('php-frame').src = 'new-request.php';
            
            // Display the overlay
        document.getElementById('overlay').style.display = 'flex';
    }

    function showPopUp(id) {
        document.getElementById('php-frame').src = 'view-request.php?id='+id;
            
            // Display the overlay
        document.getElementById('overlay').style.display = 'flex';

    }

    function hidePopup() {
            // Hide the overlay
            document.getElementById('overlay').style.display = 'none';
            
            // Clear the iframe src to stop the page loading (optional)
            document.getElementById('php-frame').src = '';
    }


</script>

<style>
    .request_container {
        width: 100%;
        max-width: 1075px;
        margin: 0 auto;
        margin-top: 30px;
        font-family: Roboto, sans-serif;
    }

    .request_container .information_form form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .request_container .information_form .form-input {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 5px;
    }
    textarea {
        font-family: Roboto, sans-serif;
    }
    #date {
        font-family: Roboto, sans-serif;
    }
    .action {
    display: flex;
    margin-top: 30px;
    gap: 20px;
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }

.action button {
    justify-content: center;
    width: 216px;
    border-radius: 5.067px;
    border-color: rgba(90, 99, 122, 1);
    border-style: solid;
    border-width: 1px;
    background-color: var(--White, #fff);
    color: var(--Shade-600, #5a637a);
    white-space: nowrap;
    padding: 12px 8px;
  }

</style>
<?php
require_once '../db_module.php';
require_once 'user.php';
// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);
$regid = isset($_GET['id']) ? $_GET['id'] : null;
$action = isset($_GET['action']) ? $_GET['id'] : null;
$evTitle = "";
$evContent = "";
$evDate= "";
$evLocation = "";


if ($regid) {
    $query = "SELECT s.MaSuKien, s.TenSuKien, s.MoTa, s.ThoiGian, s.DiaDiem, d.TinhTrangXacNhan, d.Checkin
    FROM SuKien s
    LEFT JOIN DangKy d ON s.MaSuKien = d.MaSuKien
    WHERE d.MaDangKy = $regid
    ";
    $result = chayTruyVanTraVeDL($link, $query);

    // Kiểm tra xem có dữ liệu trả về không
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $evTitle = $row['TenSuKien'];
        $evContent = $row['MoTa'];
        $evDate= $row['ThoiGian'];
        $evLocation = $row['DiaDiem'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //
    $link = NULL;
    taoKetNoi($link);
    if (isset($_POST['clicked_button'])) {
        $buttonClicked = $_POST['clicked_button'];
        if ($buttonClicked === 'confirm') {$confirm = 'Xác nhận';}
        elseif ($buttonClicked === 'reject') {$confirm = 'Từ chối';}
    }
    $sql = "UPDATE DangKy SET TinhTrangXacNhan = ? WHERE MaDangKy = ?";
    // Chuẩn bị câu lệnh SQL để thực thi với các tham số ?
    $stmt = mysqli_prepare($link, $sql);
    // Hàm liên kết các tham số với truy vấn SQL và cho cơ sở dữ liệu biết các tham số là gì
    mysqli_stmt_bind_param($stmt, "ss", $confirm, $regid);

    //Thực thi câu lệnh
    $insert_result = mysqli_stmt_execute($stmt);
    if ($insert_result) {
        echo "<script>alert('Yêu cầu thành công.');</script>";
    } else {
        echo "<script>alert('Đã có lỗi xảy ra.');</script>";
    }

    // Đóng câu lệnh
    mysqli_stmt_close($stmt);
}

?>
<div class='request_container'>
    <div>
        <h3>Thông tin sự kiện</h3>
    </div>
    <div class='information_form'>
        <form id="myForm" action="event.php" method="POST">
            <div class='form-input'>
                <label>Tên sự kiện</label>
                <input type="text" name="ev-name" value="<?php echo $evTitle?>" readonly>
            </div>
            <div class='form-input'>
                <label>Ngày tổ chức</label>
                <input type="date" id = 'date' name="ev-date"  value="<?php echo $evDate?>" readonly>
            </div>
            <div class='form-input'>
                <label>Địa điểm tổ chức</label>
                <input type="text" name="ev-locate" value="<?php echo $evLocation?>" readonly>
            </div>
            <div class='form-input'>
                <label>Mô tả sự kiện</label>
                <textarea id = "description" name="description" cols="30" rows="10" readonly><?php echo $evContent?></textarea>
            </div>
            <?php 
            if ($row['TinhTrangXacNhan'] == 'Chưa xác nhận' && date('Y-m-d') < $evDate) {
                echo '
                <div class="form-input">
                <label>Số người tham dự</label>
                <input type="text" name="attendee">
                </div>
                <div class = "action">
                <button name="clicked_button" value="confirm" type="submit" style="background-color: #244585; color: var(--Shade-100, #fff);">Xác nhận tham gia</button>
                <button name="clicked_button" value="reject" type="submit" style="background-color: #efeff0; color: var(--Shade-100, #fff); color: #244585">Không tham gia</button>
                </div>
                ';
            }
            ?>
        </form>
    </div>
</div>

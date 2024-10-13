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

</style>
<?php
require_once '../db_module.php';

// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);
$requestid = isset($_GET['id']) ? $_GET['id'] : null;

$reqTitle = "";
$reqContent = "";
$reqDate= "";
$reqResult = "";


if ($requestid) {
    $query = "SELECT * FROM yeucauhotro WHERE MaYeuCauHoTro = '$requestid' ";
    $result = chayTruyVanTraVeDL($link, $query);

    // Kiểm tra xem có dữ liệu trả về không
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $reqTitle = $row['TieuDeHoTro'];
        $reqContent = $row['NoiDungYeuCau'];
        $reqDate= $row['NgayYeuCau'];
        $reqResult = $row['KetQua'];
    }
}


?>
<div class='request_container'>
    <div>
        <h3>Thông tin yêu cầu</h3>
    </div>
    <div class='information_form'>
        <form>
            <div class='form-input'>
                <label>Tiêu đề yêu cầu</label>
                <input type="text" name="req-name" value="<?php echo $reqTitle?>" readonly>
            </div>
            <div class='form-input'>
                <label>Ngày tạo yêu cầu</label>
                <input type="date" id = 'date' name="req-date"  value="<?php echo $reqDate?>" readonly>
            </div>
            <div class='form-input'>
                <label>Nội dung yêu cầu</label>
                <textarea id = "description" name="description" cols="30" rows="10" readonly><?php echo $reqContent?></textarea>
            </div>
            <div class='form-input'>
                <label>Kết quả</label>
                <textarea id = "description" name="description" cols="30" rows="10" readonly><?php echo $reqResult?></textarea>
            </div>
            
        </form>
    </div>
</div>

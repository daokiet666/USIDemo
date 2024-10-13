<style>
    .request_container {
        width: 100%;
        max-width: 1075px;
        margin: 0 auto;
        margin-top: 30px;
        font-family: Roboto, sans-serif;
        padding: 20;
    }

    .information_form {
        margin-left: 50;
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
    form {
    width: 75%;
}

form label {
    display: block;
    margin-bottom: 3px;
    font-weight: 300;
}

input {
    margin-bottom: 25px;
    width: 100%;
    font-family: Roboto, sans-serif;
    font-size: 14px;
    padding: 5px;
}

select {
    margin-bottom: 25px;
    width: 40%;
    font-family: Roboto, sans-serif;
    font-size: 14px;
    padding: 5px;
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


if (isset($_GET['startup-id'])) {
    $startup_id = $_GET['startup-id'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $target = $_POST["target"];
    $link = NULL;
    taoKetNoi($link);

    $sql = "INSERT INTO muctieu (TenMucTieu, GiaTri, MaStartup) VALUES (?,?,?)";
    // Chuẩn bị câu lệnh SQL để thực thi với các tham số ?
    $stmt = mysqli_prepare($link, $sql);
    // Hàm liên kết các tham số với truy vấn SQL và cho cơ sở dữ liệu biết các tham số là gì
    mysqli_stmt_bind_param($stmt, "ssi", $name, $target, $startup_id);

    //Thực thi câu lệnh
    $insert_result = mysqli_stmt_execute($stmt);
    if ($insert_result) {
        echo "<script>alert('Thêm mục tiêu thành công.');</script>";
    } else {
        echo "<script>alert('Đã có lỗi xảy ra.');</script>";
    }

    // Đóng câu lệnh
    mysqli_stmt_close($stmt);
}


?>
<div class='request_container'>
    <div>
        <h3>Thông tin mục tiêu</h3>
    </div>
    <div class='information_form'>
        <form id="myForm" action="new-okr.php" method="POST">
            <div class='form-input'>
                <label>Tên mục tiêu</label>
                <input type="text" name="name" >
            </div>

            <div class='form-input'>
                <label>Giá trị mục tiêu</label>
                <input type="number" id="amount" name="target" step="0.01" min="0" max="999999999999.99" required>
            </div>
            <div class="action">
            <button type="submit" style="background-color: var(--Royal-Blue, #1a2c50); color: var(--Shade-100, #fff);">Tạo mục tiêu</button>
        </div>
        </form>
    </div>
</div>

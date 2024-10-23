<style>
    .record_container {
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
    .record_container .information_form form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .record_container .information_form .form-input {
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

  .record_container .table-container {
        margin-top: 20px;
    }

    .record_container table {
        width: 100%;
        border-collapse: collapse;
    }

    .record_container th,
    td {
        padding: 6px;
        text-align: center;

    }

    .record_container th:not(:last-child),
    td:not(:last-child) {


        border-right: 1px solid #ddd;
    }

    .record_container th {
        background-color: #244585;
        color: white;
    }

    .record_container tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .record_container table button,
    .record_container table .view-btn {
        padding: 6px 6px;
        margin-right: 5px;
        border: none;
        cursor: pointer;
        border-radius: 3px;
        color: white;
    }

    .record_container table button {
        background-color: white;
        color: #244585;
        border: solid 1px black;
    }
    .task-table button {
        font-family: Roboto, sans-serif;
        color: #244585;
        border: none;
        font-weight: bold;
        margin-top: 10px;
        cursor: pointer;
        border-radius: 3px;
    }
    
    .record_container label {
        font-weight: bold;
    }

    table input {
        margin-bottom: 0;
    }
</style>
<?php
require_once '../db_module.php';
require_once 'user.php';
// Kết nối đến cơ sở dữ liệu
$link = null;
taoKetNoi($link);

$recDate = "";
$recContent = "";
$recStartup = "";
$startuptask_title= "";
$startuptask_description= "";
$usitask_title= "";
$usitask_description= "";
$last_id = "";
$query = "SELECT TenStartup, MaStartup FROM Startup";
$result = chayTruyVanTraVeDL($link, $query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recDate = $_POST["recdate"];
    $recContent = $_POST["description"];
    $recStartup= $_POST["startup"];
    $startuptask_titles = isset($_POST['startup_task_titles']) ? $_POST['startup_task_titles'] : [];
    $startuptask_descriptions = isset($_POST['startup_task_descriptions']) ? $_POST['startup_task_descriptions'] : [];
    $usitask_titles = isset($_POST['usi_task_titles']) ? $_POST['usi_task_titles'] : [];
    $usitask_descriptions = isset($_POST['usi_task_descriptions']) ? $_POST['usi_task_descriptions'] : [];

    //chuyen dang datetime 
    $recDatetime = str_replace('T', ' ', $recDate) . ":00";
    $link = NULL;
    taoKetNoi($link);

    $sql = "INSERT INTO bienban (ThoiGian, NoiDung, MaNhanVien, MaStartup) VALUES (?,?,?,?)";
    // Chuẩn bị câu lệnh SQL để thực thi với các tham số ?
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $recDatetime, $recContent, $user, $recStartup);

    //Thực thi câu lệnh
    $insert_result = mysqli_stmt_execute($stmt);
    if ($insert_result) {
        $last_id = mysqli_insert_id($link);
        echo "<script>alert('Tạo biên bản thành công.');</script>";
    } else {
        echo "<script>alert('Đã có lỗi xảy ra.');</script>";
    }

    // Đóng câu lệnh
    mysqli_stmt_close($stmt);

    //Insert nhhiệm vụ cho startup
    foreach ($startuptask_titles as $sindex => $startuptask_title ) {
        $startuptask_description = $startuptask_descriptions[$sindex];
        $startupsql = "INSERT INTO nhiemvustartup (TenNhiemVu, MoTaNhiemVu, MaBienBan) VALUES (?,?,?)";
        $stmt = mysqli_prepare($link, $startupsql);
        mysqli_stmt_bind_param($stmt, "ssi", $startuptask_title, $startuptask_description, $last_id);

        //Thực thi câu lệnh
        $insert_result = mysqli_stmt_execute($stmt);
    }

    $tasktype = 'TT002';
    $taskstatus = 'Chưa làm';
    //Insert nhhiệm vụ cho USI
    foreach ($usitask_titles as $uindex => $usitask_title ) {
        $usi_description = $usitask_descriptions[$uindex];
        $taskdate = substr($recDate, 0, 10);
        $usisql = "INSERT INTO tacvu (TieuDeTacVu, ChiTietTacVu, NgayTao, MaBienBan, MaLoaiTacVu, MaNhanVien, TrangThaiTacVu) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($link, $usisql);
        mysqli_stmt_bind_param($stmt, "sssisis", $usitask_title, $usi_description,$taskdate, $last_id, $tasktype, $user, $taskstatus);

        //Thực thi câu lệnh
        $insert_result = mysqli_stmt_execute($stmt);
    }
}


?>
<div class='record_container'>
    <div>
        <h3>Biên bản tư vấn định kỳ</h3>
    </div>
    <div class='information_form'>
        <form id="myForm" action="" method="POST">
            <div class='form-input'>
                <label>Thời gian</label>
                <input type="datetime-local" name="recdate">
            </div>
            <div class='form-input'>
                <label>Startup</label>
                <?php //tao dropdown startup
                echo "<select name='startup'>"; 
                while ($select_startup = $result->fetch_assoc()) {
                    echo "<option value='" . $select_startup['MaStartup'] . "'>" . $select_startup['TenStartup'] . "</option>";
                }
                echo "</select>";            
                ?>
            </div>

            <div class='form-input'>
                <label>Nội dung buổi tư vấn</label>
                <textarea id = "description" name="description" cols="30" rows="10"></textarea>
            </div>
            <!-- Nhiệm vụ của startup -->
             <div class = "task-table">
            <label>Nhiệm vụ của startup</label>
            <table>
                <thead>
                    <tr>
                        <th>Nhiệm vụ</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="startup_task">
                <!-- Task tự động dc thêm -->
                </tbody>
            </table>
            <button type="button" onclick="addTask('startup_task')">+ Thêm</button>
            </div>
            <!-- Nhiệm vụ của USI -->
            <div class = "task-table">
            <label>Cam kết hành động của USI</label>
            <table>
                <thead>
                    <tr>
                        <th>Cam kết</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody id="usi_task">
                <!-- Task tự động dc thêm -->
                </tbody>
            </table>
            <button type="button" onclick="addTask('usi_task')">+ Thêm</button>
            </div>
            <div class="action">
            <button type="submit" style="background-color: #244585; color: var(--Shade-100, #fff);">Tạo biên bản</button>
        </div>
        </form>
    </div>
</div>

<script>
    function addTask(type) {
        const tableBody = document.getElementById(type);

        // Create a new row
        const newRow = document.createElement("tr");

        // Create title input
        const titleCell = document.createElement("td");
        const titleInput = document.createElement("input");
        titleInput.type = "text";
        titleInput.name = type +'_titles[]';
        titleInput.placeholder = "Enter task title";
        titleInput.required = true;
        titleCell.appendChild(titleInput);
        // Create description input
        const descriptionCell = document.createElement("td");
        const descriptionInput = document.createElement("input");
        descriptionInput.type = "text";
        descriptionInput.name = type + '_descriptions[]';
        descriptionInput.placeholder = "Enter task description";
        descriptionInput.required = true;
        descriptionCell.appendChild(descriptionInput);

        // Create action cell with remove button
        const actionCell = document.createElement("td");
        const removeButton = document.createElement("button");
        removeButton.type = "button";
        removeButton.textContent = "Loại bỏ"; 
        removeButton.onclick = function () {
            removeTask(removeButton);
        };
        actionCell.appendChild(removeButton);

        // Append cells to the new row
        newRow.appendChild(titleCell);
        newRow.appendChild(descriptionCell);
        newRow.appendChild(actionCell);

        // Append new row to the table body
        tableBody.appendChild(newRow);
    }

    function removeTask(button) {
        // Remove the row that contains the clicked button
        const row = button.closest("tr");
        row.remove();
    }
</script>
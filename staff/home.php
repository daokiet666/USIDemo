
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
    * {
        margin: 0px;
    }

    .admin-management {
        display: grid;
        grid-template-columns: auto 1fr;
        width: 100%;
        height: 100vh;
    }

    .sidebar {
        background-color: #1a2c50;
        display: flex;
        max-width: 241px;
        flex-direction: column;
        align-items: center;
        font-size: 16px;
        color: #fff;
        font-weight: 400;
        line-height: 150%;
        padding: 25px 25px 80px;
    }

    .logo {
        aspect-ratio: 1.96;
        object-fit: auto;
        object-position: center;
        width: 119px;
        max-width: 100%;
    }

    .welcome-text {
        font-family: Roboto, sans-serif;
        border-radius: 8px;
        margin-top: 32px;
        white-space: nowrap;
        justify-content: center;
    }

    .menu-container {
        align-self: stretch;
        display: flex;
        margin-top: 67px;
        flex-direction: column;
        row-gap: 16px;
    }

    .menu-item {
        justify-content: space-between;
        border-radius: 4px;
        display: flex;
        gap: 20px;
        cursor: pointer;
        padding: 8px 16px;
    }

    .menu-item-active {
        background-color: #ffbe00;
    }

    .menu-item-default {
        background-color: #118eea;
    }

    .menu-item-content {
        display: flex;
        gap: 16px;
    }

    .menu-item-icon {
        aspect-ratio: 1;
        object-fit: auto;
        object-position: center;
        width: 16px;
        margin: auto 0;
    }

    .menu-item-text {
        font-family: Roboto, sans-serif;
    }

    .menu-item-arrow {
        aspect-ratio: 0.6;
        object-fit: auto;
        object-position: center;
        width: 6px;
        stroke-width: 2px;
        stroke: #fff;
        border-color: rgba(255, 255, 255, 1);
        border-style: solid;
        border-width: 2px;
        margin: auto 0;
    }

    .menu-item-text-multiline {
        line-height: 24px;
    }

    .header {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        padding: 8px 60px;
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 1);
        font-size: 18px;
        font-weight: 500;
        color: var(--Pastel-Yellow, #f2c46f);
        text-align: center;
    }

    @media (max-width: 991px) {
        .header {
            padding: 0 20px;
        }
    }

    .user-info {
        display: flex;
        justify-content: flex-end;
        gap: 20px;
    }

    @media (max-width: 991px) {
        .user-info {
            margin-right: 10px;
        }
    }

    .user-avatar {
        width: 32px;
        aspect-ratio: 1;
        object-fit: cover;
        object-position: center;
        margin: auto 0;
    }

    .logout-button {
        padding: 12px 8px;
        font-family: Roboto, sans-serif;
        background-color: var(--Royal-Blue, #1a2c50);
        border-radius: 5.067px;
        color: inherit;
        cursor: pointer;
    }
    </style>

    <div class="admin-management">
        <div class="sidebar">
            <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/d9143cf1fb8d6fc12272f9775cc3aeff77b9839d32e73495b6a9cf83d7d9c3b7?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                alt="Logo" class="logo" />
            <h2 class="welcome-text">Welcome</h2>
            <div class="menu-container">

                <div onclick="redirectTo('task-mgt')"
                    class="<?php echo isset($_GET['handle']) && ($_GET['handle'] == 'task-mgt' || $_GET['handle'] == 'create-cinema' || $_GET['handle'] == 'edit-cinema') || !isset($_GET['handle']) ? 'menu-item menu-item-active' : 'menu-item menu-item-default'; ?>">
                    <div class="menu-item-content">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/1645334a3bbbbf67bb257cdcb36aa05979f059c53a4ab1b308f97b9a5bf22a2f?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                            alt="Quản lý rạp icon" class="menu-item-icon" />

                        <span class="menu-item-text">Quản lý tác vụ</span>
                    </div>


                </div>
                <div onclick="redirectTo('event-mgt')"
                    class="<?php echo isset($_GET['handle']) && $_GET['handle'] == 'event-mgt' ? 'menu-item menu-item-active' : 'menu-item menu-item-default'; ?>">
                    <div class="menu-item-content">

                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/1645334a3bbbbf67bb257cdcb36aa05979f059c53a4ab1b308f97b9a5bf22a2f?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                            alt="Lên lịch chiếu icon" class="menu-item-icon" />
                        <span class="menu-item-text">Quản lý sự kiện</span>

                    </div>

                </div>
                <div onclick="redirectTo('startup-mgt')"
                    class="<?php echo isset($_GET['handle']) && ($_GET['handle'] == 'startup-mgt' || $_GET['handle'] == 'create-movie' || $_GET['handle'] == 'edit-movie') ? 'menu-item menu-item-active' : 'menu-item menu-item-default'; ?>">


                    <div class="menu-item-content">
                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/1645334a3bbbbf67bb257cdcb36aa05979f059c53a4ab1b308f97b9a5bf22a2f?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                            alt="Lên lịch chiếu icon" class="menu-item-icon" />
                        <span class="menu-item-text">Quản lý startups</span>

                    </div>


                </div>
                
                <div onclick="redirectTo('record-mgt')"
                    class="<?php echo isset($_GET['handle']) && ($_GET['handle'] == 'record-mgt' || $_GET['handle'] == 'create-news' || $_GET['handle'] == 'edit-news') ? 'menu-item menu-item-active' : 'menu-item menu-item-default'; ?>">
                    <div class="menu-item-content">

                        <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/edf3b61290f0d8d6c17ab64b262286faf7283c49410ded99a4d96000414d0c34?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                            alt="Quản lý bài viết icon" clas s="menu-item-icon" />
                        <span class="menu-item-text">Biên bản tư vấn</span>
                    </div>


                </div>

                
            </div>
        </div>
        <div class="header-content">
            <div class="header">
                <div class="user-info">
                    <img src="https://cdn.builder.io/api/v1/image/assets/TEMP/88a804c5520def4dcdc79f06fa68269a7051516cb28777adf3b68509506917d3?apiKey=a7b5919b608d4a8d87d14c0f93c1c4bc&"
                        alt="User avatar" class="user-avatar" />
                    <button class="logout-button" onclick='signOut()'>Đăng xuất</button>
                </div>
            </div>
            <div class='content'>
                <?php
                // Kiểm tra tham số "handle" trong URL
                if (isset($_GET['handle'])) {
                    $handle = $_GET['handle'];
                    // Kiểm tra giá trị của tham số "handle" và include trang tương ứng
                    switch ($handle) {

                        case 'task-mgt':

                            include 'task-mgt.php';
                            break;
                        case 'create-cinema':
                            include 'create-cinema.php';
                            break;
                        case 'edit-cinema':
                            include 'edit-cinema.php';
                            break;

                        case 'startup-mgt':
                            include 'startup-mgt.php';
                            break;
                        case 'create-movie':
                            include 'create-movie.php';
                            break;
                        case 'edit-movie':
                            include 'edit-movie.php';
                            break;

                        case 'change-password':
                            include 'change-password.php';
                            break;
                        case 'event-mgt':
                            include 'event-mgt.php';
                            break;


                        case 'startup-info':
                            include 'startup-info.php';
                            break;
                        case 'create-food':
                            include 'create-food.php';
                            break;
                        case 'edit-food':
                            include 'edit-food.php';
                            break;


                        case 'record-mgt':
                            include 'record-mgt.php';
                            break;
                        case 'create-news':
                            include 'new_news.php';
                            break;
                        case 'edit-news':
                            include 'edit_news.php';
                            break;
                        case 'record':
                            include 'record.php';
                            break;
                        default:
                            include 'task-mgt.php'; // Trang mặc định khi không có tham số hoặc tham số không hợp lệ
                            break;
                    }
                } else {
                    include 'task-mgt.php'; // Trang mặc định khi không có tham số trong URL
                }
                ?>
            </div>




        </div>
    </div>

</body>
<script>
function redirectTo(handle) {
    window.location.href = 'home.php?handle=' + handle;
}
const arr = [1, 2, 3, 4];
arr.forEach((number) => {
    return number * 2;
})
console.log(arr);

function signOut() {
    var xhr = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('action', 'logout');
    xhr.open("POST", window.location.href, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 &&
            xhr.status === 200) { // Nếu request thành công, redirect hoặc thực hiện các hành động khác // Ví dụ:
            window.location.href = "login.php";
        }
    };
    xhr.send(formData);

}
</script>


</html>
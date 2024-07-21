<?php
if (session_status() == PHP_SESSION_NONE) {
    header('Location: /admin');
}

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Vui lòng nhập tên tài khoản!";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Vui lòng nhập mật khẩu!";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT username, password, user_id FROM account WHERE username = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {                    
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password, $user_id);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["username"] = $username;
                            $_SESSION["admin"] = false;
                            $_SESSION["guest"] = false;
                            
                            $sql = "select type from user where id = $user_id";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $type = $result->fetch_assoc()["type"];
                                
                                if ($type == "-1") {
                                    $_SESSION["admin"] = true;
                                }
                                if ($type == "0") {
                                    $_SESSION["guest"] = true;
                                }
                            } else {
                                $_SESSION["admin"] = false;
                                $_SESSION["guest"] = true;
                            }  

                            header("location: /admin");
                        } else {
                            $password_err = "Mật khẩu không chính xác!";
                        }
                    }
                } else {
                    $username_err = "Tài khoản không tồn tại!";
                }
            } else {
                echo "Hệ thống có sự cố, vui lòng thử lại sau!";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Đăng nhập</title>
        <link rel="stylesheet" href="/admin/stylesAuth.css">
    </head>
    <body>
        <h2>Đăng nhập</h2>
        <form action="/admin/auth.php?type=login" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                Tên tài khoản: <input type="text" name="username" placeholder="Nhập tên tài khoản" value="<?php echo $username; ?>" required>
                <span class="error"><?php echo $username_err == '' ? '' : $username_err . "<p></p>"; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                Mật khẩu: <input type="password" name="password" placeholder="Nhập mật khẩu" required>
                <span class="error"><?php echo $password_err == '' ? '' : $password_err . "<p></p>"; ?></span>
            </div>
            <a href="/admin/auth.php?type=forgot" class="button">Quên mật khẩu?</a>
            <p></p>
            <div class="form-group">
                <input type="submit" value="Đăng nhập">
            </div>
        </form>

        <form method="get">
            Chưa có tài khoản?
            <p></p>
            <a href="/admin/auth.php?type=register" class="button">Đăng ký</a>
        </form>
    </body>
</html>

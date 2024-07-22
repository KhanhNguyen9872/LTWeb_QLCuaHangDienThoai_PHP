<?php
if (session_status() == PHP_SESSION_NONE) {
    header('Location: /');
}

if (isset($_POST['submit'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $fullname = $_POST["fullname"];
    $gender = $_POST["gender"];
    $numberphone = $_POST["numberphone"];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($username) || empty($password) || empty($email) || empty($fullname) || empty($numberphone)) {
        echo "Thông tin không được để trống.";
    } elseif ($password !== $repassword) {
        echo "Mật khẩu phải trùng nhau.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email không đúng định dạng.";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $repassword = password_hash($_POST['repassword'], PASSWORD_DEFAULT);
        $email = $_POST['email'];

        try {
            $sql = "INSERT INTO user (name, gender, email, phone) VALUES ('$fullname', '$gender', '$email', '$numberphone')";
            
            if (!$conn->query($sql)) {
                echo "Error: " . $sql . "<br>" . $conn->error;
                die();
            }

            $sql = "select id from user where name = '$fullname' and gender = '$gender' and email = '$email' and phone = '$numberphone'";
            $result = $conn->query($sql);
            $data = $result->fetch_assoc();
            $user_id = $data["id"];

            $stmt = $conn->prepare("INSERT INTO account (username, password, email, user_id) VALUE (?, ?, ?, ?);");
            $stmt->bind_param("ssss", $username, $password, $email, $user_id);
            try {
                $stmt->execute();
                echo "Đăng ký thành công!";
            } catch (Exception $ex) {
                echo "Thông tin không hợp lệ! Có vẻ như tài khoản hoặc email này đã đăng ký rồi!";
            }
        } catch (Exception $ex) {
            echo "";
        }
        $stmt->close();
    }
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Đăng ký</title>
        <link rel="stylesheet" href="/admin/stylesAuth.css">
    </head>
    <body>
        <h2>Đăng ký</h2>
        <form action="/admin/auth.php?type=register" method="post">
            Tên tài khoản: <input type="text" name="username" placeholder="Nhập tên tài khoản" required><br>
            Mật khẩu: <input type="password" name="password" placeholder="Nhập mật khẩu" required><br>
            Nhập lại mật khẩu: <input type="password" name="repassword" placeholder="Nhập lại" required><br>
            Họ và tên: <input type="text" name="fullname" placeholder="Nhập họ và tên của bạn" required><br>
            Giới tính: <br><p></p><select name="gender">
                <option value="0">Nam</option>
                <option value="1">Nữ</option>
                <option value="-1">Không xác định</option>
            </select><br>
            <p></p>
            Số điện thoại: <input type="text" name="numberphone" placeholder="Nhập số điện thoại của bạn" required><br>
            Email: <input type="email" name="email" placeholder="Nhập Email của bạn" required><br>

            <input type="submit" name="submit" value="Đăng ký">
            <p></p>
            <a href="/admin" class="button">Quay về trang chủ</a>
        </form>
    </body>
</html>

<?php
require_once 'config/db.php';
require_once 'config/lib.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chu</title>
    <link rel="stylesheet" href="/css/banhang/style.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>Dien Thoai NHOM1</h1>
            <nav>
                <ul>
                    <li><a href="#">Trang Chu</a></li>
                    <li><a href="#">Liên He</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <p></p>

    <main class="container">
        <h2>Danh sách san pham</h2>
        <div class="product-list">
            <?php
            $sql = "SELECT id, name, price, img FROM phone order by id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>
                        <img src='/admin/upload/img/phone/" . $row["img"] . "' alt='" . $row["img"] . "'>
                        <h3>" . $row["name"] . "</h3>
                        <p>Giá: " . number_format($row["price"], 0, ',', '.') . " VND</p>
                        <button class='buy-btn'>Mua ngay</button>
                        </div>";
                }
            } else {
                echo "Không có san pham nào.";
            }
            $conn->close();
            ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; Copyright 2024. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

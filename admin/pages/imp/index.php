<?php
include '../config/check_login.php';
include '../config/db.php';

?>
<head>
<link rel="stylesheet" href="/admin/pages/imp/styles.css">
</head>
<body>
    <main>
        <?php
        // id	phone_id	quantity	price	dateimport	
        if (!is_guest()) {
            echo '<section class="add-type">
            <h2>Nhập kho</h2>
            <form action="/admin/pages/imp/add.php" method="post">
                <div>
                    <label for="name">Tên điện thoại</label><br>
                    <select name="phone_id">';

                    $sql = "select id, name from phone order by id";
                    $re = $conn->query($sql);
                    if ($re->num_rows > 0) {
                        while ($row = $re->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>' . "\n"; 
                        }
                    } else { 
                        echo '<option value="null">Vui lòng thêm điện thoại trước</option>';
                    }                    
                
            echo '</select>
                </div>
                <div>
                    <label for="quantity">Số lượng</label><br>
                    <input type="number" name="quantity" placeholder="Số lượng" size="1" required>
                </div>
                <div>
                    <label for="quantity">Giá nhập</label><br>
                    <input type="number" name="price" placeholder="Giá nhập" size="1" required>
                </div>
                <div>
                    <br>
                    <button type="submit">Nhập kho</button>
                </div>
            </form>
        </section><hr>';
        }
        ?>
        <section class="type-list">
            <h2>Danh sách phiếu nhập kho</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên điện thoại</th>
                        <th>Số lượng</th>
                        <th>Giá nhập</th>
                        <th>Ngày nhập</th>
                        <?php
                        if (!is_guest()) {
                            echo '<th>Hành động</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM imp order by id;";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $id = $row["phone_id"];
                            $sql = "select name from phone where id = $id";
                            $rr = $conn->query($sql);
                            if ($rr->num_rows > 0) {
                                $phoneName = $rr->fetch_assoc()["name"];
                            } else {
                                $phoneName = "null";
                            }
                            echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $phoneName . "</td>
                                <td>" . $row["quantity"] . "</td>
                                <td>" . number_format(round($row["price"], 0), 0, '', '.') . " VND" . "</td>
                                <td>" . $row["dateimport"] . "</td>";
                            if (!is_guest()) {
                            echo "<td class='actions'>
                                    <a href='/admin/?page=editImp&id=" . $row["id"] . "' class='edit-btn'>Sửa</a>
                                    <a href='#' onclick=\"deleteSubmit('" . $row["id"] . "')\" class='delete-btn'>Xóa</a>
                                </td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Không có loại phiếu nhập nào</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
<?php
    if (!is_guest()) {
    echo "<script>
        function deleteSubmit(id) {
            if (confirm('Bạn có muốn xóa id [' + id + '] không?')) {
                window.location.href = '/admin/pages/imp/delete.php?id=' + id;
            }
        }
    </script>";
    }
?>
</html>

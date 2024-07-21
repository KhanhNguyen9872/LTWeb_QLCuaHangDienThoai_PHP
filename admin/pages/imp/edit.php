<?php
include '../config/check_login.php';
include '../config/check_guest.php';
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $phone_id = $_POST['phone_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $dateImport = $_POST['dateImport'];

    $sql = "UPDATE imp SET phone_id='$phone_id', quantity='$quantity', price='$price', dateimport='$dateImport' WHERE id=$id";

    if (!$conn->query($sql)) {
        echo "Error updating record: " . $conn->error;
        die();
    }

    $conn->close();

    echo "<script>window.location.href='/admin/?page=imp';</script>";
    exit();
} else {
    $id = $_GET['id'];
    $sql = "SELECT * FROM imp WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        redirect("/admin/?page=imp");
    }
}
?>

<link rel="stylesheet" href="/admin/pages/imp/styles.css">
<main>
    <section class="edit-type">
        <h2>Sửa thông tin loại</h2>
        <form action="/admin/?page=editImp" method="post">
            <div>
                <label for="id">ID</label><br>
                <input type="text" name="id" value="<?php echo $row['id']; ?>" size="1" readonly>
            </div>
            <div>
            <label for="phone_id">Tên điện thoại</label><br>
                <select name="phone_id">
                <?php
                    $sql = "select id, name from phone order by id";
                    $re = $conn->query($sql);
                    if ($re->num_rows > 0) {
                        while ($r = $re->fetch_assoc()) {
                            echo '<option value="' . $r['id'] . '"' . ($row['phone_id'] == $r['id'] ? 'selected="selected"' : '') . '>' . $r['name'] . '</option>' . "\n"; 
                        }
                    } else { 
                        echo '<option value="null">Vui lòng thêm điện thoại trước</option>';
                    }                    
                ?>
                </select>
            </div>
            <div>
                <label for="quantity">Số lượng</label><br>
                <input type="number" name="quantity" placeholder="Số lượng" value="<?php echo $row['quantity'] ?>" size="1" required>
            </div>
            <div>
                <label for="quantity">Giá nhập (VND)</label><br>
                <input type="number" name="price" placeholder="Giá nhập" value="<?php echo $row['price'] ?>" size="1" required>
            </div>
            <div>
                <label for="dateImport">Ngày nhập</label><br>
                <input type="date" id="dateImport" name="dateImport" value="<?php echo $row["dateimport"] ?>" required>
            </div>
            <div>
                <br>
                <button type="submit">Sửa</button>
            </div>
        </form>
    </section>
</main>

<?php
include '../../../config/check_login.php';
include '../../../config/check_guest.php';
include '../../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone_id = $_POST['phone_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    date_default_timezone_set('Asia/Bangkok');
    $dateImport = date('Y-m-d', time());

    $sql = "INSERT INTO imp (phone_id, quantity, price, dateImport) VALUES ('$phone_id', '$quantity', '$price', '$dateImport')";

    if (!$conn->query($sql)) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
    } else {
        $sql = "UPDATE phone set quantity = quantity + '$quantity' where (id = '$phone_id');";
        if (!$conn->query($sql)) {
            echo "Error: " . $sql . "<br>" . $conn->error;
            die();
        }
    }

    $conn->close();

    header('Location: /admin/?page=imp');
}
?>

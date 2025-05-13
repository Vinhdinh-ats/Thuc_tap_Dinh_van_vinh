<?php

function isValidTransactionName($name) {
    return preg_match("/^[\p{L}\s0-9]+$/u", $name); // Chỉ chữ cái, số, khoảng trắng
}

function isValidAmount($amount) {
    return preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $amount) && $amount > 0;
}

function isValidDate($date) {
    return preg_match("/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/", $date);
}

function containsSensitiveKeyword($note) {
    foreach ($GLOBALS['sensitive_keywords'] as $keyword) {
        if (stripos($note, $keyword) !== false) {
            return true;
        }
    }
    return false;
}

function saveTransactionToDatabase($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO transactions (transaction_name, amount, type, note, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $data['transaction_name'], $data['amount'], $data['type'], $data['note'], $data['date']);
    return $stmt->execute();
}

function getTransactionsFromDatabase($conn) {
    $result = $conn->query("SELECT * FROM transactions ORDER BY id DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

<?php
session_start();
if (isset($_GET['author'])) {
    $_SESSION['selectedAuthor'] = $_GET['author'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'No author provided']);
}
?> 
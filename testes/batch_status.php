<?php
session_start();

if (isset($_SESSION['batch_status'])) {
    echo json_encode([
        "status" => $_SESSION['batch_status']
    ]);
} else {
    echo json_encode([
        "status" => "no_process"
    ]);
}

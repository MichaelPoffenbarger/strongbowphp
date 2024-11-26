<?php
require_once 'config.php';

function get_user_by_id($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_projects_by_user($user_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM projects WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function get_project_by_id($project_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function get_updates_by_project($project_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM project_updates WHERE project_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function add_project_update($project_id, $content) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO project_updates (project_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $project_id, $content);
    return $stmt->execute();
}

function get_files_by_project($project_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM project_files WHERE project_id = ? ORDER BY uploaded_at DESC");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function add_project_file($project_id, $file_name, $file_path) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO project_files (project_id, file_name, file_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $project_id, $file_name, $file_path);
    return $stmt->execute();
}
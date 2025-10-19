<?php
require_once 'config.php';

header('Content-Type: application/json');

$conn = getDBConnection();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add':
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priority = $_POST['priority'] ?? 'medium';
            
            if (empty($title)) {
                $response['message'] = 'Title is required';
                break;
            }
            
            $stmt = $conn->prepare("INSERT INTO todos (title, description, priority) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $priority);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Task added successfully';
                $response['id'] = $conn->insert_id;
            } else {
                $response['message'] = 'Failed to add task';
            }
            $stmt->close();
            break;
            
        case 'update':
            $id = intval($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priority = $_POST['priority'] ?? 'medium';
            
            if ($id <= 0 || empty($title)) {
                $response['message'] = 'Invalid data';
                break;
            }
            
            $stmt = $conn->prepare("UPDATE todos SET title = ?, description = ?, priority = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $description, $priority, $id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Task updated successfully';
            } else {
                $response['message'] = 'Failed to update task';
            }
            $stmt->close();
            break;
            
        case 'toggle':
            $id = intval($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            
            if ($id <= 0) {
                $response['message'] = 'Invalid task ID';
                break;
            }
            
            $stmt = $conn->prepare("UPDATE todos SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Status updated successfully';
            } else {
                $response['message'] = 'Failed to update status';
            }
            $stmt->close();
            break;
            
        case 'delete':
            $id = intval($_POST['id'] ?? 0);
            
            if ($id <= 0) {
                $response['message'] = 'Invalid task ID';
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Task deleted successfully';
            } else {
                $response['message'] = 'Failed to delete task';
            }
            $stmt->close();
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
}

$conn->close();
echo json_encode($response);

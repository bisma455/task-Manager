<?php
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['index'], $data['name'], $data['status'])) {
    $tasksFile = '../tasks.json';
    $tasks = json_decode(file_get_contents($tasksFile), true);
    $tasks[$data['index']] = [
        'name' => htmlspecialchars($data['name']),
        'status' => $data['status']
    ];
    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'tasks' => $tasks]);
}
?>

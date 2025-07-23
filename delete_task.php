<?php
$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['index'])) {
    $tasksFile = '../tasks.json';
    $tasks = json_decode(file_get_contents($tasksFile), true);
    unset($tasks[$data['index']]);
    $tasks = array_values($tasks); 
    file_put_contents($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));
    echo json_encode(['success' => true, 'tasks' => $tasks]);
}
?>

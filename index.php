<?php
$tasksFile = 'tasks.json';
if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, json_encode([]));
}
$tasks = json_decode(file_get_contents($tasksFile), true);

// Counts for stats
$done = count(array_filter($tasks, fn($t) => $t['status'] === 'Done'));
$inProgress = count(array_filter($tasks, fn($t) => $t['status'] === 'In Progress'));
$toDo = count(array_filter($tasks, fn($t) => $t['status'] === 'To Do'));
$total = count($tasks);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager - Purple Theme</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark bg-purple-gradient shadow-lg sticky-top rounded-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold text-white fs-3" href="#">
            <i class="bi bi-check2-circle"></i> TaskManager
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav gap-2">
                <li class="nav-item">
                    <a class="nav-link active text-white fw-semibold rounded-pill px-3 py-1" href="#hero">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold rounded-pill px-3 py-1" href="#tasks">📝 Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold rounded-pill px-3 py-1" href="#about">ℹ️ About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

   
    <section id="hero" class="d-flex align-items-center text-center text-white bg-gradient-short">
        <div class="container py-5">
            <h1 class="display-5 fw-bold">Welcome to Task Manager</h1>
            <p class="lead">Organize. Track. Complete.</p>
            <a href="#tasks" class="btn btn-light btn-lg rounded-pill mt-3">Get Started</a>
        </div>
    </section>

   
    <section id="stats" class="py-4 bg-light">
        <div class="container text-center">
            <h2 class="mb-4 text-purple">Your Task Overview</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-box bg-purple-light shadow-sm">📋 <?= $total ?><br><small>Total</small></div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box bg-purple-light shadow-sm">✅ <?= $done ?><br><small>Done</small></div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box bg-purple-light shadow-sm">🔵 <?= $inProgress ?><br><small>In Progress</small></div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box bg-purple-light shadow-sm">🟡 <?= $toDo ?><br><small>To Do</small></div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="tasks" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 text-purple">Manage Your Tasks</h2>
            <p class="text-center text-muted mb-4">
                Create and manage your daily tasks below. Stay organized and boost your productivity effortlessly.
            </p>
            <form id="taskForm" class="d-flex gap-2 mb-4">
                <input type="text" id="taskName" class="form-control rounded-pill" placeholder="Enter task name" required>
                <select id="taskStatus" class="form-select rounded-pill">
                    <option value="To Do">To Do</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Done">Done</option>
                </select>
                <button type="submit" class="btn btn-purple rounded-pill">Add</button>
            </form>

            <ul id="taskList" class="list-group">
                <?php foreach ($tasks as $index => $task): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center bg-purple-light text-dark mb-2">
                    <span>
                        <strong><?= htmlspecialchars($task['name']) ?></strong>
                        <small class="badge bg-purple text-white"><?= $task['status'] ?></small>
                    </span>
                    <span>
                        <button class="btn btn-sm btn-warning me-2" onclick="editTask(<?= $index ?>)">✏️</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteTask(<?= $index ?>)">🗑️</button>
                    </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    
    <section id="about" class="py-5 bg-gradient-short text-white">
        <div class="container text-center">
            <h2 class="mb-4">About TaskManager</h2>
            <p class="lead">
                TaskManager is a clean and powerful web app built with Plain PHP, JSON storage, and Bootstrap 5.
            </p>
            <p>
                It helps you plan, track, and manage your tasks seamlessly across devices. Designed for simplicity and performance, it works perfectly in XAMPP or any PHP server.
            </p>
            <p class="fw-bold">Made with ❤️ for productivity lovers.</p>
        </div>
    </section>

    
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-purple text-white">
            <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editTaskForm">
              <input type="hidden" id="editTaskIndex">
              <div class="mb-3">
                <label for="editTaskName" class="form-label">Task Name</label>
                <input type="text" id="editTaskName" class="form-control rounded-pill" required>
              </div>
              <div class="mb-3">
                <label for="editTaskStatus" class="form-label">Status</label>
                <select id="editTaskStatus" class="form-select rounded-pill">
                  <option value="To Do">To Do</option>
                  <option value="In Progress">In Progress</option>
                  <option value="Done">Done</option>
                </select>
              </div>
              <button type="submit" class="btn btn-purple rounded-pill">Update Task</button>
            </form>
          </div>
        </div>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="script.js"></script>
</body>
</html>

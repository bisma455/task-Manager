document.getElementById('taskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('taskName').value;
    const status = document.getElementById('taskStatus').value;

    axios.post('api/add_task.php', { name, status })
        .then(res => {
            updateTaskList(res.data.tasks);
            document.getElementById('taskForm').reset();
        });
});

function deleteTask(index) {
    axios.post('api/delete_task.php', { index })
        .then(res => updateTaskList(res.data.tasks));
}

function editTask(index) {
    const task = document.querySelectorAll('#taskList li')[index];
    const currentName = task.querySelector('strong').innerText;
    const currentStatus = task.querySelector('.badge').innerText;

    document.getElementById('editTaskIndex').value = index;
    document.getElementById('editTaskName').value = currentName;
    document.getElementById('editTaskStatus').value = currentStatus;

    // Show Modal
    const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
    editModal.show();
}

document.getElementById('editTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const index = document.getElementById('editTaskIndex').value;
    const name = document.getElementById('editTaskName').value;
    const status = document.getElementById('editTaskStatus').value;

    axios.post('api/update_task.php', { index, name, status })
        .then(res => {
            updateTaskList(res.data.tasks);
            bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
        });
});

function updateTaskList(tasks) {
    const list = document.getElementById('taskList');
    list.innerHTML = '';
    tasks.forEach((task, index) => {
        list.innerHTML += `
        <li class="list-group-item d-flex justify-content-between align-items-center bg-purple-light text-dark mb-2">
            <span>
                <strong>${task.name}</strong>
                <small class="badge bg-purple text-white">${task.status}</small>
            </span>
            <span>
                <button class="btn btn-sm btn-warning me-2" onclick="editTask(${index})">✏️</button>
                <button class="btn btn-sm btn-danger" onclick="deleteTask(${index})">🗑️</button>
            </span>
        </li>`;
    });
    updateStats(tasks);
}

function updateStats(tasks) {
    const total = tasks.length;
    const done = tasks.filter(t => t.status === 'Done').length;
    const inProgress = tasks.filter(t => t.status === 'In Progress').length;
    const toDo = tasks.filter(t => t.status === 'To Do').length;

    document.querySelector('.stat-box:nth-child(1)').innerHTML = `📋 ${total}<br><small>Total</small>`;
    document.querySelector('.stat-box:nth-child(2)').innerHTML = `✅ ${done}<br><small>Done</small>`;
    document.querySelector('.stat-box:nth-child(3)').innerHTML = `🔵 ${inProgress}<br><small>In Progress</small>`;
    document.querySelector('.stat-box:nth-child(4)').innerHTML = `🟡 ${toDo}<br><small>To Do</small>`;
}

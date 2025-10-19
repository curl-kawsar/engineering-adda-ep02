<?php
require_once 'config.php';

// Get filter and sort parameters
$filter = $_GET['filter'] ?? 'all';
$sort = $_GET['sort'] ?? 'created_desc';

// Build query
$conn = getDBConnection();
$query = "SELECT * FROM todos";
$conditions = [];

if ($filter === 'completed') {
    $conditions[] = "status = 'completed'";
} elseif ($filter === 'pending') {
    $conditions[] = "status = 'pending'";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Add sorting
switch ($sort) {
    case 'created_asc':
        $query .= " ORDER BY created_at ASC";
        break;
    case 'created_desc':
        $query .= " ORDER BY created_at DESC";
        break;
    case 'priority':
        $query .= " ORDER BY FIELD(priority, 'high', 'medium', 'low'), created_at DESC";
        break;
    case 'title':
        $query .= " ORDER BY title ASC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
}

$result = $conn->query($query);
$todos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $todos[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List - Task Manager</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-tasks"></i> My Todo List</h1>
            <p class="subtitle">Stay organized and productive</p>
        </header>

        <!-- Add Todo Form -->
        <div class="add-todo-section">
            <form id="addTodoForm">
                <input type="hidden" name="action" value="add">
                <input type="hidden" id="editId" name="id" value="">
                
                <div class="form-group">
                    <input type="text" id="todoTitle" name="title" placeholder="What needs to be done?" required>
                </div>
                
                <div class="form-group">
                    <textarea id="todoDescription" name="description" placeholder="Add description (optional)" rows="2"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <select id="todoPriority" name="priority">
                            <option value="low">Low Priority</option>
                            <option value="medium" selected>Medium Priority</option>
                            <option value="high">High Priority</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <span id="submitBtnText">Add Task</span>
                    </button>
                    
                    <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Filters and Sort -->
        <div class="controls">
            <div class="filters">
                <button class="filter-btn <?= $filter === 'all' ? 'active' : '' ?>" data-filter="all">
                    <i class="fas fa-list"></i> All
                </button>
                <button class="filter-btn <?= $filter === 'pending' ? 'active' : '' ?>" data-filter="pending">
                    <i class="fas fa-clock"></i> Pending
                </button>
                <button class="filter-btn <?= $filter === 'completed' ? 'active' : '' ?>" data-filter="completed">
                    <i class="fas fa-check-circle"></i> Completed
                </button>
            </div>
            
            <div class="sort">
                <select id="sortSelect">
                    <option value="created_desc" <?= $sort === 'created_desc' ? 'selected' : '' ?>>Newest First</option>
                    <option value="created_asc" <?= $sort === 'created_asc' ? 'selected' : '' ?>>Oldest First</option>
                    <option value="priority" <?= $sort === 'priority' ? 'selected' : '' ?>>Priority</option>
                    <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Title (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Todo List -->
        <div class="todo-list" id="todoList">
            <?php if (empty($todos)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>No tasks found. Add your first task to get started!</p>
                </div>
            <?php else: ?>
                <?php foreach ($todos as $todo): ?>
                    <div class="todo-item <?= $todo['status'] === 'completed' ? 'completed' : '' ?>" data-id="<?= $todo['id'] ?>">
                        <div class="todo-checkbox">
                            <input type="checkbox" 
                                   class="task-checkbox" 
                                   data-id="<?= $todo['id'] ?>"
                                   <?= $todo['status'] === 'completed' ? 'checked' : '' ?>>
                        </div>
                        
                        <div class="todo-content">
                            <h3 class="todo-title"><?= htmlspecialchars($todo['title']) ?></h3>
                            <?php if (!empty($todo['description'])): ?>
                                <p class="todo-description"><?= nl2br(htmlspecialchars($todo['description'])) ?></p>
                            <?php endif; ?>
                            <div class="todo-meta">
                                <span class="priority priority-<?= $todo['priority'] ?>">
                                    <i class="fas fa-flag"></i> <?= ucfirst($todo['priority']) ?>
                                </span>
                                <span class="date">
                                    <i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($todo['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="todo-actions">
                            <button class="btn-icon edit-btn" data-id="<?= $todo['id'] ?>" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete-btn" data-id="<?= $todo['id'] ?>" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Statistics -->
        <?php
        $totalTasks = count($todos);
        $completedTasks = count(array_filter($todos, fn($t) => $t['status'] === 'completed'));
        $pendingTasks = $totalTasks - $completedTasks;
        ?>
        <div class="stats">
            <div class="stat-item">
                <span class="stat-value"><?= $totalTasks ?></span>
                <span class="stat-label">Total Tasks</span>
            </div>
            <div class="stat-item">
                <span class="stat-value"><?= $pendingTasks ?></span>
                <span class="stat-label">Pending</span>
            </div>
            <div class="stat-item">
                <span class="stat-value"><?= $completedTasks ?></span>
                <span class="stat-label">Completed</span>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>

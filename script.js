// DOM Elements
const addTodoForm = document.getElementById('addTodoForm');
const todoTitle = document.getElementById('todoTitle');
const todoDescription = document.getElementById('todoDescription');
const todoPriority = document.getElementById('todoPriority');
const editId = document.getElementById('editId');
const submitBtnText = document.getElementById('submitBtnText');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const todoList = document.getElementById('todoList');
const filterBtns = document.querySelectorAll('.filter-btn');
const sortSelect = document.getElementById('sortSelect');

// Current filter and sort
let currentFilter = new URLSearchParams(window.location.search).get('filter') || 'all';
let currentSort = new URLSearchParams(window.location.search).get('sort') || 'created_desc';

// Add/Update Todo
addTodoForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(addTodoForm);
    const isEditing = editId.value !== '';
    
    if (isEditing) {
        formData.set('action', 'update');
    }
    
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            resetForm();
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
});

// Toggle Task Status
todoList.addEventListener('change', async (e) => {
    if (e.target.classList.contains('task-checkbox')) {
        const taskId = e.target.dataset.id;
        const status = e.target.checked ? 'completed' : 'pending';
        
        const formData = new FormData();
        formData.append('action', 'toggle');
        formData.append('id', taskId);
        formData.append('status', status);
        
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                const todoItem = e.target.closest('.todo-item');
                todoItem.classList.toggle('completed');
                showNotification('Task status updated', 'success');
            } else {
                e.target.checked = !e.target.checked;
                showNotification(data.message, 'error');
            }
        } catch (error) {
            e.target.checked = !e.target.checked;
            showNotification('Failed to update task', 'error');
            console.error('Error:', error);
        }
    }
});

// Edit Task
todoList.addEventListener('click', async (e) => {
    const editBtn = e.target.closest('.edit-btn');
    if (editBtn) {
        const taskId = editBtn.dataset.id;
        const todoItem = editBtn.closest('.todo-item');
        
        const title = todoItem.querySelector('.todo-title').textContent;
        const descElement = todoItem.querySelector('.todo-description');
        const description = descElement ? descElement.textContent : '';
        const priorityElement = todoItem.querySelector('.priority');
        const priority = priorityElement.className.split('priority-')[1];
        
        todoTitle.value = title;
        todoDescription.value = description;
        todoPriority.value = priority;
        editId.value = taskId;
        
        submitBtnText.textContent = 'Update Task';
        cancelEditBtn.style.display = 'inline-flex';
        
        // Scroll to form
        window.scrollTo({ top: 0, behavior: 'smooth' });
        todoTitle.focus();
    }
});

// Delete Task
todoList.addEventListener('click', async (e) => {
    const deleteBtn = e.target.closest('.delete-btn');
    if (deleteBtn) {
        if (!confirm('Are you sure you want to delete this task?')) {
            return;
        }
        
        const taskId = deleteBtn.dataset.id;
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', taskId);
        
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                // Remove the todo item from DOM
                const todoItem = deleteBtn.closest('.todo-item');
                todoItem.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    window.location.reload();
                }, 300);
            } else {
                showNotification(data.message, 'error');
            }
        } catch (error) {
            showNotification('Failed to delete task', 'error');
            console.error('Error:', error);
        }
    }
});

// Cancel Edit
cancelEditBtn.addEventListener('click', () => {
    resetForm();
});

// Filter Tasks
filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        const filter = btn.dataset.filter;
        updateURL(filter, currentSort);
    });
});

// Sort Tasks
sortSelect.addEventListener('change', () => {
    const sort = sortSelect.value;
    updateURL(currentFilter, sort);
});

// Helper Functions
function resetForm() {
    addTodoForm.reset();
    editId.value = '';
    submitBtnText.textContent = 'Add Task';
    cancelEditBtn.style.display = 'none';
}

function updateURL(filter, sort) {
    const url = new URL(window.location);
    url.searchParams.set('filter', filter);
    url.searchParams.set('sort', sort);
    window.location.href = url.toString();
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 300px;
    `;
    
    if (type === 'success') {
        notification.style.background = '#10b981';
    } else if (type === 'error') {
        notification.style.background = '#ef4444';
    } else {
        notification.style.background = '#3b82f6';
    }
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    @keyframes slideOut {
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

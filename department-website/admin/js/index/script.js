// Notyf initialization
const notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'right',
        y: 'top'
    }
});

// DOM Elements
const tableBody = document.getElementById('tableBody');
const searchInput = document.getElementById('searchInput');
const yearFilter = document.getElementById('yearFilter');
const classFilter = document.getElementById('classFilter');
const minPointsRange = document.getElementById('minPointsRange');
const maxPointsRange = document.getElementById('maxPointsRange');
const pointsRangeLabel = document.getElementById('pointsRangeLabel');
const rangeProgress = document.getElementById('rangeProgress');
const todoInput = document.getElementById('todoInput');
const todoDescInput = document.getElementById('todoDescInput');
const todoList = document.getElementById('todoList');
const addTodoBtn = document.getElementById('addTodoBtn');

// Statistic Elements
const totalStudentsEl = document.getElementById('totalStudents');
const avgPointsEl = document.getElementById('avgPoints');
const topPerformerEl = document.getElementById('topPerformer');
const totalEventsEl = document.getElementById('totalEvents');

// Todo List Management
// Load todos from the database on page load
function loadTodos() {
    fetch('./get_todos. php')       
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderTodos(data.todos);
            } else {
                notyf.error('Failed to load todos: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error loading todos:', error);
            notyf.error('Failed to load todos');
        });
}

// Add a new todo
function addTodo() {
    const title = todoInput.value.trim();
    const description = todoDescInput.value.trim();
    
    if (!title) {
        notyf.error('Please enter a title for the todo');
        return;
    }
    
    // Send data to server
    fetch('./add_todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            description: description
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add the new todo to the list with the ID from the server
            loadTodos(); // Reload todos from server
            todoInput.value = '';
            todoDescInput.value = '';
            notyf.success('Todo added successfully');
        } else {
            notyf.error('Failed to add todo: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error adding todo:', error);
        notyf.error('Failed to add todo');
    });
}

// Delete a todo
function deleteTodo(id) {
    fetch('./delete_todo.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTodos(); // Reload todos from server
            notyf.success('Todo deleted successfully');
        } else {
            notyf.error('Failed to delete todo: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error deleting todo:', error);
        notyf.error('Failed to delete todo');
    });
}

// Edit a todo
function editTodo(id, currentTitle, currentDesc) {
    // Create a modal dynamically for editing
    const modalHTML = `
        <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTodoModalLabel">Edit Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editTodoTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="editTodoTitle" value="${currentTitle}">
                        </div>
                        <div class="mb-3">
                            <label for="editTodoDesc" class="form-label">Description</label>
                            <textarea class="form-control" id="editTodoDesc" rows="3">${currentDesc}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to DOM
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('editTodoModal'));
    modal.show();
    
    // Handle save button click
    document.getElementById('saveEditBtn').addEventListener('click', function() {
        const newTitle = document.getElementById('editTodoTitle').value.trim();
        const newDesc = document.getElementById('editTodoDesc').value.trim();
        
        if (!newTitle) {
            notyf.error('Title cannot be empty');
            return;
        }
        
        // Send update to server
        fetch('./update_todo.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                event_id: id,
                title: newTitle,
                description: newDesc
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadTodos(); // Reload todos from server
                modal.hide();
                document.getElementById('editTodoModal').remove();
                notyf.success('Todo updated successfully');
            } else {
                notyf.error('Failed to update todo: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error updating todo:', error);
            notyf.error('Failed to update todo');
        });
    });
    
    // Remove modal from DOM after hiding
    document.getElementById('editTodoModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Render todos to the UI
function renderTodos(todos) {
    todoList.innerHTML = '';
    
    if (todos.length === 0) {
        todoList.innerHTML = '<li class="list-group-item text-muted">No future events planned yet</li>';
        return;
    }
    
    todos.forEach(todo => {
        const li = document.createElement('li');
        li.className = 'list-group-item d-flex justify-content-between align-items-center';
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'todo-content';
        
        const title = document.createElement('h6');
        title.className = 'mb-1';
        title.textContent = todo.title;
        
        const description = document.createElement('p');
        description.className = 'mb-0 text-muted small';
        description.textContent = todo.description || 'No description';
        
        contentDiv.appendChild(title);
        contentDiv.appendChild(description);
        
        const buttonsDiv = document.createElement('div');
        buttonsDiv.className = 'todo-actions';
        
        const editButton = document.createElement('button');
        editButton.className = 'btn btn-sm btn-warning me-2';
        editButton.innerHTML = '<i class="bi bi-pencil"></i>';
        editButton.addEventListener('click', () => editTodo(todo.event_id, todo.title, todo.description));
        
        const deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-sm btn-danger';
        deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
        deleteButton.addEventListener('click', () => deleteTodo(todo.event_id));
        
        buttonsDiv.appendChild(editButton);
        buttonsDiv.appendChild(deleteButton);
        
        li.appendChild(contentDiv);
        li.appendChild(buttonsDiv);
        todoList.appendChild(li);
    });
}

// Table Management
function updateStatistics(filteredStudents) {
    totalStudentsEl.textContent = filteredStudents.length;
    
    const totalPoints = filteredStudents.reduce((sum, student) => sum + parseInt(student.points), 0);
    avgPointsEl.textContent = filteredStudents.length 
        ? (totalPoints / filteredStudents.length).toFixed(1) 
        : '0';

    const topStudent = filteredStudents.reduce((top, current) => 
        (parseInt(current.points) > (parseInt(top?.points) || 0) ? current : top), null);
    topPerformerEl.textContent = topStudent ? topStudent.name : '-';

    const allEvents = filteredStudents.flatMap(student => student.events);
    totalEventsEl.textContent = new Set(allEvents).size;
}

let currentEditUsername = null;

function renderTable(data) {
    tableBody.innerHTML = '';
    data.forEach((student, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${student.username}</td>
            <td>${student.name}</td>
            <td>${student.year}</td>
            <td>${student.branch}</td>
            <td><span class="badge bg-primary fs-6">${student.points}</span></td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn me-1" data-username="${student.username}">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-username="${student.username}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(tr);
    });

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', openEditStudentModal);
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', confirmDeleteStudent);
    });
}

function renderEventsList(data) {
    const eventsList = document.getElementById('eventsList');
    eventsList.innerHTML = '';
    data.forEach(event => {
        const li = document.createElement('li');
        li.className = 'events-list-item';
        li.innerHTML = `
                <strong>${event.title}</strong>
            <span class="event-date">${event.event_date}</span>
        `;
        eventsList.appendChild(li);
    });
}

function openEditStudentModal(e) {
    const username = e.currentTarget.getAttribute('data-username');
    const student = students.find(s => s.username === username);
    if (!student) return;

    currentEditUsername = username;
    const regText = document.getElementById('editRegNumberText');
    if (regText) regText.textContent = student.username;

    const nameInput = document.getElementById('editStudentNameInput');
    if (nameInput) nameInput.value = student.name;

    const badge = document.getElementById('editCurrentPointsBadge');
    if (badge) badge.textContent = student.points + ' pts';

    const pointsInput = document.getElementById('editPointsInput');
    if (pointsInput) pointsInput.value = '';

    // Populate events dropdown
    const eventSelect = document.getElementById('editEventSelect');
    if (eventSelect) {
        eventSelect.innerHTML = '<option value="0">-- General House / Bonus Points --</option>';
        if (typeof events !== 'undefined' && Array.isArray(events)) {
            events.forEach(ev => {
                const option = document.createElement('option');
                option.value = ev.event_id || 0;
                option.textContent = ev.title + (ev.event_date ? ` (${ev.event_date})` : '');
                eventSelect.appendChild(option);
            });
        }
    }

    const modalEl = document.getElementById('editStudentModal');
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    loadPointsBreakdown(username);
}

function loadPointsBreakdown(studentId) {
    const tbody = document.getElementById('breakdownTableBody');
    if (!tbody) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-3 text-muted">
                <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                Loading points history breakdown...
            </td>
        </tr>
    `;

    fetch(`./get_student_points_breakdown.php?student_id=${encodeURIComponent(studentId)}`)
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-3">${data.error || 'Failed to load breakdown'}</td></tr>`;
            return;
        }

        const badge = document.getElementById('editCurrentPointsBadge');
        if (badge) badge.textContent = `${data.total_points} pts`;

        const s = students.find(item => item.username === studentId);
        if (s) {
            s.points = data.total_points;
            filterTable();
        }

        if (!data.breakdown || data.breakdown.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4"><i class="bi bi-info-circle me-1"></i> No points breakdown found for this student yet. Award points below!</td></tr>`;
            return;
        }

        tbody.innerHTML = '';
        data.breakdown.forEach(item => {
            const tr = document.createElement('tr');
            const roleBadgeClass = item.type === 'winner' ? 'bg-success' : (item.type === 'organizer' ? 'bg-info' : 'bg-primary');
            tr.innerHTML = `
                <td class="fw-bold">${item.event_title || 'General Activity'}</td>
                <td><span class="badge ${roleBadgeClass}">${item.role_label}</span></td>
                <td class="fw-bold text-success">+${item.points} pts</td>
                <td class="small text-muted">${item.event_date || '-'}</td>
                <td class="text-center">
                    <button class="btn btn-sm btn-outline-danger py-0 px-2 delete-point-record-btn" 
                            data-record-id="${item.id}" data-type="${item.type}" title="Delete this point entry">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.querySelectorAll('.delete-point-record-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const recordId = this.getAttribute('data-record-id');
                const type = this.getAttribute('data-type');
                deletePointRecord(studentId, recordId, type);
            });
        });
    })
    .catch(err => {
        console.error('Error fetching points breakdown:', err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-3">Error loading points history</td></tr>`;
    });
}

function deletePointRecord(studentId, recordId, type) {
    if (!confirm('Are you sure you want to remove this point entry?')) return;

    fetch('./delete_point_record.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ student_id: studentId, record_id: recordId, type: type })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            notyf.success('Point entry deleted.');
            loadPointsBreakdown(studentId);
        } else {
            notyf.error(data.error || 'Failed to delete point entry');
        }
    })
    .catch(err => {
        console.error('Error deleting point record:', err);
        notyf.error('Network error deleting point entry');
    });
}

function confirmDeleteStudent(e) {
    const username = e.currentTarget.getAttribute('data-username');
    const studentIndex = students.findIndex(s => s.username === username);
    if (studentIndex === -1) return;

    const student = students[studentIndex];
    if (!confirm(`Are you sure you want to remove ${student.name} (${student.username}) from your house records?`)) {
        return;
    }

    fetch('./delete_student.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            student_id: username
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            students.splice(studentIndex, 1);
            filterTable();
            notyf.success(`Removed ${student.name} from house list.`);
        } else {
            notyf.error(data.error || 'Failed to remove student');
        }
    })
    .catch(err => {
        console.error('Error deleting student:', err);
        notyf.error('Network error. Failed to remove student.');
    });
}

function filterTable() {
    const searchTerm = searchInput.value.toLowerCase();
    const yearTerm = yearFilter.value;
    const classTerm = classFilter.value;
    const minPoints = parseInt(minPointsRange.value);
    const maxPoints = parseInt(maxPointsRange.value);

    pointsRangeLabel.textContent = `${minPoints} - ${maxPoints}`;

    // Update range progress
    const trackWidth = 100;
    const progressWidth = ((maxPoints - minPoints) / 100) * trackWidth;
    const progressLeft = (minPoints / 100) * trackWidth;
    
    rangeProgress.style.width = `${progressWidth}%`;
    rangeProgress.style.left = `${progressLeft}%`;

    const filteredStudents = students.filter(student => {
        const matchSearch = student.username.toLowerCase().includes(searchTerm) ||
                            student.name.toLowerCase().includes(searchTerm);
        const matchYear = !yearTerm || student.year === yearTerm;
        const matchClass = !classTerm || student.branch === classTerm;
        const matchPoints = parseInt(student.points) >= minPoints && parseInt(student.points) <= maxPoints;

        return matchSearch && matchYear && matchClass && matchPoints;
    });

    renderTable(filteredStudents);
    updateStatistics(filteredStudents);
}

// Export Functions
document.getElementById('exportExcel').addEventListener('click', () => {
    // Filter out only the required fields
    const filteredStudents = students.map(({ name, username, year, branch, points }) => ({
        name, username, year, branch, points
    }));

    const worksheet = XLSX.utils.json_to_sheet(filteredStudents);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Students");
    XLSX.writeFile(workbook, "StudentPointsDashboard.xlsx");
    notyf.success('Excel Export Successful');
});


document.getElementById('exportPDF').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Title for the PDF
    doc.text("Student Points Dashboard", 10, 10);

    // Filter only required fields
    const filteredStudents = students.map(({ name, username, year, branch, points }) => 
        [name, username, year, branch, points]
    );

    // Define table headers
    const headers = [["Name", "Username", "Year", "Branch", "Points"]];

    // Generate table using autoTable plugin
    doc.autoTable({
        head: headers,
        body: filteredStudents,
        startY: 20,  // Adjusts table position
    });

    // Save the PDF
    doc.save("StudentPointsDashboard.pdf");
    notyf.success("PDF Export Successful");
});


// Range Inputs Event Listeners
minPointsRange.addEventListener('input', function () {
    if (parseInt(this.value) > parseInt(maxPointsRange.value)) {
        this.value = maxPointsRange.value;
    }
    filterTable();
});

maxPointsRange.addEventListener('input', function () {
    if (parseInt(this.value) < parseInt(minPointsRange.value)) {
        this.value = minPointsRange.value;
    }
    filterTable();
});

// Todo Input Event Listeners
addTodoBtn.addEventListener('click', addTodo);
todoInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') addTodo();
});

// Filter Event Listeners
searchInput.addEventListener('input', filterTable);
yearFilter.addEventListener('change', filterTable);
classFilter.addEventListener('change', filterTable);

// Refresh breakdown button listener
const refreshBtn = document.getElementById('refreshBreakdownBtn');
if (refreshBtn) {
    refreshBtn.addEventListener('click', function() {
        if (currentEditUsername) loadPointsBreakdown(currentEditUsername);
    });
}

// Edit Student Form Submit Listener
const editStudentForm = document.getElementById('editStudentForm');
if (editStudentForm) {
    editStudentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!currentEditUsername) return;

        const student = students.find(s => s.username === currentEditUsername);
        if (!student) return;

        const newName = document.getElementById('editStudentNameInput').value.trim();
        const newPoints = parseInt(document.getElementById('editPointsInput').value) || 0;
        const eventId = parseInt(document.getElementById('editEventSelect').value) || 0;
        const role = document.getElementById('editRoleSelect').value;

        if (newPoints <= 0) {
            notyf.error('Please enter points greater than 0');
            return;
        }

        const saveBtn = document.getElementById('saveStudentPointsBtn');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Saving...';

        fetch('./update_student_points.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                student_id: currentEditUsername,
                name: newName,
                points: newPoints,
                event_id: eventId,
                role: role
            })
        })
        .then(res => res.json())
        .then(data => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Award Points & Save';
            if (data.success) {
                student.name = data.name;
                student.points = data.new_points;

                document.getElementById('editPointsInput').value = '';
                filterTable();
                loadPointsBreakdown(currentEditUsername);

                notyf.success(`Successfully awarded ${newPoints} pts to ${data.name}`);
            } else {
                notyf.error(data.error || 'Failed to update points');
            }
        })
        .catch(err => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="bi bi-plus-lg me-1"></i> Award Points & Save';
            console.error('Error updating student points:', err);
            notyf.error('Network error. Failed to update points.');
        });
    });
}

// Initialize the dashboard
document.addEventListener('DOMContentLoaded', () => {
    renderTable(students);
    updateStatistics(students);
    renderEventsList(events);
    loadTodos();
});
        

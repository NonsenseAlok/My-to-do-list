<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        #new-task {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }
        button {
            margin-right: 10px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f3f3f3;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        li.completed {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .btn-danger {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>To Do List</h1>
        <div class="input-group mb-3">
            <input type="text" id="new-task" class="form-control" placeholder="Enter new task" aria-label="Enter new task" aria-describedby="add-task-btn">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="add-task-btn" onclick="addTask()">Add Task</button>
                <button class="btn btn-secondary" type="button" onclick="loadTasks(true)">Show All Tasks</button>
            </div>
        </div>
        <ul id="task-list"></ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadTasks();

            $('#new-task').keypress(function(event) {
                if (event.which === 13) {
                    addTask();
                }
            });
        });

        function loadTasks(showAll = false) {
            let url = '/tasks';
            if (showAll) {
                url += '?all=true';
            }

            $.get(url, function(tasks) {
                $('#task-list').empty();
                tasks.forEach(task => {
                    addTaskToUI(task);
                });
            });
        }

        function addTask() {
            var task = $('#new-task').val();
            if (task) {
                $.post('/tasks', { task: task, completed: 0 }, function(newTask) {
                    $('#new-task').val('');
                    loadTasks();
                }).fail(function(xhr, status, error) {
                    console.error('Error adding task:', error);
                });
            }
        }

        function addTaskToUI(task) {
            var taskItem = $('<li></li>').text(task.task).addClass(task.completed ? 'completed' : '');
            var deleteButton = $('<button></button>').text('Delete').addClass('btn btn-danger').click(function() {
                if (confirm('Are you sure to delete this task?')) {
                    deleteTask(task.id);
                }
            });

            taskItem.append(deleteButton);

            if (!task.completed) {
                var completeButton = $('<button></button>').text('Complete').addClass('btn btn-success').click(function() {
                    completeTask(task.id);
                });
                taskItem.append(completeButton);
            } else {
                taskItem.append($('<span></span>').text('(Completed)').addClass('text-success'));
            }

            $('#task-list').append(taskItem);
        }

        function deleteTask(id) {
            $.ajax({
                url: '/tasks/' + id,
                type: 'DELETE',
                success: function() {
                    loadTasks();
                }
            });
        }

        function completeTask(id) {
            $.ajax({
                url: '/tasks/' + id,
                type: 'PUT',
                data: { completed: 1 },
                success: function() {
                    loadTasks();
                }
            }).fail(function(xhr, status, error) {
                console.error('Error completing task:', error);
            });
        }
    </script>
</body>
</html>

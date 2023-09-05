@extends('layouts.app') {{-- Assuming you have a master layout --}}

@section('content')
<div class="container">
    <h1>Show All Task</h1>
    <hr>
    <h2>Create Task</h2>
    <form class="d-flex" data-action={{ route('store-task') }} method="Post" id="add-task-form">
        @csrf
        <input class="form-control me-2" type="text" id="task-input" name="task" placeholder="Add Task"
            aria-label="task">
        <button class="btn btn-outline-success me-2" type="submit">Add</button>
    </form>
    <hr>
    <h2>Task List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th class="col-md-6">Task</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr>
                <td>
                    <input type="checkbox" class="complete-task" data-task-id="{{ $task->id }}" {{ $task->is_completed ?
                    ' checked' : '' }}>
                </td>
                <td>{{ $task->task }}</td>
                <td>{{ $task->is_completed ? 'Completed' : 'Pending'}} </td>
                <td>{{ $task->created_at->diffForHumans() }}</td>
                <td>
                    <button class="btn btn-danger delete-task" data-task-id="{{ $task->id }}">Delete Task</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('footer-scripts')
<script>
    $(document).ready(function () {
        $('#add-task-form').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission behavior
            const url = $(this).data('action');
            const task = $('#task-input').val();

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: '{{ csrf_token() }}',
                    task: task
                },
                success: function (response) {
                    if (response) {
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                        }).then((value) => {
                            // Append the new task to the task list
                            const newRow = `
                                <tr>
                                    <td>
                                        <input type="checkbox" class="complete-task" data-task-id="${response.task.id}">
                                    </td>
                                    <td>${response.task.task}</td>
                                    <td>${response.task.is_completed ? 'Completed' : 'Pending'}</td>
                                    <td>${response.task.created_at}</td>
                                    <td>
                                        <button class="btn btn-danger delete-task" data-task-id="${response.task.id}">Delete Task</button>
                                    </td>
                                </tr>
                            `;
                            $('tbody').prepend(newRow);

                            // Clear the task input field
                            $('#task-input').val('');
                        });
                    }
                },
                error: function (error) {
                    console.error('Operation Failed:', error);
                }
            });
        });

        $('.complete-task').change(function () {
            const taskId = $(this).data('task-id');
            const isChecked = $(this).prop('checked');
            
            $.ajax({
                type: 'PUT',
                url: `/tasks/${taskId}`,
                data: {
                    _token: '{{ csrf_token() }}',
                    is_completed: isChecked
                },
                success: function (response) {
                    if(response.message) {
                        swal({
                                title: "Success!",
                                text: response.message,
                                icon: "success",
                        }).then((value) => {
                            window.location.href = "{{ route('list-task') }}";
                        });
                    }
                },
                error: function (error) {
                    console.error('operation Failed:', error);
                }
            });
        });

        $('.delete-task').click(function () {
            const taskId = $(this).data('task-id');
            swal({
                title: "Are you sure you want to delete this task",
                text: "Once deleted, You won't be able to revert this!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                    if (willDelete) {
                        console.log(taskId );
                        $.ajax({
                            type: 'DELETE',
                            url: `/tasks/${taskId}`,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                // Remove the row from the table on successful deletion
                                if(response.message) {
                                    swal({
                                            title: "Success!",
                                            text: response.message,
                                            icon: "success",
                                    }).then((value) => {
                                        window.location.href = "{{ route('list-task') }}";
                                    });
                                }
                                // swal("Deleted!", {
                                //     icon: "success",
                                // });
                            },
                            error: function (error) {
                                console.error('operation Failed:', error);
                            }
                        });
                    } else {
                        swal("Not Deleted!");
                    }
            });
        });
    });
</script>
@endsection
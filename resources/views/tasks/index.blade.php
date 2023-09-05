@extends('layouts.app') {{-- Assuming you have a master layout --}}

@section('content')
<div class="container">
    <h1>Show All Task</h1>
    <hr>
    <h2>Create Task</h2>
    <form class="d-flex" id="task" action={{ route('store-task') }} method="Post">
        <input class="form-control me-2" type="text" name="task" placeholder="Add Task" aria-label="Search">
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
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $task->type }}</td>
                <td>{{ $task->is_completed ? 'Pending' : 'Completed'}} </td>
                <td>{{ $task->created_at->diffForHumans() }}</td>
                <td>delete icon</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
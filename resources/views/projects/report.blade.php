<!DOCTYPE html>
<html>

<head>
    <title>Projects Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Projects Report</h1>

    <h2>Active Projects</h2>
    @if ($activeProjects->isEmpty())
        <p>No active projects found.</p>
    @else
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            @foreach ($activeProjects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->start_date ? $project->start_date->format('F j, Y') : 'N/A' }}</td>
                    <td>{{ $project->end_date ? $project->end_date->format('F j, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h2>Completed Projects</h2>
    @if ($completedProjects->isEmpty())
        <p>No completed projects found.</p>
    @else
        <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            @foreach ($completedProjects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->start_date ? $project->start_date->format('F j, Y') : 'N/A' }}</td>
                    <td>{{ $project->end_date ? $project->end_date->format('F j, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    <h2>Open Tasks</h2>
    <p>There are currently {{ $openTasksCount }} open tasks.</p>

    <h2>All Tasks</h2>
    @if ($tasks->isEmpty())
        <p>No tasks found.</p>
    @else
        <table>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Priority</th>
                <th>Due Date</th>
            </tr>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ ucfirst($task->status) ?? 'No Status' }}</td>
                    <td>{{ $task->assigned_to ? $task->assignedTo->name : 'Unassigned' }}</td>
                    <td>{{ $task->priority ?? 'No Priority' }}</td>
                    <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('F j, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </table>
    @endif
</body>

</html>
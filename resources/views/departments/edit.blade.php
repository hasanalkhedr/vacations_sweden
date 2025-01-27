<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Department</title>
</head>
<body>
<form method="POST" action="{{ route('departments.update', ['department' => $department]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <label for="name">Department Name</label>
        <input
            type="text"
            name="name"
            value="{{ $department->name }}"
        />
        <select name='manager_id'>
            <option value="" disabled>Choose HEAD</option>
            @if(count($employees))
                @foreach ($employees as $employee)
                    @if($employee->id === $department->manager_id)
                        <option value="{{ $employee->id }}" selected>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @else
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div>

</form>
</body>
</html>

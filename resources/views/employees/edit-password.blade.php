<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Employee</title>
</head>
<body>
<form method="POST" action="{{ route('employees.updatePassword',  ['employee' => $employee->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <label for="current_password">Current Password</label>
        <input
            type="password"
            name="current_password"
        />
        <label for="new_password">New Password</label>
        <input
            type="password"
            name="new_password"
        />
        <label for="new_password_confirmation">New Confirm Password</label>
        <input
            type="password"
            name="new_password_confirmation"
        />
    </div>
    <button>Submit</button>
</form>
</body>
</html>

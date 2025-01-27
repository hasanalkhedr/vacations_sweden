<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{__("Department")}}</title>
</head>
<body>
<form method="POST" action="{{ route('departments.store') }}" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="name" class="blue-color">{{__("Department Name")}}</label>
        <input
            type="text"
            name="name"
            value="{{ old('name') }}"
        />
    </div>

</form>
</body>
</html>

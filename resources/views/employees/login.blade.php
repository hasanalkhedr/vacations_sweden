<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>

<body>
<div class="w-screen h-screen flex justify-center items-center">
    <form class="p-10 bg-white rounded-xl drop-shadow-lg space-y-5" method="POST" action="{{ route('employees.authenticate') }}">
        @csrf
        <h1 class="text-center text-3xl">Sign In</h1>
        <div class="flex flex-col space-y-2">
            <label class="text-sm" for="email">Email</label>
            <input class="w-96 px-3 py-2 rounded-md border border-slate-400" type="email" placeholder="Your Email"
                   name="email" id="email">
            @error('email')
            <p class="text-red-500 text-xs mt-1"></p>{{ $message }}
            @enderror
        </div>
        <div class="flex flex-col space-y-2">
            <label class="text-sm" for="password">Password</label>
            <input class="w-96 px-3 py-2 rounded-md border border-slate-400" type="password"
                   placeholder="Your Password" name="password" id="password">
            @error('password')
            <p class="text-red-500 text-xs mt-1"></p>{{ $message }}
            @enderror
        </div>

        <button class="w-full px-10 py-2 bg-blue-600 text-white rounded-md
            hover:bg-blue-500 hover:drop-shadow-md duration-300 ease-in" type="submit">
            Sign In
        </button>
    </form>
</div>
</body>
</html>

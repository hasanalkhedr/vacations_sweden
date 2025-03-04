<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Login') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favico32.png') }}">
    <script src="https://cdn.tailwindcss.com/"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <section class="h-screen">
        <div class="container px-6 py-12 h-full m-auto">
            <div class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800">
                <div class="md:w-8/12 lg:w-6/12 mb-12 md:mb-0">
                    <img src="https://assets.website-files.com/626f5d0ae6c15cea8c2dd5dd/6322a454df6ee26d107fa074_Banner-0014.png"
                        class="w-full" alt="Phone image" />
                </div>
                <div class="md:w-8/12 lg:w-5/12 lg:ml-20">
                    <form method="POST" action="{{ route('employees.authenticate') }}">
                        @csrf

                        <!-- Logo -->
                        <div class="flex justify-center">
                            <img src="{{ asset('assets/images/logo-IF.png') }}" class="w-2/4" alt="Logo" />
                        </div>

                        <div
                            class="flex items-center my-4 before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5">
                            <p class="text-center font-semibold mx-4 mb-0">{{ __('Login') }}</p>
                        </div>
                        <!-- Email input -->

                        <div class="mb-6">
                            <input
                                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none @error('email') is-invalid @enderror"
                                autofocus value="{{ old('email') }}" type="email" required name="email"
                                autocomplete="email" placeholder="{{ __('Email Address') }}" />

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ __($message) }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password input -->
                        <div class="mb-6">
                            <input
                                class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                                type="Password" name="password" placeholder="{{ __('Password') }}" />
                        </div>

                        <!-- Submit button -->
                        <button type="submit"
                            class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out w-full"
                            data-mdb-ripple="true" data-mdb-ripple-color="light">
                            {{ __('Login') }}
                        </button>

                        <div class="powered-by">
                            Powered By <a class="IST" href="https://isolutionleb.com/">IST</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>

<x-sidebar>
    <div class="m-4">
        <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="first_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="first_name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">First Name</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="last_name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="last_name" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Last Name</label>
                </div>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="email" name="email" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="email" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email Address</label>
            </div>
            <div class="relative z-0 mb-6 w-full group">
                <input type="password" name="password_confirmation" id="floating_password" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="password_confirmation" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Confirm Password</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 mb-6 w-full group">
                    <input type="text" name="phone_number" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="phone_number" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number (+96170707070)</label>
                </div>
                <div class="relative z-0 mb-6 w-full group">
                    <input type="number" name="nb_of_days" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="nb_of_days" class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Number of Days Off</label>
                </div>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
        </form>
    </div>
{{--<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">--}}
{{--    @csrf--}}
{{--    <div>--}}
{{--        <label for="first_name">First Name</label>--}}
{{--        <input--}}
{{--            type="text"--}}
{{--            name="first_name"--}}
{{--            value="{{ old('first_name') }}"--}}
{{--        />--}}
{{--        <label for="last_name">Last Name</label>--}}
{{--        <input--}}
{{--            type="text"--}}
{{--            name="last_name"--}}
{{--            value="{{ old('last_name') }}"--}}
{{--        />--}}
{{--        <label for="email">Email</label>--}}
{{--        <input--}}
{{--            type="email"--}}
{{--            name="email"--}}
{{--            value="{{ old('email') }}"--}}
{{--        />--}}
{{--        <label for="password">Password</label>--}}
{{--        <input--}}
{{--            type="password"--}}
{{--            name="password"--}}
{{--            value="{{ old('password') }}"--}}
{{--        />--}}
{{--        <label for="password_confirmation">Password Confirmation</label>--}}
{{--        <input--}}
{{--            type="password"--}}
{{--            name="password_confirmation"--}}
{{--            value="{{ old('password_confirmation') }}"--}}
{{--        />--}}
{{--        <label for="phone_number">Phone Number</label>--}}
{{--        <input--}}
{{--            type="text"--}}
{{--            name="phone_number"--}}
{{--            value="{{ old('phone_number') }}"--}}
{{--        />--}}
{{--        <label for="nb_of_days">Number of Days Off</label>--}}
{{--        <input--}}
{{--            type="number"--}}
{{--            name="nb_of_days"--}}
{{--            value="{{ old('nb_of_days') }}"--}}
{{--        />--}}
{{--        <select name='role_id' id="role_id" onchange="enableOrDisableDepartment(this);">--}}
{{--            <option value="" disabled>Choose Role</option>--}}

{{--            @if(count($roles))--}}
{{--                @foreach ($roles as $role)--}}
{{--                    @unless($role->id == \Spatie\Permission\Models\Role::findByName('supervisor')->id)--}}
{{--                    <option value="{{ $role->id }}">{{ $role->name }}</option>--}}
{{--                    @endunless--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        </select>--}}
{{--        <select name='department_id' id="department_id">--}}
{{--            <option value="" disabled>Choose Department</option>--}}
{{--            <option value="">None</option>--}}
{{--            @if(count($departments))--}}
{{--                @foreach ($departments as $department)--}}
{{--                    <option value="{{ $department->id }}">{{ $department->name }}</option>--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--        </select>--}}
{{--    </div>--}}
{{--    <button>Submit</button>--}}
{{--</form>--}}

{{--<script type="text/javascript">--}}
{{--    function enableOrDisableDepartment(that) {--}}
{{--        var select = document.getElementById('role_id');--}}
{{--        var role = select.options[select.selectedIndex].text;--}}
{{--        if (role == "employee") {--}}
{{--            document.getElementById("department_id").disabled = false;--}}
{{--        } else {--}}
{{--            document.getElementById("department_id").disabled = true;--}}
{{--        }--}}
{{--    }--}}
{{--</script>--}}

</x-sidebar>>

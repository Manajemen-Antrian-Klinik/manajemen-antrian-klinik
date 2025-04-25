<!DOCTYPE html>
<html class="h-full bg-[#FBFBFB]" lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite('resources/css/app.css')
        <title>Klinik Hestia Medika | {{ $title }}</title>
    </head>

    <body class="h-full">
        <div class="flex h-screen flex-col justify-center">
            {{-- Logo --}}
            <div class="flex h-screen flex-col justify-center">
                <!-- Logo -->
        <div class="sm:mx-auto sm:w-auto sm:max-w-lg mb-6">
            <div class="flex items-start gap-3">
                <!-- Kotak H Besar -->
                <div class="border-2 border-gray-500 flex items-center justify-center w-10 h-14">
                    <span class="text-2xl font-bold text-gray-500">H</span>
                </div>
                <!-- Tulisan -->
                <div>
                    <!-- KLINIK -->
                    <div class="text-gray-500 tracking-widest text-lg font-semibold">
                        KLINIK
                    </div>
                    <!-- HESTIA MEDIKA -->
                    <div class="flex gap-2 text-gray-500 tracking-widest text-lg font-semibold mt-1">
                        <!-- HESTIA -->
                        <span class="relative flex items-center">
                            <span class="border-2 border-gray-500 w-5 h-5 flex items-center justify-center">
                                <span class="text-sm font-bold leading-none">H</span>
                            </span>
                            <span>ESTIA</span>
                        </span>

                        <!-- MEDIKA -->
                        <span class="relative">
                            <span>MEDIKA</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

            {{-- Form Register --}}
            <div class="mt-3 sm:mx-auto sm:w-full sm:max-w-lg">
                <form class="space-y-6" action="#" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block text-xl/6 font-medium text-gray-900">Name</label>
                        <div class="mt-1">
                            <input type="text" name="name" id="name" autofocus required placeholder="Name" value="{{ old('name') }}"
                                class="@error('name') is-invalid @enderror 
                                block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                    @error('name')
                        <div class="alert-danger mb-2 -mt-5 text-red-400 text-xs">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    

                    <div>
                        <label for="email" class="block text-xl/6 font-medium text-gray-900">Email address</label>
                        <div class="mt-1">
                            <input type="email" name="email" id="email" autocomplete="email" required placeholder="Email" value="{{ old('email') }}"
                                class="@error('email') is-invalid @enderror 
                                block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div> 
                    </div>
                    @error('email')
                        <div class="alert-danger mb-2 -mt-5 text-red-400 text-xs">
                            {{ $message }}
                        </div>
                    @enderror

                    <div>
                        <label for="password" class="block text-xl/6 font-medium text-gray-900">Password</label>
                        <div class="mt-1">
                            <input type="password" name="password" id="password" autocomplete="current-password" required placeholder="Password" value="{{ old('password') }}"
                                class="@error('password') is-invalid @enderror
                                block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                    @error('password')
                        <div class="alert-danger mb-2 -mt-5 text-red-400 text-xs">
                            {{ $message }}
                        </div>
                    @enderror
                    
                    <div>
                        <label for="password" class="block text-xl/6 font-medium text-gray-900">ConfirmPassword</label>
                        <div class="mt-1">
                            <input type="password" name="repeat_password" id="repeat_password" autocomplete="current-password" required placeholder="Confirm Password" value="{{ old('name') }}"
                                class="@error('repeat_password') is-invalid @enderror
                                block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        </div>
                    </div>
                    @error('repeat_password')
                        <div class="alert-danger mb-2 -mt-5 text-red-400 text-xs">
                            {{ $message }}
                        </div>
                    @enderror
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-[#6B5B95] px-3 py-1.5 text-lg/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Register
                        </button>
                    </div>
                </form>
            </div>
            <small class="d-block text-center mt-3">Already Registered? <a href="/login" class="font-semibold text-gray-900 hover:text-indigo-500">Please Login</a></small>

        </div>
    </body>

</html>

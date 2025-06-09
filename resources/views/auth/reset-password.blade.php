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

        <!-- Logo Section -->
        <div class="sm:mx-auto sm:w-auto sm:max-w-lg mb-6">
            <div class="flex items-start gap-3">
                <!-- Large H Box -->
                <div class="border-2 border-gray-500 flex items-center justify-center w-10 h-14">
                    <span class="text-2xl font-bold text-gray-500">H</span>
                </div>
                <!-- Clinic Name -->
                <div>
                    <div class="text-gray-500 tracking-widest text-lg font-semibold">
                        KLINIK
                    </div>
                    <div class="flex gap-2 text-gray-500 tracking-widest text-lg font-semibold mt-1">
                        <span class="relative flex items-center">
                            <span class="border-2 border-gray-500 w-5 h-5 flex items-center justify-center">
                                <span class="text-sm font-bold leading-none">H</span>
                            </span>
                            <span>ESTIA</span>
                        </span>
                        <span>MEDIKA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reset Password Form -->
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your new password below.</p>
            </div>

            <form class="space-y-6" action="/reset-password" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="email" class="block text-xl font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" value="{{ $email }}" readonly
                            class="block w-full rounded-md bg-gray-100 px-3 py-1.5 text-base text-gray-900 border border-gray-300 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xl font-medium text-gray-900">New Password</label>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" required autocomplete="new-password"
                            class="@error('password') is-invalid @enderror block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 border border-gray-300 placeholder-gray-400 focus:outline-indigo-600 focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                            placeholder="Enter your new password">
                    </div>
                </div>
                @error('password')
                <div class="alert-danger mb-5 -mt-3 text-red-400 text-xs">
                    {{ $message }}
                </div>
                @enderror

                <div>
                    <label for="password_confirmation" class="block text-xl font-medium text-gray-900">Confirm Password</label>
                    <div class="mt-2">
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                            class="block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 border border-gray-300 placeholder-gray-400 focus:outline-indigo-600 focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                            placeholder="Confirm your new password">
                    </div>
                </div>

                @error('email')
                <div class="alert-danger mb-5 text-red-400 text-xs">
                    {{ $message }}
                </div>
                @enderror

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-[#6B5B95] px-3 py-1.5 text-lg font-semibold text-white shadow hover:bg-indigo-500 focus:outline-indigo-600 focus:ring-2 focus:ring-indigo-600">
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="/login" class="text-sm font-semibold text-gray-900 hover:text-indigo-500">
                    ← Back to Login
                </a>
            </div>
        </div>
    </div>
</body>

</html>
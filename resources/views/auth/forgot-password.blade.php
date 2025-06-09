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

        <!-- Forgot Password Form -->
        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-lg">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Forgot Password?</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            @if (session()->has('status'))
                <div id="status_alert" class="absolute top-10 right-0 p-4 mb-4 font-semibold text-sm max-w-sm bg-green-500 text-white rounded-s-2xl flex items-center" role="alert">
                    {{ session('status') }}
                    <button type="button" class="ms-3 -mx-1.5 -my-1.5 text-white rounded-full hover:bg-white hover:text-green-500 p-1.5 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#status_alert" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            @endif

            <form class="space-y-6" action="/forgot-password" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-xl font-medium text-gray-900">Email address</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" autofocus required autocomplete="email" value="{{ old('email') }}"
                            class="@error('email') is-invalid @enderror block w-full rounded-md bg-[#C5BAFF4D] px-3 py-1.5 text-base text-gray-900 border border-gray-300 placeholder-gray-400 focus:outline-indigo-600 focus:ring-2 focus:ring-indigo-600 sm:text-sm"
                            placeholder="Enter your email address">
                    </div>
                </div>
                @error('email')
                <div class="alert-danger mb-5 -mt-3 text-red-400 text-xs">
                    {{ $message }}
                </div>
                @enderror

                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-[#6B5B95] px-3 py-1.5 text-lg font-semibold text-white shadow hover:bg-indigo-500 focus:outline-indigo-600 focus:ring-2 focus:ring-indigo-600">
                        Send Reset Link
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan semua tombol dengan atribut data-dismiss-target
            const closeButtons = document.querySelectorAll('[data-dismiss-target]');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-dismiss-target');
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        targetElement.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>
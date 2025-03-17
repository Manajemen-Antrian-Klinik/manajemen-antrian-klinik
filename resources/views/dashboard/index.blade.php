@vite(['resources/css/app.css', 'resources/js/app.js'])
<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Klinik Hestia Medika</title>
        @vite('resources/css/app.css')
    </head>

    <body class="min-h-screen bg-gradient-to-r from-blue-100 to-blue-200 flex flex-col">

        <!-- Header -->
        <header class="flex justify-between items-center p-4">
            <!-- Logo Section -->
            <div class="flex items-center gap-3 ml-10">
                <div class="border-2 border-gray-500 flex items-center justify-center w-10 h-11.5">
                    <span class="text-2xl font-bold text-gray-500">H</span>
                </div>
                <div>
                    <div class="text-gray-500 tracking-widest text-lg font-semibold">
                        KLINIK
                    </div>
                    <div class="flex gap-2 text-gray-500 tracking-widest text-lg font-semibold">
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

            @auth
            <div x-data="{ open: false }" class="relative inline-block text-left">
                <div>
                    <button @click="open = !open" type="button" 
                        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-blue-200 px-3 py-2 text-sm font-semibold text-gray-500 shadow-sm hover:bg-blue-100" 
                        id="menu-button" aria-expanded="true" aria-haspopup="true">
                        Welcome back, {{ auth()->user()->name }}
                        <svg class="-mr-1 w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            
                <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-blue-200/90 shadow-lg focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                    <div class="py-1" role="none">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-500 hover:bg-blue-100 hover:text-gray-500 rounded-md" role="menuitem" tabindex="-1">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-500 hover:bg-blue-100 hover:text-gray-500 rounded-md" role="menuitem" tabindex="-1">Check</a>
                        <form method="POST" action="/logout" role="none">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-500 hover:bg-blue-100 hover:text-gray-500 rounded-md" role="menuitem" tabindex="-1">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
            
            
            @else
            <div class="space-x-4 mr-10">
                <button class="text-[#6B5B95] hover:text-gray-900"><a href="/register">Sign Up</a></button>
                <a href="/login" class="bg-[#6B5B95] hover:bg-[#6B5B95] text-white px-4 py-2 rounded">Login</a>
            </div>
            @endauth
        </header>

        <!-- Main Content -->
        <main class="flex flex-1 justify-between items-center ml-10 pl-4 pt-4 pb-4 pr-25 mr-20">
            
        </main>

        <!-- Footer -->
        <footer class="bg[#FBFBFB] pr-4 pb-1 pt-1 pl-4 flex justify-between items-center ">
            <div class="text-sm font-bold ml-10">
                <span class="text-blue-800">KLINIK</span> <span class="text-gray-800">HESTIA MEDIKA</span>
            </div>
            <div class="text-right text-sm mr-10">
                <p class="font-bold">Alamat dan Kontak</p>
                <p>Jl. Kebangsaan No </p>
                <p>+6281234567890</p>
                <p>hestiamedika@gmail.com</p>
            </div>
        </footer>
    </body>
</html>
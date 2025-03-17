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
            
            <div class="space-x-4 mr-10">
                <button class="text-[#6B5B95] hover:text-gray-900"><a href="/register">Sign Up</a></button>
                <a href="/login" class="bg-[#6B5B95] hover:bg-[#6B5B95] text-white px-4 py-2 rounded">Login</a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex flex-1 justify-between items-center ml-10 pl-4 pt-4 pb-4 pr-25 mr-20">
            <div class="max-w-md space-y-4">
                <h1 class="text-3xl font-bold text-gray-800">SELAMAT DATANG!</h1>
                <h2 class="text-2xl font-bold text-blue-800">KLINIK HESTIA MEDIKA</h2>
                <h3 class="text-xl font-bold text-gray-800">SIAP MELAYANI ANDA</h3>
                <p class="text-gray-600">
                    Kami menyediakan layanan kesehatan yang cepat, nyaman, dan terpercaya.
                </p>
            </div>
            
            <div class="relative w-64 h-64">
                <!-- Lingkaran Background -->
                <div class="absolute inset-0 rounded-full bg-[#C5BAFF]/30"></div>
            
                <!-- Gambar Dokter -->
                <img src="{{ asset('storage/img/doctor.jpg') }}" alt="Dokter"
                    class="absolute left-1/2 -translate-x-1/2 bottom-0 w-64 h-auto z-10 rounded-b-full object-cover" />
            </div>
            
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

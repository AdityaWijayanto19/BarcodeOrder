<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Login</h1>
            <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda</p>
        </div>

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="contoh@email.com"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password" name="password" required placeholder="********" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm
                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none">
            </div>

            <!-- Button -->
            <button type="submit" class="w-full rounded-lg bg-blue-600 py-2.5 text-white font-semibold
                       hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>

        <!-- Register -->
        {{--
        <div class="mt-6 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">
                Daftar
            </a>
        </div>
        --}}
    </div>

</body>

</html>
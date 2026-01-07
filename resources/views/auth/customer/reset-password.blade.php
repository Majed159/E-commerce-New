<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="text-3xl font-bold text-blue-600">
                    {{ config('app.name') }}
                </a>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Reset your password
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your new password below.
                </p>
            </div>

            <!-- Reset Password Form -->
            <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Hidden Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Hidden Email -->
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            New Password
                        </label>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               autofocus
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm New Password
                        </label>
                        <input id="password_confirmation"
                               type="password"
                               name="password_confirmation"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition font-semibold">
                        Reset Password
                    </button>
                </form>
            </div>

            <!-- Back to Login -->
            <p class="mt-6 text-center text-sm text-gray-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-indigo-500">
                    Sign in
                </a>
            </p>

            <!-- Back to Home -->
            <p class="mt-2 text-center text-sm text-gray-600">
                <a href="{{ route('home') }}" class="font-medium text-blue-600 hover:text-indigo-500">
                    ← Back to Home
                </a>
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Illustration -->
            <div class="hidden lg:flex flex-col justify-center items-center">
                <div class="relative w-full max-w-md">
                    <!-- Decorative Background -->
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-400 to-purple-600 rounded-3xl transform -rotate-6 blur-2xl opacity-20"></div>
                    
                    <!-- Main Illustration Card -->
                    <div class="relative bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-3xl p-12 text-white shadow-2xl">
                        <div class="space-y-8 text-center">
                            <!-- Icon -->
                            <div class="flex justify-center">
                                <div class="w-24 h-24 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Text -->
                            <div>
                                <h2 class="text-3xl font-bold mb-4">TaskFlow</h2>
                                <p class="text-lg opacity-90 mb-2">Manage your tasks efficiently</p>
                                <p class="text-sm opacity-75">Organize, prioritize, and achieve your goals</p>
                            </div>

                            <!-- Features -->
                            <div class="pt-6 space-y-3 text-sm">
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Track daily tasks</span>
                                </div>
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Build healthy habits</span>
                                </div>
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Boost productivity</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="flex justify-center items-center">
                <div class="w-full max-w-md">
                    <!-- Card -->
                    <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10">
                        <!-- Header -->
                        <div class="text-center mb-10">
                            <div class="inline-flex justify-center items-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                            <p class="text-gray-600">Sign in to your account to continue</p>
                        </div>

                        <!-- Session Status -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                                <p class="font-semibold mb-2">Login Failed</p>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <!-- Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition bg-gray-50 placeholder-gray-400"
                                    placeholder="Enter your email"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition bg-gray-50 placeholder-gray-400"
                                    placeholder="Enter your password"
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center">
                                <input 
                                    id="remember_me" 
                                    type="checkbox" 
                                    name="remember"
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                                />
                                <label for="remember_me" class="ms-3 text-sm text-gray-600 cursor-pointer">
                                    Remember me
                                </label>
                            </div>

                            <!-- Links -->
                            <div class="flex items-center justify-between text-sm">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                        Forgot password?
                                    </a>
                                @endif
                                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                                    Create account
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl mt-8"
                            >
                                Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-sm text-gray-600 mt-8">
                        © 2026 TaskFlow. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

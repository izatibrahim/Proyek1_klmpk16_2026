<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Manager</title>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Text -->
                            <div>
                                <h2 class="text-3xl font-bold mb-4">Join TaskFlow</h2>
                                <p class="text-lg opacity-90 mb-2">Get organized and productive</p>
                                <p class="text-sm opacity-75">Start managing your tasks today</p>
                            </div>

                            <!-- Features -->
                            <div class="pt-6 space-y-3 text-sm">
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Easy task management</span>
                                </div>
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Track your progress</span>
                                </div>
                                <div class="flex items-center justify-center gap-3">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Achieve your goals</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="flex justify-center items-center">
                <div class="w-full max-w-md">
                    <!-- Card -->
                    <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10">
                        <!-- Header -->
                        <div class="text-center mb-10">
                            <div class="inline-flex justify-center items-center w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h1>
                            <p class="text-gray-600">Join us and start organizing your tasks</p>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                                <p class="font-semibold mb-2">Registration Failed</p>
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('register') }}" class="space-y-6">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Full Name</label>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition bg-gray-50 placeholder-gray-400"
                                    placeholder="Enter your full name"
                                />
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-sm" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">Email Address</label>
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required 
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
                                    autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition bg-gray-50 placeholder-gray-400"
                                    placeholder="Create a strong password"
                                />
                                <p class="text-xs text-gray-500 mt-1">At least 8 characters</p>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">Confirm Password</label>
                                <input 
                                    id="password_confirmation" 
                                    type="password" 
                                    name="password_confirmation" 
                                    required 
                                    autocomplete="new-password"
                                    class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-indigo-500 focus:outline-none transition bg-gray-50 placeholder-gray-400"
                                    placeholder="Confirm your password"
                                />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-sm" />
                            </div>

                            <!-- Terms -->
                            <div class="flex items-start">
                                <input 
                                    type="checkbox" 
                                    id="terms" 
                                    class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer mt-1"
                                    required
                                />
                                <label for="terms" class="ms-3 text-sm text-gray-600 cursor-pointer">
                                    I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Terms and Conditions</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl mt-8"
                            >
                                Create Account
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">or</span>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <p class="text-center text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                Sign in here
                            </a>
                        </p>
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

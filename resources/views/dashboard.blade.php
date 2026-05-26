<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Welcome Card -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-lg p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="text-gray-500 text-sm mb-1">Good {{ \Carbon\Carbon::now()->hour < 12 ? 'Morning' : 'Afternoon' }}!</p>
                                <h1 class="text-3xl font-bold text-gray-900">{{ Auth::user()->name }}</h1>
                            </div>
                            <div class="w-12 h-12 bg-indigo-500 rounded-full flex items-center justify-center text-white text-xl">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="mb-6">
                            <p class="text-gray-600 text-lg font-semibold">You have <span class="text-4xl font-bold text-indigo-600">{{ $todaysTodosCount }}</span></p>
                            <p class="text-gray-600">task for today</p>
                        </div>
                    </div>
                </div>

                <!-- Team Members Card -->
                <div class="bg-white rounded-3xl shadow-lg p-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ count($teamMembers) }} Members</h3>
                    <div class="flex items-center gap-2 mb-4">
                        @foreach($teamMembers->take(6) as $member)
                        @endforeach
                        @if(count($teamMembers) > 6)
                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 text-xs font-bold">
                                +{{ count($teamMembers) - 6 }}
                            </div>
                        @endif
                    </div>
                    <button class="w-full bg-indigo-100 text-indigo-600 rounded-full py-2 hover:bg-indigo-200 transition">
                        <a href="{{ route('categories.index') }}" class="btn-styling">
                            Tambah Kategori
                        </a>
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Next Task / Calendar Section -->
                <div class="lg:col-span-2">
                    <!-- Next Task Card -->
                    @if($upcomingTodos->isNotEmpty())
                        <div class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-3xl shadow-lg p-8 mb-6 text-white">
                            <div class="flex items-start justify-between mb-6">
                                <div>
                                    <h2 class="text-2xl font-bold mb-2">Next Task</h2>
                                    <p class="text-indigo-100 text-sm">Upcoming</p>
                                </div>
                                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white bg-opacity-20 rounded-2xl p-4">
                                <p class="font-semibold text-lg mb-2">{{ $upcomingTodos[0]->title }}</p>
                                <p class="text-indigo-100 text-sm mb-3">{{ $upcomingTodos[0]->description ?? 'No description' }}</p>
                                @if($upcomingTodos[0]->deadline)
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $upcomingTodos[0]->deadline->format('M d, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Calendar Section -->
                    <div class="bg-white rounded-3xl shadow-lg p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Calendar</h2>
                        </div>

                        <!-- Month Display -->
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $currentMonth->format('F') }}</h3>
                        </div>

                        <!-- Weekday Headers -->
                        <div class="grid grid-cols-7 gap-2 mb-4">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <div class="text-center text-sm font-semibold text-gray-500">{{ $day }}</div>
                            @endforeach
                        </div>

                        <!-- Calendar Days -->
                        <div class="grid grid-cols-7 gap-2">
                            @php
                                $firstDay = $currentMonth->copy()->startOfMonth();
                                $lastDay = $currentMonth->copy()->endOfMonth();
                                $startDate = $firstDay->copy()->startOfWeek();
                                $endDate = $lastDay->copy()->endOfWeek();
                                $currentDate = $startDate->copy();
                                $today = \Carbon\Carbon::today();
                            @endphp

                            @while($currentDate <= $endDate)
                                @php
                                    $isToday = $currentDate->isToday();
                                    $isCurrent = $currentDate->month === $currentMonth->month;
                                    $todosCount = $monthTodos->filter(fn($todo) => $todo->deadline->isSameDay($currentDate))->count();
                                @endphp
                                <div class="text-center">
                                    <button class="w-full aspect-square rounded-lg text-sm font-medium transition 
                                        {{ $isToday ? 'bg-indigo-600 text-white' : ($isCurrent ? 'text-gray-900 hover:bg-gray-100' : 'text-gray-300') }}">
                                        {{ $currentDate->day }}
                                        @if($todosCount > 0)
                                            <div class="text-xs mt-1">
                                                <span class="inline-block w-1 h-1 {{ $isToday ? 'bg-white' : 'bg-indigo-500' }} rounded-full"></span>
                                            </div>
                                        @endif
                                    </button>
                                </div>
                                @php $currentDate->addDay() @endphp
                            @endwhile
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar - Plan & Tasks -->
                <div>
                    <!-- Plan Card -->
                    <div class="bg-white rounded-3xl shadow-lg p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900">Plan</h2>
                            <a href="{{ route('todos.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                View All
                            </a>
                        </div>

                        <div class="space-y-3">
                            @forelse($todayTodos->take(4) as $todo)
                                <div class="bg-gradient-to-r {{ $loop->iteration % 4 === 1 ? 'from-indigo-400 to-indigo-500' : ($loop->iteration % 4 === 2 ? 'from-yellow-400 to-yellow-500' : 'from-pink-400 to-pink-500') }} rounded-2xl p-4 text-white">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-sm mb-1">{{ $todo->title }}</h3>
                                            @if($todo->deadline)
                                                <p class="text-xs opacity-90">{{ $todo->deadline->format('g:i A') }}</p>
                                            @endif
                                        </div>
                                        <button class="text-lg hover:scale-110 transition">
                                            {{ $todo->is_done ? '✓' : '○' }}
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-gray-50 rounded-2xl p-4 text-center text-gray-500">
                                    <p class="text-sm">No tasks for today</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Habits Section -->
                    @if($habits->isNotEmpty())
                        <div class="bg-white rounded-3xl shadow-lg p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Habits</h2>
                            <div class="space-y-3">
                                @foreach($habits->take(3) as $habit)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                        <div>
                                            <p class="font-medium text-gray-900 text-sm">{{ $habit->name }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($habit->frequency) }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-indigo-600">{{ $habit->streak_count }}</p>
                                            <p class="text-xs text-gray-500">streak</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Icon Bar (Mobile style) -->
    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-gray-900 rounded-full px-8 py-3 shadow-lg flex gap-4">
        <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
        </a>
        <a href="{{ route('todos.index') }}" class="text-gray-400 hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
        </a>
        <a href="{{ route('habits.index') }}" class="text-gray-400 hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </a>
        <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-gray-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </a>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Habits</h1>
                    <p class="text-gray-600">Build and track your daily and weekly habits</p>
                </div>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                    + Add Habit
                </button>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Habits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($habits as $habit)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-bold mb-1">{{ $habit->name }}</h3>
                                    <p class="text-indigo-100 text-sm">{{ ucfirst($habit->frequency) }} Habit</p>
                                </div>
                                <form method="POST" action="{{ route('habits.destroy', $habit->habits_id) }}" style="display:inline" onsubmit="return confirm('Delete this habit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-indigo-200 hover:text-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Streak -->
                            <div class="text-center mb-6">
                                <p class="text-4xl font-bold text-indigo-600">{{ $habit->streak_count }}</p>
                                <p class="text-gray-600 text-sm">day{{ $habit->streak_count !== 1 ? 's' : '' }} streak</p>
                            </div>

                            <!-- Check Today Button -->
                            @php
                                $today = now()->toDateString();
                                $checkedToday = $habit->logs()->whereDate('completed_at', $today)->exists();
                            @endphp
                            
                            <form method="POST" action="{{ route('habits.check', $habit->habits_id) }}" class="mb-6">
                                @csrf
                                @method('PATCH')
                                <button 
                                    type="submit" 
                                    {{ $checkedToday ? 'disabled' : '' }}
                                    class="w-full py-3 rounded-lg font-semibold transition {{ $checkedToday ? 'bg-green-100 text-green-600 cursor-not-allowed' : 'bg-indigo-100 text-indigo-600 hover:bg-indigo-200' }}"
                                >
                                    {{ $checkedToday ? '✓ Completed Today' : 'Check Today' }}
                                </button>
                            </form>

                            <!-- Recent Logs -->
                            <div class="border-t pt-4">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Recent Activity</p>
                                <div class="space-y-2">
                                    @forelse($habit->logs()->latest('completed_at')->take(5)->get() as $log)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">{{ $log->completed_at->format('M d, Y') }}</span>
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-sm">No activity yet</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Total Completions -->
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-center text-xs text-gray-500">
                                    {{ $habit->logs()->count() }} total completion{{ $habit->logs()->count() !== 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-4">No habits yet. Create one to get started!</p>
                        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="inline-block bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-2 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition">
                            Add Your First Habit
                        </button>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create New Habit</h2>
            <form method="POST" action="{{ route('habits.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Habit Name</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none" placeholder="e.g., Morning Exercise">
                </div>

                <div>
                    <label for="frequency" class="block text-sm font-semibold text-gray-900 mb-2">Frequency</label>
                    <select id="frequency" name="frequency" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="flex-1 px-4 py-2 border-2 border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition">
                        Create Habit
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

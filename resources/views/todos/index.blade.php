<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Tasks</h1>
                    <p class="text-gray-600">Manage and track your daily tasks</p>
                </div>
                <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                    + Add Task
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

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <form method="GET" action="{{ route('todos.index') }}" id="filterForm" class="space-y-2">
                            <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none">
                                <option value="">All Tasks</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="done" {{ request('status') === 'done' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </form>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                        <form method="GET" action="{{ route('todos.index') }}" class="space-y-2">
                            <select name="category_id" onchange="this.form.submit()" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->categories_id }}" {{ request('category_id') == $category->categories_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-end gap-4">
                        <div class="text-center flex-1">
                            <p class="text-2xl font-bold text-indigo-600">{{ $todos->count() }}</p>
                            <p class="text-sm text-gray-600">Total Tasks</p>
                        </div>
                        <div class="text-center flex-1">
                            <p class="text-2xl font-bold text-green-600">{{ $todos->where('is_done', true)->count() }}</p>
                            <p class="text-sm text-gray-600">Completed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tasks List -->
            <div class="space-y-4">
                @forelse($todos as $todo)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border-l-4" style="border-color: {{ $todo->category->color ?? '#6366f1' }}">
                        <div class="flex items-start justify-between gap-4">
                            <!-- Left Content -->
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <!-- Checkbox -->
                                    <form method="POST" action="{{ route('todos.toggle', $todo->todos_id) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="flex-shrink-0">
                                            @if($todo->is_done)
                                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full hover:border-indigo-500"></div>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Title -->
                                    <div>
                                        <h3 class="font-semibold text-gray-900 {{ $todo->is_done ? 'line-through text-gray-400' : '' }}">
                                            {{ $todo->title }}
                                        </h3>
                                    </div>
                                </div>

                                <!-- Description & Meta -->
                                @if($todo->description)
                                    <p class="text-gray-600 text-sm mb-2 {{ $todo->is_done ? 'line-through' : '' }}">{{ $todo->description }}</p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3 text-sm">
                                    <!-- Category Badge -->
                                    @if($todo->category)
                                        <span class="px-3 py-1 rounded-full text-white text-xs font-semibold" style="background-color: {{ $todo->category->color }}">
                                            {{ $todo->category->name }}
                                        </span>
                                    @endif

                                    <!-- Deadline -->
                                    @if($todo->deadline)
                                        <div class="flex items-center gap-1 text-gray-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $todo->deadline->format('M d, Y') }}
                                        </div>
                                    @endif

                                    <!-- Status Badge -->
                                    @if($todo->is_done)
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Completed</span>
                                    @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Pending</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2">
                                <button onclick="editTodo({{ $todo }})" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('todos.destroy', $todo->todos_id) }}" style="display:inline" onsubmit="return confirm('Delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No tasks yet. Create one to get started!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Add New Task</h2>
            <form method="POST" action="{{ route('todos.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">Task Title</label>
                    <input type="text" id="title" name="title" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none" placeholder="Enter task title">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none" placeholder="Enter description (optional)" rows="3"></textarea>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">Category</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->categories_id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-semibold text-gray-900 mb-2">Deadline</label>
                    <input type="date" id="deadline" name="deadline" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none">
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="flex-1 px-4 py-2 border-2 border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editTodo(todo) {
            // Redirect to edit form (akan buat view terpisah)
            window.location.href = '/todos/' + todo.todos_id + '/edit';
        }
    </script>
</x-app-layout>

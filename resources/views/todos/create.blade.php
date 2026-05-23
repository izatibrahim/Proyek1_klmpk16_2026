<x-app-layout>
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('todos.index') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Tasks
                </a>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Create New Task</h1>
                <p class="text-gray-600">Add a new task to your to-do list</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <form method="POST" action="{{ route('todos.store') }}" class="space-y-8">
                    @csrf

                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-3">Task Title *</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}"
                            required 
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none transition {{ $errors->has('title') ? 'border-red-500' : '' }}" 
                            placeholder="e.g., Complete project report"
                        >
                        @error('title')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">Description</label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none transition"
                            placeholder="Add more details about this task..."
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-3">Category</label>
                        <select 
                            id="category_id" 
                            name="category_id"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none transition {{ $errors->has('category_id') ? 'border-red-500' : '' }}"
                        >
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->categories_id }}" {{ old('category_id') == $category->categories_id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline -->
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-gray-900 mb-3">Deadline</label>
                        <input 
                            type="date" 
                            id="deadline" 
                            name="deadline"
                            value="{{ old('deadline') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-indigo-500 focus:outline-none transition {{ $errors->has('deadline') ? 'border-red-500' : '' }}"
                        >
                        @error('deadline')
                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t">
                        <a href="{{ route('todos.index') }}" class="flex-1 px-6 py-3 border-2 border-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                            Cancel
                        </a>
                        <button 
                            type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:from-indigo-700 hover:to-purple-700 transition"
                        >
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

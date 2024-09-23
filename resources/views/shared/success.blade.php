@if (session('success'))
    <div class="bg-green-500 dark:bg-green-700 text-white p-4 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

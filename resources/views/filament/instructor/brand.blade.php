<div class="flex flex-col p-0 gap-0 my-2">
    <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white p-0 m-0">
        {{ config('app.name', 'LMS Platform') }}
    </span>
    <span class="text-lg font-bold text-primary-600 dark:text-primary-400 mb-2">
        Instructor: {{ auth()->user()->name ?? 'Instructor' }}
    </span>
</div>

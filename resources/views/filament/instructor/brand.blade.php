<div class="flex flex-col leading-none">
    <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">
        {{ config('app.name', 'LMS Platform') }}
    </span>
    <span class="text-sm font-medium text-primary-600 dark:text-primary-400 mt-1">
        Instructor Panel: {{ auth()->user()->name ?? 'Instructor' }}
    </span>
</div>
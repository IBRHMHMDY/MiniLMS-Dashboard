<div class="flex flex-row-reverse items-center gap-4 me-4">
    
    {{-- 1. الزر الكبير (سيكون في أقصى اليسار بالنسبة لهذه المجموعة) --}}
    <x-filament::button
        href="/instructor/courses/create"
        tag="a"
        color="primary"
        icon="heroicon-o-plus-circle"
        size="sm"
    >
        Add New Course
    </x-filament::button>

    {{-- 2. الأيقونات (ستكون في المنتصف، بين الزر وقائمة المستخدم) --}}
    <div class="flex flex-row items-center gap-4 border-l border-gray-200 dark:border-gray-700 pl-4">
        <x-filament::icon-button
            icon="heroicon-o-users"
            tooltip="Students Tracking"
            href="#"
            tag="a"
            color="gray"
        />

        <x-filament::icon-button
            icon="heroicon-o-bell"
            tooltip="Notifications"
            color="gray"
        />

        <x-filament::icon-button
            icon="heroicon-o-language"
            tooltip="Change Language"
            color="gray"
        />
    </div>

    {{-- 3. قائمة المستخدم والوضع الليلي (تُضاف تلقائياً بواسطة Filament على يمين هذه المجموعة) --}}
</div>
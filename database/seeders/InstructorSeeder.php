<?php

namespace Database\Seeders;

use App\Enums\CourseLevel;
use App\Enums\CourseStatus;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء حساب المدرب للتجربة
        $instructor = User::firstOrCreate(
            ['email' => 'ibrahim@gmail.com'],
            [
                'name' => 'Ibrahim (Instructor)',
                'password' => 'password',
                'status' => 'active',
            ]
        );

        // 2. إنشاء تصنيفات
        $categories = ['Web Development', 'Mobile App Development', 'UI/UX Design'];
        foreach ($categories as $catName) {
            Category::firstOrCreate(
                ['slug' => Str::slug($catName)],
                ['name' => $catName, 'is_active' => true]
            );
        }

        // 3. إنشاء كورس تجريبي للمدرب
        $course = Course::create([
            'category_id' => Category::first()->id,
            'instructor_id' => $instructor->id,
            'title' => 'Laravel 12 & Flutter Masterclass',
            'slug' => 'laravel-12-flutter-masterclass',
            'short_description' => 'Build a complete LMS from scratch.',
            'level' => CourseLevel::ADVANCED,
            'status' => CourseStatus::PUBLISHED,
            'price' => 99.99,
            'is_free' => false,
            'published_at' => now(),
        ]);

        // 4. إنشاء أقسام ودروس داخل الكورس
        $sections = ['Introduction & Setup', 'Database Architecture', 'API Development'];
        
        foreach ($sections as $index => $secTitle) {
            $section = Section::create([
                'course_id' => $course->id,
                'title' => $secTitle,
                'sort_order' => $index,
            ]);

            // إنشاء درسين لكل قسم
            for ($i = 1; $i <= 2; $i++) {
                Lesson::create([
                    'section_id' => $section->id,
                    'title' => $secTitle . ' - Lesson ' . $i,
                    'slug' => Str::slug($secTitle . '-Lesson-' . $i . '-' . uniqid()),
                    'duration_in_minutes' => rand(5, 20),
                    'sort_order' => $i,
                    'is_free_preview' => ($index === 0 && $i === 1), // أول درس مجاني
                ]);
            }
        }
    }
}
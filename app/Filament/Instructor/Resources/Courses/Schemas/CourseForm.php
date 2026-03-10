<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get as UtilitiesGet;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Course Information')
                    ->schema([
                        // السطر الأول: Course Name - Category
                        Grid::make(2)->schema([
                            TextInput::make('title')
                                ->label('Course Name')
                                ->required()
                                ->maxLength(255),
                                
                            Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        ]),
                        // السطر الثالث: Published
                        Grid::make(2)->schema([
                            Toggle::make('is_published')
                                ->label('Publish Course')
                                ->default(false)
                                ->inline(false),
                            Grid::make(2)->schema([
                                Toggle::make('is_free')
                                    ->label('Free Course')
                                    ->default(false)
                                    ->live() // لتفعيل التحديث اللحظي لحقل السعر
                                    ->inline(false), // لجعل التوجل يأخذ مساحته العمودية بانتظام
                                    
                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('$')
                                    // السعر مطلوب فقط إذا لم يكن الكورس مجانياً
                                    ->required(fn (UtilitiesGet $get) => ! $get('is_free'))
                                    // إخفاء الحقل بذكاء (UX) إذا كان الكورس مجانياً
                                    ->hidden(fn (UtilitiesGet $get) => $get('is_free')),
                            ]),
                        ]),
                            
                        // السطر الرابع: Cover Image - Description
                        Grid::make(2)->schema([
                            FileUpload::make('image_path')
                                ->label('Cover Image')
                                ->image()
                                ->directory('courses/covers')
                                ->imageEditor() // تفعيل محرر الصور المدمج في Filament v4
                                ->columnSpan(1),
                                
                            RichEditor::make('description')
                                ->label('Description')
                                ->required()
                                ->toolbarButtons([
                                    'bold', 'italic', 'strike', 'link', 'h2', 'h3', 'bulletList', 'orderedList', 'redo', 'undo',
                                ])
                                ->columnSpan(1),
                        ]),

                        // حقل المدرب المخفي (يُعزل تماماً عن واجهة المستخدم ويأخذ قيمة المدرب الحالي)
                        Hidden::make('instructor_id')
                            ->default(fn () => Auth::id()),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

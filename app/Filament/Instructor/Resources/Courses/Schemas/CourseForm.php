<?php

namespace App\Filament\Instructor\Resources\Courses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

class CourseForm
{
    public static function configure($schema)
    {
        return $schema
            ->components([
                Hidden::make('instructor_id')
                    ->default(fn () => auth()->user()->id),

                // 🌟 إضافة الـ Tabs لتحسين تجربة المستخدم
                Tabs::make('CourseBuilder')
                    ->tabs([
                        Tab::make('المعلومات الأساسية')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label('عنوان الكورس')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('category_id')
                                    ->label('القسم')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                // 🌟 تحويل Textarea إلى RichEditor احترافي
                                RichEditor::make('description')
                                    ->label('وصف الكورس')
                                    ->required()
                                    ->toolbarButtons([
                                        'blockquote', 'bold', 'bulletList', 'h2', 'h3',
                                        'italic', 'link', 'orderedList', 'redo', 'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('التسعير والوسائط')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Toggle::make('is_free')
                                    ->label('هل هذا الكورس مجاني؟')
                                    ->default(true)
                                    ->live(),

                                TextInput::make('price')
                                    ->label('السعر (EGP)')
                                    ->numeric()
                                    ->prefix('EGP')
                                    ->hidden(fn (Get $get) => $get('is_free'))
                                    ->required(fn (Get $get) => ! $get('is_free')),

                                FileUpload::make('image_path')
                                    ->label('صورة غلاف الكورس')
                                    ->image()
                                    ->imageEditor() // يسمح بقص الصورة وتعديلها داخل لوحة التحكم
                                    ->directory('courses')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(), // جعل الـ Tabs تأخذ العرض بالكامل
            ]);
    }
}

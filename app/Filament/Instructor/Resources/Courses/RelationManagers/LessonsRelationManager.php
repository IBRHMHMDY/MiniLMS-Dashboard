<?php

namespace App\Filament\Instructor\Resources\CourseResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $title = 'محتوى الكورس (الدروس)';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('عنوان الدرس')
                    ->required()
                    ->maxLength(255),

                TextInput::make('video_url')
                    ->label('رابط الفيديو')
                    ->required()
                    ->maxLength(255),

                // بناءً على صورتك، يبدو أن الحقل لديك اسمه content وليس description
                Textarea::make('content')
                    ->label('محتوى الدرس')
                    ->columnSpanFull(),
            ]);
        // 💡 لاحظ: قمنا بحذف حقل Course وحقل order_number تماماً من هنا
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title') // 👈 هذا السطر السحري هو ما سيحذف الـ JSON من العنوان ويضع اسم الدرس مكانه!
            ->columns([
                TextColumn::make('title')
                    ->label('عنوان الدرس'),

                TextColumn::make('video_url')
                    ->label('رابط الفيديو')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('إضافة درس جديد'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order_number') // 👈 هذا هو السطر الذي يُشغّل السحب والإفلات في الجدول!
            ->defaultSort('order_number');
    }
}

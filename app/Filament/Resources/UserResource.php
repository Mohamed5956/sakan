<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'المستخدمون';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $pluralModelLabel = 'المستخدمون';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('البيانات الشخصية')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn($state) => !empty($state))
                            ->required(fn(string $operation) => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText('اتركه فارغاً إذا لا تريد تغيير كلمة المرور'),

                        Forms\Components\Toggle::make('is_admin')
                            ->label('مدير النظام؟')
                            ->default(false)
                            ->helperText('المديرون لديهم صلاحية الوصول للوحة التحكم'),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_admin')
                    ->label('مدير')
                    ->boolean(),

                Tables\Columns\TextColumn::make('properties_count')
                    ->label('العقارات')
                    ->counts('properties')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('النوع')
                    ->trueLabel('المديرون')
                    ->falseLabel('المستخدمون العاديون'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف')
                    ->before(function (User $record) {
                        // منع حذف المدير الوحيد
                        if ($record->is_admin && User::where('is_admin', true)->count() === 1) {
                            \Filament\Notifications\Notification::make()
                                ->title('لا يمكن حذف المدير الوحيد في النظام')
                                ->danger()
                                ->send();
                            $this->halt();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

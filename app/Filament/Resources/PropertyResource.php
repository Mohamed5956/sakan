<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyResource\Pages;
use App\Models\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Filament\Forms\Components\Actions\Action;
class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'العقارات';
    protected static ?string $modelLabel = 'عقار';
    protected static ?string $pluralModelLabel = 'العقارات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── المعلومات الأساسية ──────────────────────────
            Section::make('المعلومات الأساسية')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('عنوان العقار')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                $slug = preg_replace('/[^\p{Arabic}\p{N}\s-]+/u', '', $state);
                                $slug = preg_replace('/\s+/u', '-', trim($slug));
                                $slug = preg_replace('/-+/', '-', $slug);

                                $originalSlug = $slug;
                                $counter = 1;

                                while (Property::where('slug', $slug)->exists()) {
                                    $slug = $originalSlug . '-' . $counter;
                                    $counter++;
                                }

                                $set('slug', $slug);
                                $set('meta_title', $state . ' | سكن');
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label('الرابط المختصر (Slug)')
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->maxLength(255),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\Select::make('type')
                            ->label('نوع العقار')
                            ->required()
                            ->options([
                                'شقة'      => 'شقة',
                                'غرفة'     => 'غرفة',
                                'استوديو'  => 'استوديو',
                                'فيلا'     => 'فيلا',
                                'دوبلكس'   => 'دوبلكس',
                                'وحدة سكنية' => 'وحدة سكنية',
                            ]),

                        Forms\Components\Select::make('price_period')
                            ->label('فترة السعر')
                            ->options([
                                'شهري'  => 'شهري',
                                'سنوي'  => 'سنوي',
                                'يومي'  => 'يومي',
                            ])
                            ->default('شهري'),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('price')
                            ->label('السعر (جنيه)')
                            ->numeric()
                            ->prefix('ج.م'),

                        Forms\Components\TextInput::make('nearest_university')
                            ->label('أقرب جامعة')
                            ->maxLength(255),
                    ]),

                    Forms\Components\Textarea::make('description')
                        ->label('الوصف')
                        ->rows(4)
                        ->columnSpanFull(),
                ]),

            // ── الموقع والعنوان ────────────────────────────
            Section::make('الموقع والعنوان')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('city')
                            ->label('المدينة')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('district')
                            ->label('الحي / المنطقة')
                            ->maxLength(255),
                    ]),

                    Forms\Components\TextInput::make('address')
                        ->label('العنوان الكامل')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull()
                        ->helperText('أدخل العنوان الكامل ثم اضغط "تحديد الموقع"')
                        ->suffixAction(
                            Action::make('geocode')
                                ->label('تحديد الموقع')
                                ->icon('heroicon-o-map-pin')
                                ->action(function (Forms\Get $get, Forms\Set $set) {
                                    $address = $get('address');

                                    if (!$address) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('من فضلك أدخل العنوان أولاً')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                                        'address'  => $address,
                                        'key'      => config('services.google_maps.key'),
                                        'language' => 'ar',
                                    ]);

                                    $data = $response->json();

                                    if (($data['status'] ?? '') === 'OK') {
                                        $location = $data['results'][0]['geometry']['location'];
                                        $set('latitude',  $location['lat']);
                                        $set('longitude', $location['lng']);

                                        \Filament\Notifications\Notification::make()
                                            ->title('تم تحديد الموقع بنجاح ✓')
                                            ->success()
                                            ->send();
                                    } else {
                                        \Filament\Notifications\Notification::make()
                                            ->title('لم يتم العثور على الموقع')
                                            ->body('تحقق من العنوان وحاول مرة أخرى')
                                            ->danger()
                                            ->send();
                                    }
                                })
                        ),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('خط العرض (Latitude)')
                            ->numeric()
                            ->readOnly()
                            ->placeholder('يتم تعبئته تلقائياً'),

                        Forms\Components\TextInput::make('longitude')
                            ->label('خط الطول (Longitude)')
                            ->numeric()
                            ->readOnly()
                            ->placeholder('يتم تعبئته تلقائياً'),
                    ]),

                ]),

            // ── تفاصيل العقار ──────────────────────────────
            Section::make('تفاصيل العقار')
                ->schema([
                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('bathrooms')
                            ->label('عدد الحمامات')
                            ->numeric()
                            ->default(1)
                            ->minValue(0),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('عقار مميز؟')
                            ->default(false),
                    ]),

                    // Rooms Repeater
                    Forms\Components\Repeater::make('rooms')
                        ->label('الغرف')
                        ->relationship()
                        ->schema([
                            Grid::make(3)->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('اسم الغرفة')
                                    ->default('غرفة')
                                    ->required(),

                                Forms\Components\TextInput::make('beds')
                                    ->label('عدد الأسرة')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required(),

                                Forms\Components\TextInput::make('notes')
                                    ->label('ملاحظات')
                                    ->placeholder('اختياري'),
                            ]),
                        ])
                        ->addActionLabel('+ إضافة غرفة')
                        ->collapsible()
                        ->columnSpanFull(),
                ]),

            // ── الصور ──────────────────────────────────────
            Section::make('الصور')
                ->schema([
                    Forms\Components\FileUpload::make('main_image')
                        ->label('الصورة الرئيسية')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('properties/main')
                        ->maxSize(5120)
                        ->downloadable()
                        ->openable()
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('images')
                        ->label('معرض الصور')
                        ->relationship()
                        ->schema([
                            Grid::make(2)->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('الصورة')
                                    ->image()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('properties/gallery')
                                    ->maxSize(5120)
                                    ->required(),

                                Forms\Components\TextInput::make('alt')
                                    ->label('وصف الصورة (Alt)')
                                    ->placeholder('وصف مختصر للصورة'),
                            ]),
                        ])
                        ->addActionLabel('+ إضافة صورة')
                        ->collapsible()
                        ->columnSpanFull(),
                ]),

            // ── معلومات التواصل ────────────────────────────
            Section::make('معلومات التواصل')
                ->schema([
                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('رقم الهاتف')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('whatsapp')
                            ->label('واتساب')
                            ->tel()
                            ->maxLength(20)
                            ->helperText('مثال: 201012345678'),

                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->maxLength(255),
                    ]),
                ]),

            // ── SEO ────────────────────────────────────────
            Section::make('تحسين محركات البحث (SEO)')
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->label('عنوان الصفحة (Meta Title)')
                        ->maxLength(60)
                        ->helperText('الحد الأقصى 60 حرف')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('meta_description')
                        ->label('وصف الصفحة (Meta Description)')
                        ->maxLength(160)
                        ->helperText('الحد الأقصى 160 حرف')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),

            // ── الحالة ─────────────────────────────────────
            Section::make('الحالة')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشر العقار')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('الصورة')
                    ->disk('public')
                    ->defaultImageUrl(asset('images/property-placeholder.webp'))
                    ->height(60)
                    ->width(80),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('type')
                    ->label('النوع')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('city')
                    ->label('المدينة')
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->money('EGP')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('مميز')
                    ->boolean(),

                Tables\Columns\TextColumn::make('views')
                    ->label('المشاهدات')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع العقار')
                    ->options([
                        'شقة'        => 'شقة',
                        'غرفة'       => 'غرفة',
                        'استوديو'    => 'استوديو',
                        'فيلا'       => 'فيلا',
                        'دوبلكس'     => 'دوبلكس',
                        'وحدة سكنية' => 'وحدة سكنية',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->trueLabel('نشط')
                    ->falseLabel('غير نشط'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('مميز'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
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
            'index'  => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit'   => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Student\Pages;

use App\Models\Student;
use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static string $view = 'filament.student.pages.profile';

    protected static ?string $navigationLabel = 'Hồ sơ';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Thông tin cá nhân';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public ?array $data = [];

    public Student $student;

    public function mount(): void
    {
        $user = Auth::user();
        $this->student = Student::where('user_id', $user->id)
            ->with('classRelation')
            ->firstOrFail();

        $this->form->fill([
            'student_id' => $this->student->student_id ?? '',
            'full_name' => $this->student->full_name ?? '',
            'birth_date' => $this->student->birth_date,
            'avatar' => $this->student->avatar,
            'class_id' => optional($this->student->classRelation)->class_id ?? '—',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cá nhân')
                    ->schema([
                        TextInput::make('student_id')
                            ->label('Mã sinh viên')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('full_name')
                            ->label('Họ và tên')
                            ->required()
                            ->maxLength(100),
                        DatePicker::make('birth_date')
                            ->label('Ngày sinh')
                            ->required()
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->maxDate(now())
                            ->minDate(now()->subYears(100)),
                        FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->disk('public'),
                        TextInput::make('class_id')
                            ->label('Lớp')

                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data')
            ->model($this->student);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Xử lý avatar - FileUpload có thể trả về array
        $avatar = $data['avatar'] ?? null;
        if (is_array($avatar)) {
            $avatar = $avatar[0] ?? null;
        }
        if (empty($avatar)) {
            $avatar = $this->student->avatar;
        }

        $this->student->update([
            'full_name' => $data['full_name'] ?? $this->student->full_name,
            'birth_date' => $data['birth_date'] ?? $this->student->birth_date,
            'avatar' => $avatar,
        ]);

        Notification::make()
            ->title('Cập nhật thành công')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Lưu thay đổi')
                ->action('save')
                ->color('primary'),
        ];
    }
}


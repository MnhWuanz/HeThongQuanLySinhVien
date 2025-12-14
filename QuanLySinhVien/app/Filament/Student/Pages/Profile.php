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
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
            'email' => $user->email ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin sinh viên')
                    ->description('Thông tin cơ bản từ hồ sơ sinh viên')
                    ->schema([
                        TextInput::make('student_id')
                            ->label('Mã sinh viên')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('full_name')
                            ->label('Họ và tên')
                            ->disabled()
                            ->dehydrated(false),
                        DatePicker::make('birth_date')
                            ->label('Ngày sinh')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('class_id')
                            ->label('Lớp')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Ảnh đại diện')
                    ->description('Cập nhật ảnh đại diện của bạn')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Ảnh đại diện')
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->disk('public')
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
                            ->helperText('Chỉ chấp nhận file JPG, PNG. Kích thước tối đa: 2MB')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),

                Section::make('Đổi mật khẩu')
                    ->description('Thay đổi mật khẩu đăng nhập của bạn')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Mật khẩu hiện tại')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->rules(['nullable', 'current_password'])
                            ->validationMessages([
                                'current_password' => 'Mật khẩu hiện tại không đúng.',
                            ]),
                        TextInput::make('password')
                            ->label('Mật khẩu mới')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->rules(['nullable', 'confirmed', Password::min(8)])
                            ->validationMessages([
                                'confirmed' => 'Mật khẩu xác nhận không khớp.',
                                'min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                            ])
                            ->helperText('Để trống nếu không muốn đổi mật khẩu'),
                        TextInput::make('password_confirmation')
                            ->label('Xác nhận mật khẩu mới')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->requiredWith('password'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data')
            ->model($this->student);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        // Xử lý avatar - FileUpload có thể trả về array
        $avatar = $data['avatar'] ?? null;
        if (is_array($avatar)) {
            $avatar = $avatar[0] ?? null;
        }
        if (empty($avatar)) {
            $avatar = $this->student->avatar;
        }

        // Cập nhật avatar
        $this->student->update([
            'avatar' => $avatar,
        ]);

        // Xử lý đổi mật khẩu
        if (!empty($data['password'])) {
            if (empty($data['current_password'])) {
                Notification::make()
                    ->title('Lỗi')
                    ->body('Vui lòng nhập mật khẩu hiện tại để đổi mật khẩu mới.')
                    ->danger()
                    ->send();
                return;
            }

            if (!Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Lỗi')
                    ->body('Mật khẩu hiện tại không đúng.')
                    ->danger()
                    ->send();
                return;
            }

            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        Notification::make()
            ->title('Cập nhật thành công')
            ->body('Thông tin hồ sơ của bạn đã được cập nhật.')
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


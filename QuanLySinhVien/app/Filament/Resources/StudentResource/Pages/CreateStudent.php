<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Kiểm tra file avatar nếu có
        if (isset($data['avatar']) && $data['avatar']) {
            $file = $data['avatar'];
            
            // Lấy extension thực tế của file
            if (is_string($file)) {
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            } else {
                // Nếu là UploadedFile object
                $extension = strtolower($file->getClientOriginalExtension());
            }
            
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($extension, $allowedExtensions)) {
                Notification::make()
                    ->danger()
                    ->title('Lỗi định dạng file')
                    ->body('Chỉ chấp nhận file ảnh định dạng JPG hoặc PNG. File của bạn là: .' . $extension)
                    ->persistent()
                    ->send();
                
                // Xóa avatar khỏi data để không lưu
                unset($data['avatar']);
                
                // Dừng quá trình tạo
                $this->halt();
            }
        }
        
        return $data;
    }
}

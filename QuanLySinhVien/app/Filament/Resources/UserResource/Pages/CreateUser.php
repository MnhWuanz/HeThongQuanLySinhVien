<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Student;
use App\Models\Teacher;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected ?string $heading = 'Tạo User mới';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        
        // Check roles and create corresponding records
        if ($user->hasRole('Teacher')) {
            // Create Teacher record if doesn't exist
            if (!$user->teacher) {
                Teacher::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'phone' => '',
                    'address' => '',
                ]);
            }
        }
        
        if ($user->hasRole('Student')) {
            // Create Student record if doesn't exist
            if (!$user->student) {
                // Generate student code
                $lastStudent = Student::orderBy('student_id', 'desc')->first();
                $nextNumber = $lastStudent ? intval(substr($lastStudent->student_id, 4)) + 1 : 1;
                $studentCode = 'TEST' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                
                Student::create([
                    'student_id' => $studentCode,
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'date_of_birth' => null,
                    'phone' => '',
                    'address' => '',
                    'class_id' => null,
                ]);
            }
        }
    }
}

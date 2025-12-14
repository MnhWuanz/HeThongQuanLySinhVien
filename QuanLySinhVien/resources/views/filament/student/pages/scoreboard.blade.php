<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Thông tin sinh viên</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mã sinh viên</p>
                    <p class="font-medium">{{ $this->student->student_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Họ và tên</p>
                    <p class="font-medium">{{ $this->student->full_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lớp</p>
                    <p class="font-medium">{{ $this->student->classRelation->class_id ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div>
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>


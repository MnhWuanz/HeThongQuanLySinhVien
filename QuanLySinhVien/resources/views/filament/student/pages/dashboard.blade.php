<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-600 text-white p-8 rounded-2xl shadow-2xl">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">
                        👋 Xin chào, {{ $student->full_name }}!
                    </h1>
                    <p class="text-sky-100 text-lg mb-4">
                        Chúc bạn một ngày học tập hiệu quả và vui vẻ!
                    </p>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                            <span class="opacity-90">Mã SV:</span>
                            <strong class="ml-2">{{ $student->student_id }}</strong>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                            <span class="opacity-90">Lớp:</span>
                            <strong class="ml-2">{{ $student->classRelation->class_id ?? 'N/A' }}</strong>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                            <span class="opacity-90">Khoa:</span>
                            <strong class="ml-2">{{ $student->classRelation->departmentRelation->name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <svg class="w-32 h-32 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Subjects -->
            <div class="stats-info interactive-card bg-white p-6 rounded-xl shadow-lg border-l-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tổng số môn học</p>
                        <h3 class="text-3xl font-bold" style="color: #2563eb !important;">{{ $statistics['total_subjects'] }}</h3>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Passed Subjects -->
            <div class="stats-success interactive-card bg-white p-6 rounded-xl shadow-lg border-l-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Số môn đạt</p>
                        <h3 class="text-3xl font-bold" style="color: #16a34a !important;">{{ $statistics['passed_subjects'] }}</h3>
                    </div>
                    <div class="bg-green-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Failed Subjects -->
            <div class="stats-danger interactive-card bg-white p-6 rounded-xl shadow-lg border-l-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Số môn chưa đạt</p>
                        <h3 class="text-3xl font-bold" style="color: #dc2626 !important;">{{ $statistics['failed_subjects'] }}</h3>
                    </div>
                    <div class="bg-red-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Score -->
            <div class="stats-warning interactive-card bg-white p-6 rounded-xl shadow-lg border-l-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Điểm trung bình</p>
                        <h3 class="text-3xl font-bold" style="color: #ea580c !important;">{{ number_format($statistics['average_score'], 2) }}</h3>
                    </div>
                    <div class="bg-orange-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Highest Score -->
            <div class="interactive-card bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Điểm cao nhất</p>
                        <h3 class="text-3xl font-bold">{{ number_format($statistics['highest_score'], 2) }}</h3>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lowest Score -->
            <div class="interactive-card bg-gradient-to-br from-pink-500 to-rose-600 text-white p-6 rounded-xl shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Điểm thấp nhất</p>
                        <h3 class="text-3xl font-bold">{{ number_format($statistics['lowest_score'], 2) }}</h3>
                    </div>
                    <div class="bg-white/20 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="{{ route('filament.student.pages.scoreboard') }}" 
               class="interactive-card group bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl border-2 border-gray-100 hover:border-blue-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-4 rounded-xl text-white group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg group-hover:text-blue-600" style="color: #1f2937;">Xem Bảng điểm</h3>
                        <p class="text-sm" style="color: #4b5563;">Tra cứu điểm số các môn học</p>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <a href="{{ route('filament.student.pages.profile') }}" 
               class="interactive-card group bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl border-2 border-gray-100 hover:border-green-300">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-xl text-white group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg group-hover:text-green-600" style="color: #1f2937;">Thông tin Cá nhân</h3>
                        <p class="text-sm" style="color: #4b5563;">Xem và cập nhật hồ sơ</p>
                    </div>
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Academic Performance Chart -->
        @if($statistics['total_subjects'] > 0)
        <div class="bg-white p-6 rounded-xl shadow-lg border-2 border-gray-100">
            <h3 class="font-bold text-xl mb-4 flex items-center" style="color: #1f2937;">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kết quả Học tập
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" style="color: #4b5563;">Môn đạt</span>
                        <span class="text-sm font-bold" style="color: #16a34a;">{{ number_format(($statistics['passed_subjects'] / $statistics['total_subjects']) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" style="width: {{ ($statistics['passed_subjects'] / $statistics['total_subjects']) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm" style="color: #4b5563;">Môn chưa đạt</span>
                        <span class="text-sm font-bold" style="color: #dc2626;">{{ number_format(($statistics['failed_subjects'] / $statistics['total_subjects']) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full" style="width: {{ ($statistics['failed_subjects'] / $statistics['total_subjects']) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer Info -->
        <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl p-4 text-center">
            <p class="text-sm text-gray-600">
                📅 {{ now()->locale('vi')->isoFormat('dddd, D THÁNG M, YYYY') }} | 
                🕐 {{ now()->format('H:i') }} | 
                📚 Năm học 2025-2026
            </p>
        </div>
    </div>
</x-filament-panels::page>

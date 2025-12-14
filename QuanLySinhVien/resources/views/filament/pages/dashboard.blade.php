<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">
                        {{ $this->getSubheading() }}
                    </h2>
                    <p class="text-indigo-100">
                        📅 {{ now()->locale('vi')->isoFormat('dddd, D THÁNG M, YYYY') }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-white opacity-20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stats Widgets -->
        @if($this->getHeaderWidgets())
            <div>
                <x-filament-widgets::widgets
                    :widgets="$this->getHeaderWidgets()"
                    :columns="$this->getHeaderWidgetsColumns()"
                />
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(auth()->user()->hasRole('Super_Admin'))
                <a href="{{ route('filament.admin.resources.students.index') }}" 
                   class="interactive-card bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Quản lý</p>
                            <h3 class="text-xl font-bold">Sinh viên</h3>
                        </div>
                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('filament.admin.resources.scores.index') }}" 
                   class="interactive-card bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Quản lý</p>
                            <h3 class="text-xl font-bold">Điểm số</h3>
                        </div>
                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('filament.admin.resources.subjects.index') }}" 
                   class="interactive-card bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Quản lý</p>
                            <h3 class="text-xl font-bold">Môn học</h3>
                        </div>
                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('filament.admin.resources.class-models.index') }}" 
                   class="interactive-card bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-lg hover:shadow-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm opacity-90">Quản lý</p>
                            <h3 class="text-xl font-bold">Lớp học</h3>
                        </div>
                        <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </a>
            @endif
        </div>

        <!-- Chart Widgets -->
        @if($this->getFooterWidgets())
            <div>
                <x-filament-widgets::widgets
                    :widgets="$this->getFooterWidgets()"
                    :columns="$this->getFooterWidgetsColumns()"
                />
            </div>
        @endif

        <!-- System Info -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border-2 border-gray-200">
            <h3 class="font-bold text-lg mb-4 text-gray-800">📊 Thông tin Hệ thống</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-gray-600">Hệ thống đang hoạt động</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-600">Phiên bản: 1.0.0</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                    <span class="text-gray-600">Năm học: 2025-2026</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

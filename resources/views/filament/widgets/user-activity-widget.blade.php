<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">User Activity & Insights</x-slot>
        
        @php
            $metrics = $this->getUserMetrics();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Card 1: Verification Status -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-base font-medium text-gray-700 mb-2">Verification Status</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Verified Users</p>
                        <p class="text-2xl font-bold text-green-600">{{ $metrics['verifiedUsers'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Unverified Users</p>
                        <p class="text-2xl font-bold text-red-500">{{ $metrics['unverifiedUsers'] }}</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    @php 
                        $total = $metrics['verifiedUsers'] + $metrics['unverifiedUsers'];
                        $percentage = $total > 0 ? ($metrics['verifiedUsers'] / $total) * 100 : 0; 
                    @endphp
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% verified</p>
            </div>
            
            <!-- Card 2: Assessment Completion -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-base font-medium text-gray-700 mb-2">Assessment Completion</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">With Assessments</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $metrics['usersWithAssessments'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">No Assessments</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $metrics['usersWithoutAssessments'] }}</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    @php 
                        $total = $metrics['usersWithAssessments'] + $metrics['usersWithoutAssessments'];
                        $percentage = $total > 0 ? ($metrics['usersWithAssessments'] / $total) * 100 : 0; 
                    @endphp
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% completed assessment</p>
            </div>
            
            <!-- Card 3: User Retention -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-base font-medium text-gray-700 mb-2">User Retention</h3>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">Active (30d)</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $metrics['activeUsers'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Inactive</p>
                        <p class="text-2xl font-bold text-gray-500">{{ $metrics['inactiveUsers'] }}</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    @php 
                        $total = $metrics['activeUsers'] + $metrics['inactiveUsers'];
                        $percentage = $total > 0 ? ($metrics['activeUsers'] / $total) * 100 : 0; 
                    @endphp
                    <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}% active in last 30 days</p>
            </div>
            
            <!-- Card 4: Registration Trend -->
            <div class="bg-white rounded-xl shadow p-4">
                <h3 class="text-base font-medium text-gray-700 mb-2">New Registrations (Last 7 Days)</h3>
                <div class="flex items-end h-24 mt-2">
                    @foreach($metrics['registrationTrend'] as $date => $count)
                        @php
                            $max = max($metrics['registrationTrend']);
                            $height = $max > 0 ? ($count / $max) * 100 : 0;
                            $day = \Carbon\Carbon::parse($date)->format('D');
                        @endphp
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-full px-1">
                                <div class="bg-indigo-500 rounded-t" style="height: {{ max(5, $height) }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $day }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Most Active Users -->
        <div class="bg-white rounded-xl shadow p-4">
            <h3 class="text-base font-medium text-gray-700 mb-4">Most Active Users</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessments</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                            <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($metrics['mostActiveUsers'] as $user)
                            <tr>
                                <td class="py-2 px-4 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="py-2 px-4 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="py-2 px-4 whitespace-nowrap">{{ $user->assessments_count }}</td>
                                <td class="py-2 px-4 whitespace-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="py-2 px-4 whitespace-nowrap">
                                    @if($user->email_verified_at)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Unverified</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-sm text-gray-500">No active users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
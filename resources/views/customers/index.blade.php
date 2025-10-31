<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Danh sách khách hàng</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">Tên</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Điện thoại</th>
                            <th class="px-4 py-2">Trạng thái</th>
                            <th class="px-4 py-2">Cấp độ</th>
                            <th class="px-4 py-2">Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $customer->CustomerID }}</td>
                                <td class="px-4 py-2">{{ $customer->user->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $customer->user->email ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $customer->Phone ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($customer->is_member)
                                        <span class="text-green-600 font-semibold">Hội viên</span>
                                    @else
                                        <span class="text-gray-500">Chưa là hội viên</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{ $customer->loyaltyProgram->MembershipLevel ?? '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $customer->loyaltyProgram->Points ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

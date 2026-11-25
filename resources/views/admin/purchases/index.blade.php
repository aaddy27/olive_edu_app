@extends('admin.layouts.app')

@section('title', 'Purchases & Orders')
@section('page-title', 'Transactions Ledger')

@section('content')
<div class="space-y-6">
    
    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Course Purchases & Orders</h2>
            <p class="text-sm text-slate-500 mt-0.5">Audit transaction logs, payment methods, receipts, and order statuses</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.purchases.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm shadow-indigo-200 flex items-center gap-2">
                <span class="material-symbols-rounded text-lg">receipt</span>
                <span>Add Purchase</span>
            </a>
        </div>
    </div>

    <!-- Enterprise Material Data Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        
        <!-- Table Toolbar -->
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/40">
            
            <!-- Live Search Bar -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-rounded text-xl">search</span>
                </div>
                <input 
                    type="text" 
                    id="purchaseSearchInput"
                    onkeyup="window.filterDataTable('purchaseSearchInput', 'purchasesDataTable', 'purchasesCountBadge')"
                    class="w-full pl-11 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-2xs"
                    placeholder="Search by customer, email, course, transaction ID..."
                >
            </div>

            <!-- Toolbar Badges -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <span class="text-xs font-medium text-slate-500">
                    Showing <span id="purchasesCountBadge" class="font-bold text-slate-900">{{ $purchases->count() }}</span> of {{ $purchases->total() }} records
                </span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="px-2.5 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 rounded-lg border border-emerald-100">
                    Payment Gateway
                </span>
            </div>
        </div>

        <!-- Material Table Element -->
        <div class="overflow-x-auto">
            <table id="purchasesDataTable" class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 text-[11px] font-semibold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Transaction #</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Course Enrolled</th>
                        <th class="px-6 py-4">Net Amount</th>
                        <th class="px-6 py-4">Payment Method</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($purchases as $purchase)
                    <tr data-filterable="true" class="hover:bg-indigo-50/30 transition-colors group">
                        
                        <!-- ID & Txn ID -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-slate-900">#{{ $purchase->id }}</span>
                            @if($purchase->transaction_id)
                                <p class="text-[10px] font-mono text-slate-400 mt-0.5">{{ Str::limit($purchase->transaction_id, 14) }}</p>
                            @endif
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-xs">
                                    {{ strtoupper(substr($purchase->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm truncate leading-tight group-hover:text-indigo-600 transition-colors">
                                        {{ $purchase->user->name ?? 'Guest User' }}
                                    </p>
                                    <p class="text-xs text-slate-400 truncate mt-0.5">{{ $purchase->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Course / Item -->
                        <td class="px-6 py-4">
                            <p class="font-medium text-slate-800 text-xs truncate max-w-xs">
                                {{ $purchase->purchasable->title ?? $purchase->course->title ?? 'Item #' . $purchase->purchasable_id }}
                            </p>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase">
                                {{ class_basename($purchase->purchasable_type ?? 'Course') }}
                            </span>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono font-bold text-slate-900 text-sm">
                                {{ $purchase->currency ?? 'INR' }} {{ number_format($purchase->amount, 2) }}
                            </div>
                        </td>

                        <!-- Payment Method -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                <span class="material-symbols-rounded text-sm text-slate-400">credit_card</span>
                                {{ ucfirst($purchase->payment_method ?? 'Gateway') }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($purchase->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Completed
                                </span>
                            @elseif($purchase->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @elseif($purchase->status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 font-mono text-xs text-slate-500 whitespace-nowrap">
                            {{ $purchase->created_at->format('M d, Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.purchases.show', $purchase) }}" title="View Invoice" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">visibility</span>
                                </a>

                                <a href="{{ route('admin.purchases.edit', $purchase) }}" title="Edit Order" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                    <span class="material-symbols-rounded text-lg">edit</span>
                                </a>

                                <form action="{{ route('admin.purchases.destroy', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete order #{{ $purchase->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Order" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr class="no-filter">
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="h-12 w-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <span class="material-symbols-rounded text-2xl">receipt_long</span>
                            </div>
                            <p class="text-sm font-semibold text-slate-700">No transactions recorded</p>
                            <p class="text-xs text-slate-400 mt-1">Student purchases will appear here in real time.</p>
                            <a href="{{ route('admin.purchases.create') }}" class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">
                                + Log Manual Purchase
                            </a>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="purchasesDataTable-empty" style="display: none;" class="no-filter">
                        <td colspan="8" class="px-6 py-10 text-center text-xs text-slate-400">
                            <span class="material-symbols-rounded text-2xl text-slate-300 block mb-1">search_off</span>
                            No matching orders found for this search.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Material Footer / Pagination -->
        @if($purchases->hasPages())
        <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page {{ $purchases->currentPage() }} of {{ $purchases->lastPage() }}
            </span>
            <div>
                {{ $purchases->links() }}
            </div>
        </div>
        @endif

    </div>

</div>
@endsection

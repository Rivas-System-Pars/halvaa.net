<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SettlementController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('settlements');
        $settlements = Settlement::query()
            ->latest()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('card_number', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('shaba', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('amount', 'LIKE', '%' . $request->search . '%');
            })->paginate(10);
        return view('back.settlement.index', compact('settlements'));
    }

    public function show($settlement)
    {
        $this->authorize('settlements');
        $settlement = Settlement::query()->findOrFail($settlement);
        return view('back.settlement.show', compact('settlement'));
    }

    public function changeStatus(Request $request, $settlement)
    {
        $this->authorize('settlements');
        $settlement = Settlement::query()->where('status', Settlement::STATUS_PENDING)->findOrFail($settlement);
        $this->validate($request, [
            'status' => ['required', Rule::in(Settlement::STATUSES)],
        ]);
        try {
            return DB::transaction(function () use ($request, $settlement) {
                if ($settlement->status != $request->status) {
                    $done_at = null;
                    if ($request->status == Settlement::STATUS_DONE) {
                        $done_at = now();
                    }
                    $settlement->update(['status' => $request->status, 'done_at' => $done_at]);
                    if (in_array($request->status, [Settlement::STATUS_CANCELED, Settlement::STATUS_REJECTED])) {
                        $settlement->user->wallet()->update(['balance' => $settlement->user->wallet->balance + $settlement->amount]);
                    }
                }
                return redirect()->route('admin.settlements.index');
            });
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors()->withInput();
        }
    }
}

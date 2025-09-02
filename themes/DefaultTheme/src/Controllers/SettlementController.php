<?php

namespace Themes\DefaultTheme\src\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Rules\CardNumberRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettlementController extends Controller
{
    public function index()
    {
        $settlements = auth()->user()->settelements()->latest()->paginate(10);
        return view('front::user.settlement.index', compact('settlements'));
    }

    public function show($settlement)
    {
        $settlement = auth()->user()->settelements()->findOrFail($settlement);
        return view('front::user.settlement.show', compact('settlement'));
    }

    public function cancel($settlement)
    {
        $settlement = auth()->user()->settelements()->where('status', Settlement::STATUS_PENDING)->findOrFail($settlement);
        try {
            return DB::transaction(function () use ($settlement) {
                $settlement->update(['status' => Settlement::STATUS_CANCELED]);
                auth()->user()->wallet()->update(['balance' => auth()->user()->wallet->balance + $settlement->amount]);
                return redirect()->route('front.settlements.index');
            });
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors();
        }
    }

    public function create()
    {
        if (auth()->user()->settelements()->where('status', Settlement::STATUS_PENDING)->exists()) {
            return redirect()->route('front.settlements.index')->with(['error' => "شما یک درخواست تسویه در حال بررسی دارید"]);
        }
        return view('front::user.settlement.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string'],
            'card_number' => ['required', 'string', new CardNumberRule()],
            'shaba' => ['required', 'string', 'regex:/^(?:IR)(?=.{24}$)[0-9]*$/'],
            'amount' => ['required', 'numeric', 'between:10000,' . auth()->user()->wallet->balance],
        ], [], [
            'name' => "نام صاحب حساب",
            'card_number' => "شماره کارت",
            'shaba' => "شماره شبا",
            'amount' => "مبلغ",
        ]);
        try {
            return DB::transaction(function () use ($request) {
                Settlement::query()->create([
                    'user_id' => auth()->id(),
                    'name' => $request->name,
                    'card_number' => $request->card_number,
                    'shaba' => $request->shaba,
                    'amount' => $request->amount,
                ]);
                auth()->user()->wallet()->update(['balance' => auth()->user()->wallet->balance - $request->amount]);
                return redirect()->route('front.settlements.index');
            });
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->withErrors();
        }
    }
}

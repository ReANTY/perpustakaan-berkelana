<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        return view('books.index');
    }

    public function show(Book $book): View
    {
        $today = now();
        $defaultPickup = $today->isSunday()
            ? $today->copy()->addDay()->toDateString()
            : $today->copy()->next(Carbon::SUNDAY)->toDateString();

        return view('books.show', [
            'book' => $book,
            'defaultPickupDate' => $defaultPickup,
        ]);
    }

    public function borrow(Request $request, Book $book): RedirectResponse
    {
        $user = $request->user();

        if ($user->is_suspended) {
            return back()->with('error', 'Akun Anda sedang ditangguhkan sementara dan tidak dapat mengajukan peminjaman.');
        }

        $validated = $request->validate([
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'collateral_type' => ['required', 'in:KTP,KTM,SIM,Kartu Pelajar'],
        ], [
            'pickup_date.required' => 'Rencana tanggal pengambilan wajib diisi.',
            'pickup_date.after_or_equal' => 'Tanggal pengambilan minimal hari ini.',
            'collateral_type.required' => 'Pilih jenis jaminan fisik terlebih dahulu.',
            'collateral_type.in' => 'Jenis jaminan tidak valid.',
        ]);

        $loan = DB::transaction(function () use ($book, $user, $validated) {
            $lockedBook = Book::where('id', $book->id)->lockForUpdate()->firstOrFail();

            if (! $lockedBook->isAvailable()) {
                return null;
            }

            $loan = Loan::create([
                'loan_code' => Loan::generateLoanCode(),
                'user_id' => $user->id,
                'book_id' => $lockedBook->id,
                'duration_days' => 7,
                'pickup_date' => $validated['pickup_date'],
                'collateral_type' => $validated['collateral_type'],
                'status' => 'pending_pickup',
            ]);

            $lockedBook->update(['status' => 'booked']);

            return $loan;
        });

        if (! $loan) {
            return back()->with('error', 'Maaf, buku ini baru saja dibooking atau dipinjam anggota lain.');
        }

        return redirect()->route('loans.confirmation', $loan);
    }

    public function requestLapak(Request $request, Book $book): RedirectResponse
    {
        $sessionKey = 'lapak_requested_books.' . $book->id;
        $cacheKey = 'lapak_req:' . $book->id . ':' . $request->ip() . ':' . now()->toDateString();

        if ($request->session()->has($sessionKey) && $request->session()->get($sessionKey) === now()->toDateString()) {
            return back()->with('info', 'Kamu sudah meminta buku ini hari ini. Terima kasih atas antusiasmenya!');
        }

        if (! Cache::add($cacheKey, true, now()->endOfDay())) {
            return back()->with('info', 'Kamu sudah meminta buku ini hari ini. Terima kasih atas antusiasmenya!');
        }

        $request->session()->put($sessionKey, now()->toDateString());
        $book->increment('lapak_request_count');

        return back()->with('success', 'Permintaan dicatat! Buku ini akan diprioritaskan untuk dibawa ke Lapak Baca Buku Gratis berikutnya.');
    }
}

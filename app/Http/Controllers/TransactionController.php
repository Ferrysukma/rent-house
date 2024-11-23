<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\Store;
use App\Models\Listing;
use App\Models\Transaction;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private function _fullyBooked(Store $request) {
        $listing    = Listing::find($request->listing_id);
        $runningTransactionCount    = Transaction::where('listing_id', $listing->id)
            ->whereNot('status', 'canceled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                ->orWhereBetween('end_date', [ $request->start_date, $request->end_date])
                ->orWhere(function ($subquery) use ($request) {
                    $subquery->where('start_date', '<' , $request->start_date)
                        ->where('end_date', '>' , $request->end_date);
                });

            })->count();

            if ($runningTransactionCount >= $listing->max_person) {
                throw new HttpResponseException(
                    response()->json([
                        'success'   => false,
                        'message'   => 'Listing is fully booked',
                    ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
                );
            }
        return false;
    }

    public function index () {
        $transaction    = Transaction::with('listing')->whereUserId(auth()->user()->id)->orderBy('created_at','desc')->paginate(10);
        return json_encode([
            'success'   => true,
            'message'   => 'Transaction List',
            'data'      => $transaction
        ]);
    }

    public function isAvailable(Store $request) {
        $this->_fullyBooked($request);
        return json_encode([
            'success'   => true,
            'message'   => 'Listing ready to book'
        ]);
    }

    public function store(Store $request) {
        $this->_fullyBooked($request);
        
        $transaction    = Transaction::create([
            'start_date'    => date('Y-m-d', strtotime($request->start_date)),
            'end_date'      => date('Y-m-d', strtotime($request->end_date)),
            'user_id'       => auth()->id(),
            'listing_id'    => $request->listing_id,
        ]);

        $transaction->Listing;
        return json_encode([
            'success'   => true,
            'message'   => 'Transaction Success',
            'data'      => $transaction
        ]);
    }

    public function show(Transaction $transaction) {
        if (auth()->user()->id != $transaction->user_id) {
            return json_encode([
                'success'   => false,
                'message'   => 'Unauthorized'
            ]);
        }

        $transaction->listing;
        return json_encode([
            'success'=> true,
            'message'=> 'Detail Transaction',
            'data'=> $transaction
        ]);
    }
}

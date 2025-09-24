<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    /**
     * Récupérer les transactions de l'utilisateur connecté
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => TransactionResource::collection($transactions),
        ]);
    }

    /**
     * Créer une nouvelle transaction
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:positive,negative',
                'amount' => 'required|numeric|min:0.01',
                'category' => 'required|string|max:255',
                'service' => 'required|string|max:255',
                'date' => 'required|date',
            ]);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type' => $request->type,
                'amount' => $request->amount,
                'category' => $request->category,
                'service' => $request->service,
                'date' => $request->date,
            ]);

            return response()->json([
                'success' => true,
                'data' => new TransactionResource($transaction),
                'message' => 'Transaction créée avec succès'
            ], 201);

        } catch (\Exception $e) {
            logger('Erreur création transaction: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la transaction',
                'error' => config('app.debug') ? $e->getMessage() : 'Erreur serveur'
            ], 500);
        }
    }
}

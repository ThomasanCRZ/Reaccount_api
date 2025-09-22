<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // Récupérer toutes les transactions de l'utilisateur connecté
    public function index(Request $request)
    {
        $user = $request->user();

        $transactions = Transaction::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'data'    => TransactionResource::collection($transactions),
        ]);
    }

    // Créer une nouvelle transaction
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:positive,negative',
            'category' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'service' => $request->service,
            'amount' => $request->amount,
            'type' => $request->type,
            'category' => $request->category,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction créée avec succès',
            'data' => $transaction
        ], 201);
    }

    // Récupérer une transaction spécifique
    public function show($id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction non trouvée'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    // Mettre à jour une transaction
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction non trouvée'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:positive,negative',
            'category' => 'required|string|max:50',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction->update([
            'service' => $request->service,
            'amount' => $request->amount,
            'type' => $request->type,
            'category' => $request->category,
            'date' => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction mise à jour avec succès',
            'data' => $transaction
        ]);
    }

    // Supprimer une transaction
    public function destroy($id)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction non trouvée'
            ], 404);
        }

        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaction supprimée avec succès'
        ]);
    }
}
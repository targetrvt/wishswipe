<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NegotiateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NegotiateRequestController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        
        $requests = NegotiateRequest::with(['product', 'buyer', 'seller'])
            ->where(function ($query) use ($userId) {
                $query->where('buyer_id', $userId)
                      ->orWhere('seller_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($requests);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'proposed_price' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = Product::findOrFail($request->product_id);
        
        // Check if user is not the product owner
        if ($product->user_id === Auth::id()) {
            return response()->json(['error' => 'Cannot negotiate on your own product'], 403);
        }

        // Check if proposed price is less than product price
        if ($request->proposed_price >= $product->price) {
            return response()->json(['error' => 'Proposed price must be less than product price'], 422);
        }

        // Check if there's already a pending request for this product by this user
        $existingRequest = NegotiateRequest::where('buyer_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return response()->json(['error' => 'You already have a pending request for this product'], 422);
        }

        $negotiateRequest = NegotiateRequest::create([
            'buyer_id' => Auth::id(),
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'proposed_price' => $request->proposed_price,
            'message' => $request->message,
            'expires_at' => now()->addDays(7),
        ]);

        return response()->json([
            'message' => 'Negotiation request created successfully',
            'data' => $negotiateRequest->load(['product', 'buyer', 'seller'])
        ], 201);
    }

    public function show(NegotiateRequest $negotiateRequest): JsonResponse
    {
        // Check if user is involved in this request
        if ($negotiateRequest->buyer_id !== Auth::id() && $negotiateRequest->seller_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($negotiateRequest->load(['product', 'buyer', 'seller', 'counterOffers']));
    }

    public function accept(NegotiateRequest $negotiateRequest): JsonResponse
    {
        // Check if user is the seller
        if ($negotiateRequest->seller_id !== Auth::id()) {
            return response()->json(['error' => 'Only the seller can accept requests'], 403);
        }

        if (!$negotiateRequest->canBeAccepted()) {
            return response()->json(['error' => 'This request cannot be accepted'], 422);
        }

        if ($negotiateRequest->accept()) {
            return response()->json([
                'message' => 'Request accepted successfully',
                'data' => $negotiateRequest->fresh(['product', 'buyer', 'seller'])
            ]);
        }

        return response()->json(['error' => 'Failed to accept request'], 500);
    }

    public function decline(NegotiateRequest $negotiateRequest): JsonResponse
    {
        // Check if user is the seller
        if ($negotiateRequest->seller_id !== Auth::id()) {
            return response()->json(['error' => 'Only the seller can decline requests'], 403);
        }

        if (!$negotiateRequest->canBeDeclined()) {
            return response()->json(['error' => 'This request cannot be declined'], 422);
        }

        if ($negotiateRequest->decline()) {
            return response()->json([
                'message' => 'Request declined successfully',
                'data' => $negotiateRequest->fresh(['product', 'buyer', 'seller'])
            ]);
        }

        return response()->json(['error' => 'Failed to decline request'], 500);
    }

    public function counterOffer(Request $request, NegotiateRequest $negotiateRequest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'counter_price' => 'required|numeric|min:0.01',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user is the seller
        if ($negotiateRequest->seller_id !== Auth::id()) {
            return response()->json(['error' => 'Only the seller can make counter offers'], 403);
        }

        if (!$negotiateRequest->canBeCounterOffered()) {
            return response()->json(['error' => 'This request cannot be counter offered'], 422);
        }

        try {
            $counterOffer = $negotiateRequest->counterOffer(
                $request->counter_price,
                $request->message
            );

            return response()->json([
                'message' => 'Counter offer created successfully',
                'data' => $counterOffer->load(['product', 'buyer', 'seller'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create counter offer: ' . $e->getMessage()], 500);
        }
    }
}

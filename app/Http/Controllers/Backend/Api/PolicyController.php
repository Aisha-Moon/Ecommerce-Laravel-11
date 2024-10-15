<?php
namespace App\Http\Controllers\Backend\Api;
use App\Models\Policy;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->has('type')) {
            $policies = Policy::where('type', $request->type)->get();
        } else {
            $policies = Policy::all();
        }

        if ($policies->isEmpty()) {
            return ResponseHelper::Out('No policies found.', [], 404);
        }

        return ResponseHelper::Out('Policies retrieved successfully.', $policies, 200);
    }

    public function store(Request $request): JsonResponse
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'type' => 'required|string|in:about,refund,terms,how to buy,contact,complain',
            'des' => 'required|string',
        ]);
    
        // Create a new policy
        $policy = Policy::create($validatedData);
    
        return ResponseHelper::Out('Policy created successfully.', $policy, 201);
    }
    

    public function show($type): JsonResponse
    {
        // Find the policy by type
        $policy = Policy::where('type', $type)->first();
    
        if (!$policy) {
            return ResponseHelper::Out('Policy not found.', [], 404);
        }
    
        return ResponseHelper::Out('Policy retrieved successfully.', $policy, 200);
    }
    

    public function update(Request $request, $id): JsonResponse
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return ResponseHelper::Out('Policy not found.', [], 404);
        }

        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'des' => 'required|string',
        ]);

        $policy->update($validatedData);

        return ResponseHelper::Out('Policy updated successfully.', $policy, 200);
    }

    public function destroy($id): JsonResponse
    {
        $policy = Policy::find($id);

        if (!$policy) {
            return ResponseHelper::Out('Policy not found.', [], 404);
        }

        $policy->delete();

        return ResponseHelper::Out('Policy deleted successfully.', [], 200);
    }
}

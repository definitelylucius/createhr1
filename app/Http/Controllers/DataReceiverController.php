<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; 

class DataReceiverController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender' => 'required|string',
            'data' => 'required|array',
            'timestamp' => 'sometimes|date'
        ]);
        
        // Process the data (example: log it)
    Log::info('Received data from ' . $validated['sender'], [
            'data' => $validated['data'],
            'received_at' => now()
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'received_data' => $validated
        ]);
    }
}
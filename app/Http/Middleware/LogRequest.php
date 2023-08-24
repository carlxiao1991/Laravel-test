<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Get the current date to determine the table name
        $date = now()->format('Y_m_d');
        $tableName = 'requests_' . $date;

        // Check if the table for the day exists, if not, create it
        if (!Schema::hasTable($tableName)) {
            DB::statement("CREATE TABLE $tableName LIKE requests");
        }

        // Generate a unique request ID
        $requestId = uniqid();
        $response = $next($request);
        // Log the request information to the daily table
        DB::table($tableName)->insert([
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => $request->path(),
            'data' => json_encode($request->all()),
            'response' => json_encode($response->getContent()), // You can update this with the actual response content
            'time' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $response;
    }
}

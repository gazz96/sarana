<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Sanitize all input data
        $this->sanitizeRequest($request);

        return $next($request);
    }

    /**
     * Sanitize request input data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function sanitizeRequest(Request $request)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            // Trim whitespace
            if (is_string($value)) {
                $value = trim($value);
                
                // Remove potentially dangerous characters
                $value = $this->cleanInput($value);
            }
        });

        $request->merge($input);
    }

    /**
     * Clean input string from potentially harmful content
     *
     * @param  string  $value
     * @return string
     */
    protected function cleanInput($value)
    {
        // Remove NULL bytes
        $value = str_replace(chr(0), '', $value);
        
        // Remove excessive whitespace
        $value = preg_replace('/\s+/', ' ', $value);
        
        // Basic XSS protection - remove script tags and JavaScript
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);
        $value = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $value);
        $value = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $value);
        $value = preg_replace('/<embed\b[^>]*>/i', '', $value);
        
        // Remove event handlers like onclick, onerror, etc.
        $value = preg_replace('/\bon\w+\s*=/i', '', $value);
        
        return $value;
    }
}
<?php

namespace App\Http\Middleware;

use Closure;

class BlockOnPreviewMode
{

    const BLOCKED_METHODS = [
        'POST', 'PUT', 'PATCH', 'DELETE'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $methodIdBlocked = in_array(request()->method(), self::BLOCKED_METHODS);

        if(config('app.preview') && $methodIdBlocked) {
            return redirect()->route('preview.blocked');
        }

        return $next($request);
    }
}

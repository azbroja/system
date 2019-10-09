<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Redirect;
use Closure;

class IPAddresses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    /**
     * List of valid IPs.
     *
     * @var array
     */
    protected $ips = [
        '31.61.112.168',
        '31.186.198.46',
        '31.0.243.94',
        '192.168.10.1'];

    /**
     * List of valid IP-ranges.
     *
     * @var array
     */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach ($request->getClientIps() as $ip) {
            if (!$this->isValidIp($ip)) {
                return redirect()->away('https://www.google.pl');
            }
        }

        return $next($request);
    }

    /**
     * Check if the given IP is valid.
     *
     * @param $ip
     * @return bool
     */
    protected function isValidIp($ip)
    {
        return in_array($ip, $this->ips);
    }

    /**
     * Check if the ip is in the given IP-range.
     *
     * @param $ip
     * @return bool
     */

}

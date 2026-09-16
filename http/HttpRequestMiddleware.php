<?

namespace Cart;

abstract class HttpRequestMiddleware
{
    protected $next;

    abstract public function handle(?HttpRequestMiddleware $next): ?HttpRequestMiddleware;

    /**
     * check origin 
     */
    public function sameOrigin(): bool
    {
        if (!empty($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == $_SERVER['SERVER_NAME'])) {
            $domain       = $_SERVER['SERVER_NAME'];
            $pattern_ref  = "^http(s){0,1}://$domain\/.*";
            $pattern_ua   = "compatible{1,}";
            $ref_match    = !empty($_SERVER['HTTP_REFERER']) && preg_match("~$pattern_ref~", $_SERVER['HTTP_REFERER']);  
            $ua_match     = !preg_match("~$pattern_ua~", $_SERVER['HTTP_USER_AGENT']); //no bots
            return $ref_match && $ua_match;
        }
        return false;
    }
}

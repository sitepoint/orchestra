<?php
class TwigProxy {
    public function __call($function, $arguments) {
        if (!function_exists($function)) {
            trigger_error('call to unexisting function ' . $function, E_USER_ERROR);
            return NULL;
        }
        return call_user_func_array($function, $arguments);
    }
}

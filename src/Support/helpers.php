<?php

if (! function_exists('observer_user_id')) {
    function observer_user_id(): ?int
    {
        return \nplesa\observer\Support\ObserverContext::getUserId();
    }
}
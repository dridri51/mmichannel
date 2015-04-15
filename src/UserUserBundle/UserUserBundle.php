<?php

namespace UserUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UserUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

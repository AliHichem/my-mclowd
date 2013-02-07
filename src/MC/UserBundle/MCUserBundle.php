<?php

namespace MC\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MCUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

<?php

namespace AppBundle\Security;

class EvilSecurityRobot
{
    public function doesRobotAllowAccess()
    {
        return rand(0, 10) >= 5;
    }
}

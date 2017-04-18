<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    private $robot;

    public function __construct(EvilSecurityRobot $robot)
    {
        $this->robot = $robot;
    }

    protected function supports($attribute, $object)
    {
        if ($attribute != 'USER_VIEW') {
            return false;
        }

        if (!$object instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $object, TokenInterface $token)
    {
        return $this->robot->doesRobotAllowAccess();
    }
}
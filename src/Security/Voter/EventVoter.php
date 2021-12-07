<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EVENT_EDIT', 'EVENT_DELETE'])
            && $subject instanceof \App\Entity\Event;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_MODERATOR')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EVENT_EDIT':
                if($subject->getAuthor()->getId() === $user) {
                    return true;
                }
                break;
            case 'EVENT_DELETE':
                if($subject->getAuthor()->getId() === $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
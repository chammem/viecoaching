<?php
namespace App\EntityListener;


use App\Entity\Utilisateur;
use Doctrine\Persistence\Event\LifecycleEventArgs ;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserListener
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(Utilisateur $user, LifecycleEventArgs $args)
    {
        $user->setMdp($this->passwordHasher->hashPassword($user, $user->getMdp()));
    }

    public function preUpdate(Utilisateur $user, LifecycleEventArgs $args)
    {
        $user->setMdp($this->passwordHasher->hashPassword($user, $user->getMdp()));
    }
}
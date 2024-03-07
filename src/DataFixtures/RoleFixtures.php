<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       // Créer et configurer les rôles par défaut
        $rolesData = [
            ['nom_role' => 'ROLE_PATIENT'],
            ['nom_role' => 'ROLE_COACH'],
            ['nom_role' => 'ROLE_ADMIN'],
        ];

        foreach ($rolesData as $roleData) {
            $role = new Role();
            $role->setNomRole($roleData['nom_role']);
            
            // Persistez l'objet de rôle
            $manager->persist($role);
        }

        // Flush des données dans la base de données
        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UtilisateurFixtures extends Fixture


{
   
    private $mailer;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher , MailerInterface $mailer)
    {
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }
    

    public function load(ObjectManager $manager): void
    {
        // Récupération du rôle Admin
        $roleAdmin = $manager->getRepository(Role::class)->findOneBy(['nom_role' => 'Admin']);

        // Création d'un utilisateur admin
        $admin = new Utilisateur();
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setEmail('chammemcinda@gmail.com');
        $admin->setTel('0123456789');
        $admin->setImage('default.jpg');
        $temporaryPassword = $this->generateTemporaryPassword();
        $admin->setMdp($this->passwordHasher->hashPassword(
            $admin,
            $temporaryPassword
        ));
        $admin->setGenre('genre');
        $admin->setVille('ville');
        $admin->setRole($roleAdmin);

        // Envoyer l'e-mail avec le mot de passe temporaire
        $this->sendTemporaryPasswordByEmail($admin->getEmail(), $temporaryPassword);

        $manager->persist($admin);
        $manager->flush();
    }

    private function generateTemporaryPassword(): string
    {
        // Génération d'un mot de passe temporaire aléatoire
        return bin2hex(random_bytes(8)); // Génère une chaîne hexadécimale de 16 caractères
    }
    private function sendTemporaryPasswordByEmail(string $recipient, string $temporaryPassword): void
    {
        $email = (new Email())
            ->from('chammemsinda4@gmail.com')
            ->to($recipient)
            ->subject('Votre mot de passe temporaire')
            ->text(sprintf(
                "Votre mot de passe temporaire est : %s. Veuillez vous connecter avec ce mot de passe et le changer dès que possible.",
                $temporaryPassword
            ));
    
        $this->mailer->send($email);
}}
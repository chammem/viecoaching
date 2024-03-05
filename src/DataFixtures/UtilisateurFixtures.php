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

    public function __construct(MailerInterface $mailer)
    {
        
        $this->mailer = $mailer;
    }

    public function load(ObjectManager $manager): void
    {
        // Récupération du rôle Admin
        $roleAdmin = $manager->getRepository(Role::class)->findOneBy(['nom_role' => 'ROLE_ADMIN']);

        // Création d'un utilisateur admin
        $admin = new Utilisateur();
        $admin->setNom('Admin');
        $admin->setPrenom('Admin');
        $admin->setEmail('mahdi@gmail.com');
        $admin->setTel('0123456789');
        $admin->setImage('default.jpg');
       // Générer un mot de passe temporaire pour le premier administrateur
       $temporaryPassword = $this->generateTemporaryPassword();

       // Définir le mot de passe en clair sur l'entité Admin
       $admin->setMdp($temporaryPassword);
       $admin->setRole($roleAdmin);
       $admin->setGenre('genre');
       $admin->setVille('ville');

       // Envoyer l'e-mail avec le mot de passe temporaire
       $this->sendTemporaryPasswordByEmail($admin->getEmail(), $temporaryPassword);

       $manager->persist($admin);
       $manager->flush();
   }


private function generateTemporaryPassword(): string
{
   return bin2hex(random_bytes(4)); // Génère une chaîne hexadécimale de 16 caractères
}

private function sendTemporaryPasswordByEmail(string $recipient, string $temporaryPassword): void
{
   $email = (new Email())
       ->from('votre_email@example.com') // Remplacez par votre adresse e-mail
       ->to($recipient)
       ->subject('Votre mot de passe temporaire')
       ->text(sprintf(
           "Votre mot de passe temporaire est : %s. Veuillez vous connecter avec ce mot de passe et le changer dès que possible.",
           $temporaryPassword
       ));

   $this->mailer->send($email);
}
}
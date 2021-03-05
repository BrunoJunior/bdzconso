<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$firstname, $lastname, $password, $email, $roles]) {
            $user = new User();
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setFirstName($firstname);
            $user->setLastname($lastname);
            $user->setRoles($roles);

            $manager->persist($user);
            $this->addReference($email, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$firstname, $lastname, $password, $email, $roles];
            ['Jane', 'Doe', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom', 'Doe', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John', 'Doe', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
}

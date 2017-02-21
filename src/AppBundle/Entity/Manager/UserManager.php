<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

class UserManager
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    /** @var ObjectManager */
    protected $objectManager;

    /** @var EntityRepository */
    protected $repository;

    /** @var string */
    protected $class;

    public function __construct(EncoderFactoryInterface $encoderFactory, ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        $this->encoderFactory = $encoderFactory;

        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * Returns an empty user instance.
     *
     * @return SecurityUserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class();

        return $user;
    }

    /**
     * Updates a user.
     *
     * @param SecurityUserInterface $user
     * @param Boolean               $andFlush Whether to flush the changes (default true)
     */
    public function updateUser(SecurityUserInterface $user, $andFlush = true)
    {
        $this->updatePassword($user);

        $this->objectManager->persist($user);
        if ($andFlush) {
            $this->objectManager->flush();
        }

        return $user;
    }

    /**
     * Finds a user by email.
     *
     * @param string $email
     *
     * @return SecurityUserInterface
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(['email' => $email]);
    }

    /**
     * Finds a user by username.
     *
     * @param string $username
     *
     * @return SecurityUserInterface
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(['username' => $username]);
    }

    /**
     * Finds a user either by email, or username.
     *
     * @param string $usernameOrEmail
     *
     * @return SecurityUserInterface
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * Finds a user either by confirmation token.
     *
     * @param string $token
     *
     * @return SecurityUserInterface
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(['confirmationToken' => $token]);
    }

    public function findUserBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function updatePassword(SecurityUserInterface $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    protected function getEncoder(SecurityUserInterface $user)
    {
        return $this->encoderFactory->getEncoder($user);
    }
}

<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ValidUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function validate($value, Constraint $constraint)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByEmail($value->getEmail());

        if(!$user) {
            return;
        }

        if (($value->getId() != $user->getId()) && $this->context->getGroup() == 'registration') {
            $this->context->buildViolation('error.email_already_registered')
                ->atPath('email')
                ->addViolation();
        }

        if(!$user->isEnabled()) {
            $email = $value->getUsername();

            $route = $this->generateRoute('register_resend_confirmation', [
                'email' => $email
            ]);

            $this->context->buildViolation('error.inactive_user')
                ->setParameter('%url%', $route)
                ->addViolation();
        }
    }

    private function generateRoute($route, $routeParams)
    {
        return $this->router->generate($route, $routeParams);
    }
}
<?php
/**
 * This file is part of Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as CustomAssert;

/** 
 * @CustomAssert\ValidInvite 
 */
class Invite
{
    /** @var int */
    private $id;

    /**
     * @var Company
     */
    private $company;

    /**
     * @var User
     */
    private $sender;

    /**
     * @var User
     */
    private $receiver;

    /**
     * @Assert\NotBlank(message="error.empty_email")
     * @Assert\Email(message="error.invalid_email")
     *
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $token;

    public static function create(Company $company, User $sender, $token)
    {
        $entity = new self();
        $entity->company = $company;
        $entity->sender = $sender;
        $entity->token = $token;

        return $entity;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender(User $sender)
    {
        $this->sender = $sender;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver(User $receiver = null)
    {
        $this->receiver = $receiver;
        if($receiver) {
            $receiver->addReceivedInvite($this);
        }
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    function __toString()
    {
        return $this->email;
    }


}

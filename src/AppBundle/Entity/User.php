<?php
/**
 * This file is part of the Invox.
 * (c) Di-SiDE s.r.l.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace AppBundle\Entity;

use AppBundle\Model\UserInterface;
use AppBundle\Validator\Constraints as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @CustomAssert\ValidUser(groups={"registration", "request_reset_password"})
 */
class User implements UserInterface
{
    const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';
    const ROLE_ACCOUNTANT = 'ROLE_ACCOUNTANT';
    const ROLE_SALES_AGENT = 'ROLE_SALES_AGENT';
    const ROLE_CLERK = 'ROLE_CLERK';
    const ROLE_OWNER = 'ROLE_OWNER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_PREVIOUS_ADMIN = 'ROLE_PREVIOUS_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @Assert\NotBlank(message="error.empty_email", groups={"default", "update", "registration", "request_reset_password"})
     * @Assert\Email(message="error.invalid_email", groups={"default", "update", "registration", "request_reset_password"})
     *
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * The salt to use for hashing.
     *
     * @var string
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @Assert\NotBlank(message="error.empty_password", groups={"default", "registration", "change_password", "reset_password"})
     * @Assert\Length(min=6, groups={"default", "registration", "change_password", "reset_password"})
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it.
     *
     * @var string
     */
    protected $confirmationToken;

    /**
     * Random string sent to the user email address in order to reset the password.
     *
     * @var string
     */
    protected $resetPasswordToken;

    /**
     * @var \DateTime
     */
    protected $passwordRequestedAt;

    /**
     * @var array
     */
    protected $roles;

    /**
     * @var ArrayCollection
     */
    private $ownedCompanies;

    /**
     * @var ArrayCollection
     */
    private $managedCompanies;

    /**
     * @var ArrayCollection
     */
    private $accountedCompanies;

    /**
     * @var ArrayCollection
     */
    private $marketedCompanies;

    /**
     * @var ArrayCollection
     */
    private $sentInvites;

    /**
     * @var ArrayCollection
     */
    private $receivedInvites;

    public static function create($email, $password, $salt)
    {
        $entity = new self();

        $entity->username = $email;
        $entity->email = $email;
        $entity->password = $password;
        $entity->salt = $salt;

        return $entity;
    }

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->roles = [];

        $this->ownedCompanies = new ArrayCollection();
        $this->managedCompanies = new ArrayCollection();
        $this->marketedCompanies = new ArrayCollection();
        $this->accountedCompanies = new ArrayCollection();
        $this->sentInvites = new ArrayCollection();
        $this->receivedInvites = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->getEmail();
    }

    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if ($this->username == null) {
            $this->setUsername($email);
        }

        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean)$boolean;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;

        return $this;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken($resetPasswordToken)
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === self::ROLE_USER) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->password,
            $this->salt,
            $this->username,
            $this->enabled,
            $this->id,
        ]);
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->username,
            $this->enabled,
            $this->id
            ) = $data;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    public function isSuperadmin()
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    public function isAccountant()
    {
        return $this->accountedCompanies->count() > 0;
    }

    public function isOwner()
    {
        return $this->ownedCompanies->count() > 0;
    }

    public function isClerk()
    {
        return $this->managedCompanies->count() > 0;
    }

    public function isSalesAgent()
    {
        return $this->marketedCompanies->count() > 0;
    }

    public function isSameAs(User $user)
    {
        return $this->getId() === $user->getId();
    }

    /**
     * @inheritdoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function getOwnedCompanies()
    {
        return $this->ownedCompanies;
    }

    public function setOwnedCompanies($ownedCompanies)
    {
        /** @var Company $company */
        foreach ($ownedCompanies as $company) {
            $this->addOwnedCompany($company);
        }
    }

    public function addOwnedCompany(Company $company)
    {
        $this->ownedCompanies->add($company);
        $company->setOwner($this);
    }

    public function hasOwnedCompanies()
    {
        return $this->ownedCompanies->count() > 0;
    }

    public function getManagedCompanies()
    {
        return $this->managedCompanies;
    }

    public function setManagedCompanies($managedCompanies)
    {
        /** @var Company $company */
        foreach ($managedCompanies as $company) {
            $this->addManagedCompany($company);
        }
    }

    public function addManagedCompany(Company $company)
    {
        $this->managedCompanies->add($company);
        $company->addManager($this);
    }

    public function removeManagedCompany(Company $company)
    {
        $this->managedCompanies->removeElement($company);
        $company->removeManager($this);
    }

    public function getAccountedCompanies()
    {
        return $this->accountedCompanies;
    }

    public function setAccountedCompanies($accountedCompanies)
    {
        $this->accountedCompanies = $accountedCompanies;
    }

    public function addAccountedCompany(Company $company)
    {
        $this->accountedCompanies->add($company);
        $company->setAccountant($this);
    }

    public function removeAccountedCompany(Company $company)
    {
        $this->accountedCompanies->removeElement($company);
        $company->setAccountant(null);
    }

    public function getMarketedCompanies()
    {
        return $this->marketedCompanies;
    }

    public function setMarketedCompanies($marketedCompanies)
    {
        $this->marketedCompanies = $marketedCompanies;
    }

    public function addMarketedCompany(Company $company)
    {
        $this->marketedCompanies->add($company);
        $company->addSalesAgent($this);
    }

    public function removeMarketedCompany(Company $company)
    {
        $this->marketedCompanies->removeElement($company);
        $company->removeSalesAgent($this);
    }

    public function getDefaultCompany()
    {
        if ($this->ownedCompanies->count() > 0) {
            return $this->ownedCompanies->get(0);
        }

        return null;
    }

    public function canAddAccount()
    {
        return $this->hasOwnedCompanies();
    }

    public function hasCompanyManager(User $user)
    {
        /** @var Company $ownedCompany */
        foreach ($this->ownedCompanies as $ownedCompany) {
            if ($ownedCompany->hasManager($user)) {
                return true;
            }
        }

        return false;
    }

    public function ownsCompany(Company $company)
    {
        /** @var Company $ownedCompany */
        foreach ($this->ownedCompanies as $ownedCompany) {
            if ($ownedCompany->isSameAs($company)) {
                return true;
            }
        }

        return false;
    }

    public function ownsAccount(Account $account)
    {

        foreach ($this->ownedCompanies as $ownedCompany) {
            if ($ownedCompany->isSameAs($account->getCompany())) {
                return true;
            }
        }

        return false;
    }

    public function canEditCustomer(Customer $customer)
    {
        return $this->canManageCompany($customer->getCompany());
    }

    public function canDeleteCustomer(Customer $customer)
    {
        return $this->canManageCompany($customer->getCompany());
    }

    public function canManageDocument(Document $document)
    {
        return $this->canManageCompany($document->getCompany());
    }

    public function canManageRecurrence(Recurrence $recurrence)
    {
        return $this->canManageCompany($recurrence->getCompany());
    }

    public function canManageCompany(Company $company)
    {
        return
            $this->isSuperadmin()
            || $this->managedCompanies->contains($company)
            || $this->ownedCompanies->contains($company);
    }

    public function canManageParagraphTemplates(Company $company)
    {
        return $this->canManageWorkingNotes($company);
    }

    public function canManageWorkingNotes(Company $company)
    {
        return
            $this->isSuperadmin()
            || $this->managedCompanies->contains($company)
            || $this->marketedCompanies->contains($company)
            || $this->ownedCompanies->contains($company);
    }

    public function getDecimalPoint()
    {
        return '.';
    }

    public function getNumberSeparator()
    {
        return ',';
    }

    public function canManageMultipleCompanies()
    {
        return $this->isSuperadmin() || ($this->isManagingMultipleCompanies());
    }

    public function ownsProduct(Product $product)
    {
        return $this->canManageCompany($product->getCompany());
    }

    public function ownsService(Service $service)
    {
        return $this->canManageCompany($service->getCompany());
    }

    public function isAccountantFor(Company $company)
    {
        return $company->hasAccountant() && $company->getAccountant()->isSameAs($this);
    }

    public function isSalesAgentFor(Company $company)
    {
        return $company->hasSalesAgent($this);
    }

    public function isManagingMultipleCompanies()
    {
        return $this->managedCompanies->count() > 1;
    }

    public function hasReceivedInvites()
    {
        return $this->receivedInvites->count() > 0;
    }

    public function getSentInvites()
    {
        return $this->sentInvites;
    }

    public function setSentInvites($sentInvites)
    {
        $this->sentInvites = $sentInvites;
    }

    public function getReceivedInvites()
    {
        return $this->receivedInvites;
    }

    public function addReceivedInvite(Invite $invite)
    {
        $this->receivedInvites->add($invite);
    }

    public function setReceivedInvites($receivedInvites)
    {
        $this->receivedInvites = $receivedInvites;
    }

    public function canShowPettyCashNote(PettyCashNote $note)
    {
        return $this->isAccountant() && $this->isAccountantFor($note->getCompany());
    }

    public function isManagingSingleCompany()
    {
        return $this->isManager() && !$this->isManagingMultipleCompanies();
    }

    private function isManager()
    {
        return $this->managedCompanies->count() > 0;
    }

    public function canDeleteMedium(Medium $medium)
    {
        return $this->canManageCompany($medium->getContainer());
    }

    public function canEditMedium(Medium $medium)
    {
        return $this->canManageCompany($medium->getContainer());
    }

    public function ownsWorkingNote(WorkingNote $workingNote)
    {
        return $this->canManageCompany($workingNote->getCompany()) || $this->canManageWorkingNotes($workingNote->getCompany());
    }

    public function ownsParagraphTemplate(ParagraphTemplate $paragraphTemplate)
    {
        return $this->canManageCompany($paragraphTemplate->getCompany()) || $this->canManageParagraphTemplates($paragraphTemplate->getCompany());
    }

}

<?php

namespace App\Document;

use App\Document\AuthToken\LastLogin;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @MongoDB\Document(db="auth", collection="tokens")
 */
class AuthToken implements TokenInterface
{
    /**
     * @var string
     *
     * @MongoDB\Id(
     *     strategy="CUSTOM",
     *     options={"class": "App\Document\TokenGenerator", "bytes": 32}
     * )
     */
    private $id;

    /**
     * @var User
     *
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="App\Document\User", storeAs="ref")
     */
    private $user;

    /**
     * @var LastLogin
     *
     * @MongoDB\EmbedOne(name="last_login", targetDocument="App\Document\AuthToken\LastLogin")
     */
    private $lastLogin;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="creation_date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="expiration_date")
     * @MongoDB\Index(options={"expireAfterSeconds": 0})
     */
    private $expirationDate;

    public function __construct(
        User $user,
        ?string $ip
    ) {
        $this->user = $user;
        $this->creationDate = new \DateTime();
        $this->updateLastLogin(new LastLogin($ip));
    }

    public function __toString()
    {
        return \sprintf('%s(id="%s", user="%s")', __CLASS__, $this->id, $this->getUsername());
    }

    public function __serialize(): array
    {
        return [
            $this->id,
            $this->user,
            $this->lastLogin,
            $this->creationDate,
            $this->expirationDate,
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            $this->id,
            $this->user,
            $this->lastLogin,
            $this->creationDate,
            $this->expirationDate,
        ] = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLastLogin(): LastLogin
    {
        return $this->lastLogin;
    }

    public function updateLastLogin(LastLogin $login): void
    {
        $this->lastLogin = $login;
        $this->expirationDate = new \DateTime('+15 days');
    }

    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    public function serialize()
    {
        throw new \LogicException('This token is not meant to be serialized.');
    }

    public function unserialize($serialized)
    {
        throw new \LogicException('This token is not meant to be serialized.');
    }

    public function getRoles()
    {
    }

    public function getRoleNames()
    {
    }

    public function getCredentials()
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser($user)
    {
        throw new \LogicException('User can only be set through constructor.');
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function isAuthenticated()
    {
        return true;
    }

    public function setAuthenticated($isAuthenticated)
    {
        throw new \LogicException('This token always is authenticated.');
    }

    public function eraseCredentials()
    {
    }

    public function getAttributes()
    {
        throw new \LogicException('This token is not meant to have attributes.');
    }

    public function setAttributes(array $attributes)
    {
        throw new \LogicException('This token is not meant to have attributes.');
    }

    public function hasAttribute($name)
    {
        throw new \LogicException('This token is not meant to have attributes.');
    }

    public function getAttribute($name)
    {
        throw new \LogicException('This token is not meant to have attributes.');
    }

    public function setAttribute($name, $value)
    {
        throw new \LogicException('This token is not meant to have attributes.');
    }
}

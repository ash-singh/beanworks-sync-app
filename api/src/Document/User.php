<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="users")
 * @MongoDB\UniqueIndex(keys={"email": "asc"})
 */
class User
{
    /**
     * @var string
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="username")
     */
    protected $userName;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $email;

    /**
     * @var string
     * @MongoDB\Field(type="string")
     */
    protected $password;

    /**
     * @var bool
     *
     * @MongoDB\Field(type="bool")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="created_on")
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="updated_on")
     */
    protected $updatedDate;

    public function __construct(
        string $userName,
        string $email,
        string $password
        ) {
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->status = true;
    }

    public function getUserId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

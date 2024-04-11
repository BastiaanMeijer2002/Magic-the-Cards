<?php

namespace Model;

class User
{
    public string $tableName = "users";

    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;

    /**
     * @param string $name
     * @param string $email
     * @param string $passwordHash
     */
    public function __construct(string $name, string $email, string $passwordHash)
    {
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }




}
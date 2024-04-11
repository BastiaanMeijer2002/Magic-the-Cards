<?php

namespace Model;

class User
{
    public string $tableName = "users";

    public int $id;
    public string $name;
    public string $email;
    public string $passwordHash;
    public int $isAdmin;
    public int $isPremium;

    /**
     * @param string $name
     * @param string $email
     * @param string $passwordHash
     * @param int $isAdmin
     * @param int $isPremium
     */
    public function __construct(string $name, string $email, string $passwordHash, int $isAdmin = 0, int $isPremium = 0)
    {
        $this->name = $name;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->isAdmin = $isAdmin;
        $this->isPremium = $isPremium;
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

    /**
     * @return int
     */
    public function getIsAdmin(): int
    {
        return $this->isAdmin;
    }

    /**
     * @param int $isAdmin
     */
    public function setIsAdmin(int $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return int
     */
    public function getIsPremium(): int
    {
        return $this->isPremium;
    }

    /**
     * @param int $isPremium
     */
    public function setIsPremium(int $isPremium): void
    {
        $this->isPremium = $isPremium;
    }








}
<?php

namespace Siplo\SchedulerBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\ClassLoader\ClassMapGenerator;

/**
 * Class Reminder
 * @package Siplo\SchedulerBundle\Document
 * @MongoDB\Document()
 */
Class Reminder
{
    /**
     * @MongoDB\Id()
     */
    protected $id;

    /**
     * @MongoDB\String()
     */
    protected $firstName;

    /**
     * @MongoDB\String()
     */
    protected $email;

    /**
     * @MongoDB\Timestamp()
     */
    protected $time;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set time
     *
     * @param timestamp $time
     * @return self
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return timestamp $time
     */
    public function getTime()
    {
        return $this->time;
    }
}

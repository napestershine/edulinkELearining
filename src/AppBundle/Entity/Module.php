<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Teacher")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}


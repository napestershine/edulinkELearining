<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SessionRepository")
 */
class Session
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module")
     * @ORM\JoinColumn(name="module_id", referencedColumnName="id")
     */
    private $module;



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


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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Batch")
     * @ORM\JoinColumn(name="batch_id", referencedColumnName="id")
     */
    private $batchId;
    /**
     * @ORM\OneToOne(targetEntity="Siplo\SchedulerBundle\Entity\Event")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    protected $event;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set module
     *
     * @param \AppBundle\Entity\Module $module
     *
     * @return Session
     */
    public function setModule(\AppBundle\Entity\Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \AppBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set batch
     *
     * @param \AppBundle\Entity\Batch $batch
     *
     * @return Session
     */
    public function setBatchId(\AppBundle\Entity\Batch $batch = null)
    {
        $this->batchId = $batch;

        return $this;
    }

    /**
     * Get batch
     *
     * @return \AppBundle\Entity\Batch
     */
    public function getBatchId()
    {
        return $this->batchId;
    }
}

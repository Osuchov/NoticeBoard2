<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Notice", inversedBy="comments")
     */
    private $noticeId;

    /**
     * @var \DateTime
     * @ORM\Column(name="creationDate", type="datetime")
     */
    private $creationDate;

    public function __construct()
    {
        $this->creationDate = (new DateTime());
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
     * Set noticeId
     *
     * @param integer $noticeId
     *
     * @return Comment
     */
    public function setNoticeId($noticeId)
    {
        $this->noticeId = $noticeId;

        return $this;
    }

    /**
     * Get noticeId
     *
     * @return int
     */
    public function getNoticeId()
    {
        return $this->noticeId;
    }

    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Comment
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}

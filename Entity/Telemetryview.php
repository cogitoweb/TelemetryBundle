<?php

namespace Cogitoweb\TelemetryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Telemetryview
 *
 * @author Daniele Artico <daniele.artico@cogitoweb.it>
 */

/**
 * @ORM\Table(name="cogitoweb_telemetryview_bundle",
 * 	uniqueConstraints={
 * 		@ORM\UniqueConstraint(
 * 			name="IDX_cogitoweb_telemetryview_bundle_key", 
 * 			columns={"name"}
 * 		)
 * 	})
 * @ORM\Entity
 */
class Telemetryview {

    use BlameableEntity;

    use TimestampableEntity;

    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max=255)
     * @Assert\NotNull();
     */
    protected $name;

    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", options={"default": true})
     * @Assert\Type("boolean")
     * @Assert\NotNull()
     */
    protected $active = true;

    /**
     *
     * @var string
     * 
     * @ORM\Column(type="text", nullable=false)
     */
    protected $sql;

    public function __toString() {
        return $this->getName() ? : '';
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Parameter
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set sql
     *
     * @param string $sql
     * @return Parameter
     */
    public function setSql($sql) {
        $this->sql = $sql;

        return $this;
    }

    /**
     * Get sql
     *
     * @return string 
     */
    public function getSql() {
        return $this->sql;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Project
     */
    public function setActive($active) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive() {
        return $this->active;
    }

}

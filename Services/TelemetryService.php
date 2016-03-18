<?php

namespace Cogitoweb\TelemetryBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

/**
 * Description of TelemetryService
 *
 * @author Daniele Artico <daniele.artico@cogitoweb.it>
 */
class TelemetryService
{
	protected $em;
	
	/**
	 * Construct
	 * 
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManager $entityManager) {
		$this->em           = $entityManager;
	}
	
	
}
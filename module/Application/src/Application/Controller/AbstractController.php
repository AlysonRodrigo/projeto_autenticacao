<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
abstract class AbstractController extends AbstractActionController
{
	
	protected $em;
	
	public function getEm($entity = null)
	{
		if($entity !== null)
		{
			return $this->em->getRepository($entity);
		}
		
		if($this->em === null)
		{
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		
		return $this->em;
	}
	
	public function paginator($list, $page, $countPerPage = 10)
	{
		$paginator = new Paginator(new ArrayAdapter($list));
		$paginator->setCurrentPageNumber($page);
		$paginator->setDefaultItemCountPerPage($countPerPage);
		
		return $paginator;
	}
	
	
}
<?php

namespace Admin\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
abstract class AbstractService implements ServiceLocatorAwareInterface
{
	protected $em;
	protected $sl;
	protected $entity;
	
	public function setServiceLocator(ServiceLocatorInterface $service) {
		$this->sl = $service;
		$this->em = $service->get('Doctrine\ORM\EntityManager');
		
		return $this;
	}
	
	public function getServiceLocator() {
		return $this->sl;
	}
	
	public function getEm($entity = null)
	{
		if($entity !== null)
			return $this->em->getRepository($entity);
		
		return $this->em;
	}
	
	public function insert(array $data, $entity)
	{
		$entity = $entity ?: $this->entity;
		$entity = new $entity($data);
		
		$em = $this->getEm();
		$em->persist($entity);
		$em->flush();
		
		return $entity;
		
	}
	
	public function update(array $data, $id, $entity = null)
	{
		$entity = $entity ?: $this->entity;
		$entity = $this->getEm()->getReference($entity, $id);
		
		$hydrator = new ClassMethods();
		$hydrator->hydrate($data, $entity);
		
		$em = $this->getEm();
		$em->persist($entity);
		$em->flush();
		
		return $entity;
		
	}
	
	public function delete($id, $entity = null)
	{
		$entity = $entity?: $this->entity;
		$findEntity = $this->getEm($entity)->find($id);
		
		if($findEntity)
		{
			$entity = $this->getEm()->getReference($entity, $id);
			if($entity)
			{
				$em = $this->getEm();
				$em->remove($entity);
				$em->flush();
				
				return $id;
			}
		}
	}
	
	public function getEmRef($entity,$id)
	{
		return $this->em->getReference($entity,$id);
	}
	
	

}
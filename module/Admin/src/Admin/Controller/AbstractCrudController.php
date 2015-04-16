<?php

namespace Admin\Entity;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Validator\AbstractValidator;
use Zend\Session\AbstractContainer;
use Zend\Json\Server\Smd\Service;

abstract class AbstractCrudController extends AbstractActionController
{
	protected $em;
	protected $entity;
	protected $service;
	protected $form;
	protected $filter;
	
	protected $filtro = array();
	protected $where = array();
	protected $order = array();
	protected $template;
	
	protected $route  = "admin/default";
	protected $controlle; 
	protected $module;
	protected $action;
	
	public function indexAction()
	{
		$filtro = $this->params()->fromQuery();
		$this->setFiltro($filtro);
		
		$page = isset($filtro['pagina']) ? $filtro['pagina'] : 1;
		$where = $this->getWhere(array('pagina'));
		
		$list = $this->getEm($this->entity)->findFilter($where,$this->order);
		
		$paginator = $this->paginator($list, $page);
		
		return new ViewModel(array(
				'data'   => $paginator,
				'pagina' => $page,
				'filtro' => $filtro,
				'controller' => $this->controlle
		));
		
	}
	
	public function addAction()
	{
		$request = $this->getRequest();
		
		$form = $this->getServiceLocator()->get($this->form);
		
		// tratando os dados do filtro vindos do container
		$container = new Container(str_replace("-", "", $this->controller));
		
		$url = "";
		$url .= isset($container->filtro['params']['pagina']) ? "pagina=" . $container->filtro['params']['pagina'] : "pagina=1";
		
		if($request->isPost())
		{
			if($this->filter != null)
			{
				$form->setInputFilter($this->getServiceLocator()->get($this->filter));
			}
			
			AbstractValidator::setDefaultTranslator($this->getServiceLocator()->get('MvcTranslator'));
			$data = $request->getPost()->toArray();
			$form->setData($data);
			if($form->isValid())
			{
				$service = $this->getServiceLocator()->get($this->service);
				if($service->insert($data))
				{
					$this->flashMessenger()->addMessage(array('success' => 'Informações inserida com sucesso!'));
				}else{
					$this->flashMessenger()->addMessage(array('error' => 'Houve um erro ao tentar cadastrar o seu registro!'));
				}
				
				if($this->module === null)
				  return $this->redirect()->toRoute($this->route,array('controller' => $this->controlle));
				else 
				  return $this->redirect()->toUrl("/" . $this->module . "/" . $this->controlle . $url);
			}
		}

		
	}
	
	public function editAction()
	{
		$id = $this->params('id');
		$request = $this->getRequest();
		
		$form = $this->getServiceLocator()->get($this->form);
		
		$containner = new Container(str_repeat("-","", $this->controlle));
		
		$url = "?";
		$url .= isset($containner->filtro['params']['pagina']) ? "pagina=" . $containner->filtro['params']['pagina'] : "pagina=1";
		
		$dataEntity = $this->getEm($this->entity)->findToArray($id);
		$form->setData($dataEntity);
		
		if($request->isPost())
		{
			if($this->filter != null)
			{
				$form->setInputFilter($this->getServiceLocator()->get($this->filter));
			}
			

			AbstractValidator::setDefaultTranslator($this->getServiceLocator()->get('MvcTranslator'));
			$data = $request->getPost()->toArray();
			$form->setData($data);
			
			if($form->isValid())
			{
				$service = $this->getServiceLocator()->get($this->service);
				if($service->update($data,$id))
				{
					$this->flashMessenger()->addMessage(array('success' => 'Informações alteradas com sucesso!'));
				}else{
					$this->flashMessenger()->addMessage(array('error' => 'Houve um erro ao tentar alterar suas informações!'));
				}	
				
				if($this->module === null)
					return $this->redirect()->toRoute($this->route,array('controller' => $this->controlle));
				else
					return $this->redirect()->toUrl("/" . $this->module . "/" . $this->controlle . $url);
				
			}
		}
	}
	
	public function deleteAction()
	{
		$id = $this->params('id');
		$service = $this->getServiceLocator()->get($this->service);
		
		if($service->delete($id))
		{
		  $this->flashMessenger()->addMessage(array('success' => 'Registro excluído com sucesso!'));
		}else {
		  $this->flashMessenger()->addMessage(array('error' => 'Houve um erro ao excluir o registro!'));
		}
		
		if($this->module === null)
		   return $this->redirect()->toRoute($this->route,array('controller' => $this->controlle));
		else
		   return $this->redirect()->toUrl("/" . $this->module . "/" . $this->controlle . $url);
		
	}
	
	public function getEmRef($entity, $id)
	{
		return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')->getReference($entity,$id);
	}
	
	public function getIdUsuario()
	{
		return 1;
	}
	
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
	
	public function setFiltro(array $filtro)
	{
		if(count($filtro))
		{
			$this->filtro['params'] = $filtro;
		}
		
		$containner = new Container(str_replace("-", "", $this->controlle));
		$containner->filtro = $this->filtro;
	}
	
	public function getFiltro()
	{
		return $this->filtro;
	}
	
	public function setWhere(array $where)
	{
		if(count($where))
		{
			foreach($where as $id => $val)
			{
				$where[$id] = $val;
			}
		}
	}
	
	public function getWhere(array $arrayException)
	{
		$where = $this->where;
		$filtro = $this->filtro;
		
		if(isset($filtro['params']) && count($filtro['params']))
		{
			foreach($filtro['params'] as $idFiltro => $valFiltro)
			{
				if((!empty($valFiltro) || $valFiltro == '0') && !in_array($idFiltro, $arrayException))
				{
					$where[$idFiltro] = $valFiltro;
				}
			}
		}
		
		return $where;
	}
}
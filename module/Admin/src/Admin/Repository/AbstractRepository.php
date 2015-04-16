<?php
use Doctrine\ORM\EntityRepository;
class AbstractRepository extends EntityRepository {
	
	public function findFilter(array $filtro, $order = array()) {
		$where = "1=1 ";
		
		if (count ( $filtro )) {
			foreach ( $filtro as $indice => $valor ) {
				$cast = ( int ) $valor;
				
				switch ($valor) {
					case "IS NULL" :
						$where .= "AND s.{$indice} {$valor} ";
						break;
					case "IS NOT NULL" :
						$where .= "AND s.{$indice} {$valor} ";
						break;
					default :
						if ($cast == 0)
							$where .= "AND s.{$indice} LIKE  '%{$valor}%'";
						if ($cast > 0)
							$where .= "AND LIKE s.{$indice} = '{$valor}'";
						break;
				}
			}
		}
		
		$select = $this->createQueryBuilder ( "s" );
		$select->where ( $where );
		
		if (count ( $order ))
			$select->orderBy ( key ( $order ), current ( $order ) );
		
		return $select->getQuery ()->getResult ();
	}
	
	public function findPairs(array $filtro = array()) {
		$where = "1=1 ";
		
		if (count ( $filtro )) {
			foreach ( $filtro as $indice => $valor ) {
				$cast = ( int ) $valor;
				
				switch ($valor) {
					case "IS NULL" :
						$where .= "AND s.{$indice} {$valor} ";
						break;
					case "IS NOT NULL" :
						$where .= "AND s.{$indice} {$valor} ";
						break;
					default :
						if ($cast == 0)
							$where .= "AND s.{$indice} LIKE  '%{$valor}%'";
						if ($cast > 0)
							$where .= "AND LIKE s.{$indice} = '{$valor}'";
						break;
				}
			}
		}
		
		$select = $this->createQueryBuilder ( "s" );
		$select->where ( $where );
		$result = $select->getQuery ()->getResult ();
		
		$arrayResult = array ();
		if (count ( $result )) {
			foreach ( $result as $item ) {
				$arrayResult [$item->getId ()] = $item->getNome ();
			}
		}
		
		return $arrayResult;
	}
	
	public function findToArray($id)
	{
		return $this->find($id)->toArray();
	}
}
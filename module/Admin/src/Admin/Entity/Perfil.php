<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Perfil
 *
 * @ORM\Table(name="perfil", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity(repositoryClass="Admin\Repository\Perfil")
 */
class Perfil
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=60, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="descricao", type="string", length=255, nullable=false)
     */
    private $descricao;

    /**
     * @var integer
     *
     * @ORM\Column(name="perfil_id", type="integer", nullable=true)
     */
    private $perfilId;
    
    public function __construct(array $data) {
    	$hydrator = new ClassMethods();
    	$hydrator->hydrate ( $data, $this );
    }
    
    public function toArray() {
    	$hydrator = new ClassMethods ();
    	return $hydrator->extract ( $this );
    }
    
	public function getId() {
		return $this->id;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getNome() {
		return $this->nome;
	}
	
	public function setNome($nome) {
		$this->nome = $nome;
	}
	
	public function getDescricao() {
		return $this->descricao;
	}
	
	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	
	public function getPerfilId() {
		return $this->perfilId;
	}
	
	public function setPerfilId($perfilId) {
		$this->perfilId = $perfilId;
	}
	
}


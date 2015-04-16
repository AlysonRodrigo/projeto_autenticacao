<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Endereco
 *
 * @ORM\Table(name="endereco", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_endereco_usuario1", columns={"usuario_id"})})
 * @ORM\Entity(repositoryClass="Admin\Repository\Endereco")
 */
class Endereco
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
     * @ORM\Column(name="pais", type="string", length=45, nullable=true)
     */
    private $pais = 'Brasil';

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2, nullable=false)
     */
    private $uf;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade", type="string", length=60, nullable=false)
     */
    private $cidade;

    /**
     * @var string
     *
     * @ORM\Column(name="bairro", type="string", length=60, nullable=false)
     */
    private $bairro;

    /**
     * @var string
     *
     * @ORM\Column(name="rua", type="string", length=60, nullable=false)
     */
    private $rua;

    /**
     * @var string
     *
     * @ORM\Column(name="complemento", type="string", length=100, nullable=true)
     */
    private $complemento;

    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="Usuario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     * })
     */
    private $usuario;
    
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
	
	public function getPais() {
		return $this->pais;
	}
	
	public function setPais($pais) {
		$this->pais = $pais;
	}
	
	public function getUf() {
		return $this->uf;
	}
	
	public function setUf($uf) {
		$this->uf = $uf;
	}
	
	public function getCidade() {
		return $this->cidade;
	}
	
	public function setCidade($cidade) {
		$this->cidade = $cidade;
	}
	
	public function getBairro() {
		return $this->bairro;
	}
	
	public function setBairro($bairro) {
		$this->bairro = $bairro;
	}
	
	public function getRua() {
		return $this->rua;
	}
	
	public function setRua($rua) {
		$this->rua = $rua;
	}
	
	public function getComplemento() {
		return $this->complemento;
	}
	
	public function setComplemento($complemento) {
		$this->complemento = $complemento;
	}
	
	public function getNumero() {
		return $this->numero;
	}
	
	public function setNumero($numero) {
		$this->numero = $numero;
	}
	
	public function getUsuario() {
		return $this->usuario;
	}
	
	public function setUsuario(\Usuario $usuario) {
		$this->usuario = $usuario;
	}
	
}


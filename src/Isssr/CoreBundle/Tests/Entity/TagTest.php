<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Symfony\Component\Serializer\Exception\Exception;

use Isssr\CoreBundle\Entity\Tag;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagTest extends WebTestCase
{

	private $em;	
	private $TAGTITLE = "TAG_TITLE_TEST";
	
	private $tag = null;
	
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		$this->em = static::$kernel->getContainer()
		->get('doctrine')
		->getEntityManager()
		;		
	}
	
	public function inizialize()
	{
		$this->tag = new Tag();
		$this->tag->setTitle($this->TAGTITLE);
	}
	
	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->TAGTITLE, $this->tag->getTitle());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->tag);
		$this->em->flush();
		$tmptag = $this->em->getRepository('IsssrCoreBundle:Tag')->find($this->tag->getId());
		$this->assertEquals($tmptag, $this->tag);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->tag->getId();
		$this->em->remove($this->tag);
		$this->em->flush();
		$tmptag = $this->em->getRepository('IsssrCoreBundle:Tag')->find($id);
		$this->assertNull($tmptag);
	}
	
	public function clean()
	{
		
	}
	
    public function testTag()
    {
    	echo "\n";
    	
    	echo "Tag Test"."\n";
    	
    	$this->inizialize();

    	$this->assertGetSet();
    	$this->assertDbInsert();
    	$this->assertDbRemove();
    	
    	$this->clean();
    }
    
    protected function tearDown()
    {
    	parent::tearDown();
    	$this->em->close();
    }
}
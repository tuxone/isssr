<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Symfony\Component\Serializer\Exception\Exception;

use Isssr\CoreBundle\Entity\Tag;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagTest extends WebTestCase
{

	private $em;	
	private $TAGTITLE = "TAG_TITLE_TEST";
	
	public function setUp()
	{
		static::$kernel = static::createKernel();
		static::$kernel->boot();
		$this->em = static::$kernel->getContainer()
		->get('doctrine')
		->getEntityManager()
		;		
	}
	
    public function testTag()
    {
    	$tag = new Tag();
		$tag->setTitle($this->TAGTITLE);
		
		// Test su metodi costruttore get & set
        $this->assertEquals($this->TAGTITLE, $tag->getTitle());
        
        // Test add
        $this->em->persist($tag);
        $this->em->flush();
        $tmptag = $this->em->getRepository('IsssrCoreBundle:Tag')->find($tag->getId());
        $this->assertEquals($tmptag, $tag);
        
		// TODO test sull'unicitˆ
        
        // Test remove
        $id = $tag->getId();
        $this->em->remove($tag);
        $this->em->flush();
        $tmptag = $this->em->getRepository('IsssrCoreBundle:Tag')->find($id);
        $this->assertNull($tmptag);
    }
    
    protected function tearDown()
    {
    	parent::tearDown();
    	$this->em->close();
    }
}
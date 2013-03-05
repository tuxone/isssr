<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\EnactorInGoal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnactorInGoalTest extends WebTestCase
{

	private $em;	
	
	private $user = null;
	private $goal = null;
	
	private $enactorInGoal = null;
	
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
		$this->goal = new Goal();
		$this->goal->setTitle('GOALTITLE');
		$this->goal->setDescription('GOALDESC');
		
		$this->user = new User();
		$this->user->setUsername('USER_NAME');
		$this->user->setPlainPassword('USER_PASSWORD');
		$this->user->setEmail('USER_EMAIL');
		$this->user->setFirstname('USER_FIRSTNAME');
		$this->user->setLastname('USER_LASTNAME');
		
		
		$this->enactorInGoal = new EnactorInGoal();
		$this->enactorInGoal->setEnactor($this->user);
		$this->enactorInGoal->setGoal($this->goal);
		$this->enactorInGoal->setStatus(0);
		
		
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->goal, $this->enactorInGoal->getGoal());
		$this->assertEquals($this->user, $this->enactorInGoal->getEnactor());
		$this->assertEquals(0, $this->enactorInGoal->getStatus());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->user);
		$this->em->persist($this->goal);
		$this->em->persist($this->enactorInGoal);
		$this->em->flush();
		$tmpeig = $this->em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($this->enactorInGoal->getId());
		$this->assertEquals($tmpeig, $this->enactorInGoal);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->enactorInGoal->getId();
		$this->em->remove($this->enactorInGoal);
		$this->em->flush();
		$tmpeig = $this->em->getRepository('IsssrCoreBundle:EnactorInGoal')->find($id);
		$this->assertNull($tmpeig);
	}
	
	public function clean()
	{
		$this->em->remove($this->goal);
		$this->em->remove($this->user);
		$this->em->flush();
	}
	
    public function testEnactorInGoal()
    {
    	echo "\n";
    	
    	echo "EnactorInGoal Test"."\n";
    	
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
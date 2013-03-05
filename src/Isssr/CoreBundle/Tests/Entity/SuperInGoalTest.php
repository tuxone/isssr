<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\SuperInGoal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnactorInGoalTest extends WebTestCase
{

	private $em;	
	
	private $user = null;
	private $goal = null;
	
	private $superInGoal = null;
	
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
		
		
		$this->superInGoal = new SuperInGoal();
		$this->superInGoal->setSuper($this->user);
		$this->superInGoal->setGoal($this->goal);
		$this->superInGoal->setStatus(0);
		
		
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->goal, $this->superInGoal->getGoal());
		$this->assertEquals($this->user, $this->superInGoal->getSuper());
		$this->assertEquals(0, $this->superInGoal->getStatus());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->user);
		$this->em->persist($this->goal);
		$this->em->persist($this->superInGoal);
		$this->em->flush();
		$tmpsig = $this->em->getRepository('IsssrCoreBundle:SuperInGoal')->find($this->superInGoal->getId());
		$this->assertEquals($tmpsig, $this->superInGoal);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->superInGoal->getId();
		$this->em->remove($this->superInGoal);
		$this->em->flush();
		$tmpsig = $this->em->getRepository('IsssrCoreBundle:SuperInGoal')->find($id);
		$this->assertNull($tmpsig);
	}
	
	public function clean()
	{
		$this->em->remove($this->goal);
		$this->em->remove($this->user);
		$this->em->flush();
	}
	
    public function testSuperInGoal()
    {
    	echo "\n";
    	
    	echo "SuperInGoal Test"."\n";
    	
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
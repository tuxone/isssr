<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\MMDMInGoal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MMDMInGoalTest extends WebTestCase
{

	private $em;	
	
	private $user = null;
	private $goal = null;
	
	private $mmdmInGoal = null;
	
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
		
		
		$this->mmdmInGoal = new MMDMInGoal();
		$this->mmdmInGoal->setMmdm($this->user);
		$this->mmdmInGoal->setGoal($this->goal);
		
		
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->goal, $this->mmdmInGoal->getGoal());
		$this->assertEquals($this->user, $this->mmdmInGoal->getMmdm());
		
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->user);
		$this->em->persist($this->goal);
		$this->em->persist($this->mmdmInGoal);
		$this->em->flush();
		$tmpmmdm = $this->em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($this->mmdmInGoal->getId());
		$this->assertEquals($tmpmmdm, $this->mmdmInGoal);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->mmdmInGoal->getId();
		$this->em->remove($this->mmdmInGoal);
		$this->em->flush();
		$tmpmmdm = $this->em->getRepository('IsssrCoreBundle:MMDMInGoal')->find($id);
		$this->assertNull($tmpmmdm);
	}
	
	public function clean()
	{
		$this->em->remove($this->goal);
		$this->em->remove($this->user);
		$this->em->flush();
	}
	
    public function testMMDMInGoal()
    {
    	echo "\n";
    	
    	echo "MMDMInGoal Test"."\n";
    	
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
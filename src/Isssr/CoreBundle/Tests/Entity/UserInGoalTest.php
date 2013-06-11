<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\UserInGoal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserInGoalTest extends WebTestCase
{

	private $em;	
	
	private $user = null;
	private $goal = null;
    private $role = UserInGoal::ROLE_OWNER;
	
	private $userInGoal = null;
	
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
		
		
		$this->userInGoal = new UserInGoal();
		$this->userInGoal->setUser($this->user);
		$this->userInGoal->setGoal($this->goal);
        $this->userInGoal->setRole($this->role);
		$this->userInGoal->setStatus(0);

	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->goal, $this->userInGoal->getGoal());
		$this->assertEquals($this->user, $this->userInGoal->getUser());
        $this->assertEquals($this->role, $this->userInGoal->getRole());
        $this->assertEquals(0, $this->userInGoal->getStatus());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->user);
		$this->em->persist($this->goal);
		$this->em->persist($this->userInGoal);
		$this->em->flush();
		$tmpeig = $this->em->getRepository('IsssrCoreBundle:UserInGoal')->find($this->userInGoal->getId());
		$this->assertEquals($tmpeig, $this->userInGoal);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->userInGoal->getId();
		$this->em->remove($this->userInGoal);
		$this->em->flush();
		$tmpeig = $this->em->getRepository('IsssrCoreBundle:UserInGoal')->find($id);
		$this->assertNull($tmpeig);
	}
	
	public function clean()
	{
		$this->em->remove($this->goal);
		$this->em->remove($this->user);
		$this->em->flush();
	}
	
    public function testUserInGoal()
    {
    	echo "\n";
    	
    	echo "UserInGoal Test"."\n";
    	
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
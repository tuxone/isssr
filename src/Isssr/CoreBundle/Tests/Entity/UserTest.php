<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Goal;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

	private $em;	
	private $USER_USERNAME = "USER_USERNAME_TEST";
	private $USER_EMAIL = 'USER_EMAIL_TEST';
	private $USER_PASSWORD = 'USER_PASSWORD_TEST';
	private $USER_FIRSTNAME = 'USER_FIRSTNAME_TEST';
	private $USER_LASTNAME = 'USER_LASTNAME_TEST';
	private $USER_GOALSASOWNER = null;
	private $USER_GOALSASENACTOR = null;
	
	private $user = null;
	
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
		$this->USER_GOALSASOWNER = new Goal();
		$this->USER_GOALSASOWNER->setTitle('USER_GOALTITLE_OWNER');
		$this->USER_GOALSASOWNER->setDescription('USER_GOALDESCRIPTION_OWNER');
		 
		$this->USER_GOALSASENACTOR = new Goal();
		$this->USER_GOALSASENACTOR->setTitle('USER_GOALTITLE_ENACTOR');
		$this->USER_GOALSASENACTOR->setDescription('USER_GOALDESCRIPTION_ENACTOR');
		 
		$this->user = new User();
		$this->user->setUsername($this->USER_USERNAME);
		$this->user->setPlainPassword($this->USER_PASSWORD);
		$this->user->setEmail($this->USER_EMAIL);
		$this->user->setFirstname($this->USER_FIRSTNAME);
		$this->user->setLastname($this->USER_LASTNAME);
		$this->user->addGoalsAsOwner($this->USER_GOALSASOWNER);
		$this->user->addGoalsAsEnactor($this->USER_GOALSASENACTOR);
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->USER_USERNAME, $this->user->getUsername());
		$this->assertEquals($this->USER_PASSWORD, $this->user->getPlainPassword());
		$this->assertEquals($this->USER_EMAIL, $this->user->getEmail());
		$this->assertEquals($this->USER_FIRSTNAME, $this->user->getFirstname());
		$this->assertEquals($this->USER_LASTNAME, $this->user->getLastname());
		$this->assertEquals($this->USER_GOALSASOWNER, $this->user->getGoalsAsOwner()->first());
		$this->assertEquals($this->USER_GOALSASENACTOR, $this->user->getGoalsAsEnactor()->first());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->USER_GOALSASOWNER);
		$this->em->persist($this->USER_GOALSASENACTOR);
		$this->em->persist($this->user);
		$this->em->flush();
		$tmpuser = $this->em->getRepository('IsssrCoreBundle:User')->find($this->user->getId());
		$this->assertEquals($tmpuser, $this->user);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->user->getId();
		$this->em->remove($this->user);
		$this->em->flush();
		$tmpuser = $this->em->getRepository('IsssrCoreBundle:User')->find($id);
		$this->assertNull($tmpuser);
	}
	
	public function clean()
	{
		$this->em->remove($this->USER_GOALSASOWNER);
		$this->em->remove($this->USER_GOALSASENACTOR);
		$this->em->flush();
	}
	
    public function testUser()
    {
    	echo "\n";
    	
    	echo "User Test"."\n";
    	
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
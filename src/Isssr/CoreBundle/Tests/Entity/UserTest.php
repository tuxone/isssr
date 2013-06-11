<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Isssr\CoreBundle\Entity\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{

	private $em;	
	private $USER_USERNAME = "USER_USERNAME_TEST";
	private $USER_EMAIL = 'USER_EMAIL_TEST';
	private $USER_PASSWORD = 'USER_PASSWORD_TEST';
	private $USER_FIRSTNAME = 'USER_FIRSTNAME_TEST';
	private $USER_LASTNAME = 'USER_LASTNAME_TEST';

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
		$this->user = new User();
		$this->user->setUsername($this->USER_USERNAME);
		$this->user->setPlainPassword($this->USER_PASSWORD);
		$this->user->setEmail($this->USER_EMAIL);
		$this->user->setFirstname($this->USER_FIRSTNAME);
		$this->user->setLastname($this->USER_LASTNAME);
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->USER_USERNAME, $this->user->getUsername());
		$this->assertEquals($this->USER_PASSWORD, $this->user->getPlainPassword());
		$this->assertEquals($this->USER_EMAIL, $this->user->getEmail());
		$this->assertEquals($this->USER_FIRSTNAME, $this->user->getFirstname());
		$this->assertEquals($this->USER_LASTNAME, $this->user->getLastname());
	}
	
	public function assertDbInsert()
	{
		// Test add
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
	
    public function testUser()
    {
    	echo "\n";
    	
    	echo "User Test"."\n";
    	
    	$this->inizialize();
    	
    	$this->assertGetSet();
    	$this->assertDbInsert();
    	$this->assertDbRemove();

    }
    
    protected function tearDown()
    {
    	parent::tearDown();
    }
}
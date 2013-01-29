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
    	
    	$this->USER_GOALSASOWNER = new Goal();
    	$this->USER_GOALSASOWNER->setTitle('USER_GOALTITLE_OWNER');
    	$this->USER_GOALSASOWNER->setDescription('USER_GOALDESCRIPTION_OWNER');
    	
    	$this->USER_GOALSASENACTOR = new Goal();
    	$this->USER_GOALSASENACTOR->setTitle('USER_GOALTITLE_ENACTOR');
    	$this->USER_GOALSASENACTOR->setDescription('USER_GOALDESCRIPTION_ENACTOR');
    	
    	$user = new User();
		$user->setUsername($this->USER_USERNAME);
		$user->setPlainPassword($this->USER_PASSWORD);
		$user->setEmail($this->USER_EMAIL);
		$user->setFirstname($this->USER_FIRSTNAME);
		$user->setLastname($this->USER_LASTNAME);
		$user->addGoalsAsOwner($this->USER_GOALSASOWNER);
		$user->addGoalsAsEnactor($this->USER_GOALSASENACTOR);
		
		// Test su metodi costruttore get & set
        $this->assertEquals($this->USER_USERNAME, $user->getUsername());
        $this->assertEquals($this->USER_PASSWORD, $user->getPlainPassword());
        $this->assertEquals($this->USER_EMAIL, $user->getEmail());
        $this->assertEquals($this->USER_FIRSTNAME, $user->getFirstname());
        $this->assertEquals($this->USER_LASTNAME, $user->getLastname());
        $this->assertEquals($this->USER_GOALSASOWNER, $user->getGoalsAsOwner()->first());
        $this->assertEquals($this->USER_GOALSASENACTOR, $user->getGoalsAsEnactor()->first());
        
        // Test add
        $this->em->persist($this->USER_GOALSASOWNER);
        $this->em->persist($this->USER_GOALSASENACTOR);
        $this->em->persist($user);
        $this->em->flush();
        $tmpuser = $this->em->getRepository('IsssrCoreBundle:User')->find($user->getId());
        $this->assertEquals($tmpuser, $user);
        
        // TODO test sull'unicitˆ
       
        // Test remove
        $id = $user->getId();
        $this->em->remove($user);
        $this->em->remove($this->USER_GOALSASOWNER);
        $this->em->remove($this->USER_GOALSASENACTOR);
        $this->em->flush();
        $tmpuser = $this->em->getRepository('IsssrCoreBundle:User')->find($id);
        $this->assertNull($tmpuser);
    }
    
    protected function tearDown()
    {
    	parent::tearDown();
    	$this->em->close();
    }
}
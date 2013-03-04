<?php
namespace Isssr\CoreBundle\Tests\Utility;

use Symfony\Component\Serializer\Exception\Exception;

use Isssr\CoreBundle\Entity\Goal;
use Isssr\CoreBundle\Entity\User;
use Isssr\CoreBundle\Entity\Tag;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GoalTest extends WebTestCase
{

	private $em;
	private $GOALTITLE = "GOAL_TITLE_TEST";
	private $GOALDESCRIPTION = "GOAL_DESCRIPTION_TEST";
	private $GOALPRIORITY = 100;
	private $GOALOWNER = null;
	private $GOALENACTOR = null;
	private $GOALTAG = null;
	private $GOALFOCUS = "GOAL_FOCUS_TEST";
	private $GOALOBJECT = "GOAL_OBJECT_TEST";
	private $GOALMAGNITUDE = "GOAL_MAGNITUDE_TEST";
	private $GOALTIMEFRAME = "GOAL_TIMEFRAME_TEST";
	private $GOALORGANIZATIONALSCOPE = "GOAL_ORGANIZATIONALSCOPE_TEST";
	private $GOALCONSTRAINTS = "GOAL_CONSTRAINTS_TEST";
	private $GOALRELATIONS = "GOAL_RELATIONS_TEST";
	private $GOALCONTEST = "GOAL_CONTEST_TEST";
	private $GOALASSUMPTIONS = "GOAL_ASSUMPTIONS_TEST";
	
	private $goal = null;

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
		$this->GOALOWNER = new User();
		$this->GOALOWNER->setUsername('OWNER_USERNAME');
		$this->GOALOWNER->setPlainPassword('OWNER_PASSWORD');
		$this->GOALOWNER->setEmail('OWNER_EMAIL');
		$this->GOALOWNER->setFirstname('OWNER_FIRSTNAME');
		$this->GOALOWNER->setLastname('OWNER_LASTNAME');
		
// 		$this->GOALENACTOR = new EnactorInGoal();
// 		$this->GOALENACTOR->setUsername('ENACTOR_USERNAME');
// 		$this->GOALENACTOR->setPlainPassword('ENACTOR_PASSWORD');
// 		$this->GOALENACTOR->setEmail('ENACTOR_EMAIL');
// 		$this->GOALENACTOR->setFirstname('ENACTOR_FIRSTNAME');
// 		$this->GOALENACTOR->setLastname('ENACTOR_LASTNAME');
		
		$this->GOALTAG = new Tag();
		$this->GOALTAG->setTitle('TAG_TITLE');
		$this->GOALTAG->setDescription('TAG_DESC');
		
		$this->goal = new Goal();
		$this->goal->setTitle($this->GOALTITLE);
		$this->goal->setDescription($this->GOALDESCRIPTION);
		$this->goal->setPriority($this->GOALPRIORITY);
		$this->goal->setOwner($this->GOALOWNER);
// 		$this->goal->setEnactor($this->GOALENACTOR);
		$this->goal->addTag($this->GOALTAG);
		$this->goal->setFocus($this->GOALFOCUS);
		$this->goal->setObject($this->GOALOBJECT);
		$this->goal->setMagnitude($this->GOALMAGNITUDE);
		$this->goal->setTimeframe($this->GOALTIMEFRAME);
		$this->goal->setOrganizationalScope($this->GOALORGANIZATIONALSCOPE);
		$this->goal->setConstraints($this->GOALCONSTRAINTS);
		$this->goal->setRelations($this->GOALRELATIONS);
		$this->goal->setContest($this->GOALCONTEST);
		$this->goal->setAssumptions($this->GOALASSUMPTIONS);
	}

	public function assertGetSet()
	{
		// Test su metodi costruttore get & set
		$this->assertEquals($this->GOALTITLE, $this->goal->getTitle());
		$this->assertEquals($this->GOALDESCRIPTION, $this->goal->getDescription());
		$this->assertEquals($this->GOALPRIORITY, $this->goal->getPriority());
		$this->assertEquals($this->GOALOWNER, $this->goal->getOwner());
// 		$this->assertEquals($this->GOALENACTOR, $this->goal->getEnactor());
		$this->assertEquals($this->GOALTAG, $this->goal->getTags()->first());
		$this->assertEquals($this->GOALFOCUS, $this->goal->getFocus());
		$this->assertEquals($this->GOALOBJECT, $this->goal->getObject());
		$this->assertEquals($this->GOALMAGNITUDE, $this->goal->getMagnitude());
		$this->assertEquals($this->GOALTIMEFRAME, $this->goal->getTimeframe());
		$this->assertEquals($this->GOALORGANIZATIONALSCOPE, $this->goal->getOrganizationalScope());
		$this->assertEquals($this->GOALCONSTRAINTS, $this->goal->getConstraints());
		$this->assertEquals($this->GOALRELATIONS, $this->goal->getRelations());
		$this->assertEquals($this->GOALCONTEST, $this->goal->getContest());
		$this->assertEquals($this->GOALASSUMPTIONS, $this->goal->getAssumptions());
	}
	
	public function assertDbInsert()
	{
		// Test add
		$this->em->persist($this->GOALOWNER);
// 		$this->em->persist($this->GOALENACTOR);
		$this->em->persist($this->GOALTAG);
		$this->em->persist($this->goal);
		$this->em->flush();
		$tmpgoal = $this->em->getRepository('IsssrCoreBundle:Goal')->find($this->goal->getId());
		$this->assertEquals($tmpgoal, $this->goal);
	}
	
	public function assertDbRemove()
	{
		// Test remove
		$id = $this->goal->getId();
		$this->em->remove($this->goal);
		$this->em->flush();
		$tmpgoal = $this->em->getRepository('IsssrCoreBundle:Goal')->find($id);
		$this->assertNull($tmpgoal);
	}

	public function clean()
	{
		// Removing Owner, Enactor and Tag
		
		$this->em->remove($this->GOALOWNER);
// 		$this->em->remove($this->GOALENACTOR);
		$this->em->remove($this->GOALTAG);
		$this->em->flush();
		
	}
	
	public function testGoal()
	{
		
		echo "\n";

		echo "Goal Test"."\n";
		
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
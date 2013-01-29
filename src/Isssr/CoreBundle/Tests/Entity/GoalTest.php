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
		$this->GOALOWNER = new User();
		$this->GOALOWNER->setUsername('OWNER_USERNAME');
		$this->GOALOWNER->setPlainPassword('OWNER_PASSWORD');
		$this->GOALOWNER->setEmail('OWNER_EMAIL');
		$this->GOALOWNER->setFirstname('OWNER_FIRSTNAME');
		$this->GOALOWNER->setLastname('OWNER_LASTNAME');
		
		$this->GOALENACTOR = new User();
		$this->GOALENACTOR->setUsername('ENACTOR_USERNAME');
		$this->GOALENACTOR->setPlainPassword('ENACTOR_PASSWORD');
		$this->GOALENACTOR->setEmail('ENACTOR_EMAIL');
		$this->GOALENACTOR->setFirstname('ENACTOR_FIRSTNAME');
		$this->GOALENACTOR->setLastname('ENACTOR_LASTNAME');
		
		$this->GOALTAG = new Tag();
		$this->GOALTAG->setTitle('TAG_TITLE');
		
		
		$goal = new Goal();
		$goal->setTitle($this->GOALTITLE);
		$goal->setDescription($this->GOALDESCRIPTION);
		$goal->setPriority($this->GOALPRIORITY);
		$goal->setOwner($this->GOALOWNER);
		$goal->setEnactor($this->GOALENACTOR);
		$goal->addTag($this->GOALTAG);
		$goal->setFocus($this->GOALFOCUS);
		$goal->setObject($this->GOALOBJECT);
		$goal->setMagnitude($this->GOALMAGNITUDE);
		$goal->setTimeframe($this->GOALTIMEFRAME);
		$goal->setOrganizationalScope($this->GOALORGANIZATIONALSCOPE);
		$goal->setConstraints($this->GOALCONSTRAINTS);
		$goal->setRelations($this->GOALRELATIONS);
		$goal->setContest($this->GOALCONTEST);
		$goal->setAssumptions($this->GOALASSUMPTIONS);
		
		// Test su metodi costruttore get & set
		$this->assertEquals($this->GOALTITLE, $goal->getTitle());
		$this->assertEquals($this->GOALDESCRIPTION, $goal->getDescription());
		$this->assertEquals($this->GOALPRIORITY, $goal->getPriority());
		$this->assertEquals($this->GOALOWNER, $goal->getOwner());
		$this->assertEquals($this->GOALENACTOR, $goal->getEnactor());
		$this->assertEquals($this->GOALTAG, $goal->getTags()->first());
		$this->assertEquals($this->GOALFOCUS, $goal->getFocus());
		$this->assertEquals($this->GOALOBJECT, $goal->getObject());
		$this->assertEquals($this->GOALMAGNITUDE, $goal->getMagnitude());
		$this->assertEquals($this->GOALTIMEFRAME, $goal->getTimeframe());
		$this->assertEquals($this->GOALORGANIZATIONALSCOPE, $goal->getOrganizationalScope());
		$this->assertEquals($this->GOALCONSTRAINTS, $goal->getConstraints());
		$this->assertEquals($this->GOALRELATIONS, $goal->getRelations());
		$this->assertEquals($this->GOALCONTEST, $goal->getContest());
		$this->assertEquals($this->GOALASSUMPTIONS, $goal->getAssumptions());

		// Test add
		$this->em->persist($this->GOALOWNER);
		$this->em->persist($this->GOALENACTOR);
		$this->em->persist($this->GOALTAG);
		$this->em->persist($goal);
		$this->em->flush();
		$tmpgoal = $this->em->getRepository('IsssrCoreBundle:Goal')->find($goal->getId());
		$this->assertEquals($tmpgoal, $goal);

		// TODO test sull'unicitˆ


		// Test remove
		$id = $goal->getId();
		$this->em->remove($goal);
		$this->em->flush();
		$tmpgoal = $this->em->getRepository('IsssrCoreBundle:Tag')->find($id);
		$this->assertNull($tmpgoal);
		
		// Removing Owner, Enactor and Tag
		
		$this->em->remove($this->GOALOWNER);
		$this->em->remove($this->GOALENACTOR);
		$this->em->remove($this->GOALTAG);
		$this->em->flush();
		
	}

	protected function tearDown()
	{
		parent::tearDown();
		$this->em->close();
	}
}
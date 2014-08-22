<?php

namespace OroCRM\Bundle\SalesBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\ClassUtils;
use OroCRM\Bundle\SalesBundle\Entity\B2bCustomer;

class B2bCustomerTest extends \PHPUnit_Framework_TestCase
{
    const TEST_ID   = 12;
    const TEST_NAME = 'test name';

    /** @var B2bCustomer */
    protected $entity;

    protected function setUp()
    {
        $this->entity = new B2bCustomer();
    }

    public function tearDown()
    {
        unset($this->entity);
    }

    /**
     * @dataProvider getSetDataProvider
     */
    public function testGetSet($property, $value, $expected = null)
    {
        if (null !== $value) {
            call_user_func_array([$this->entity, 'set' . ucfirst($property)], [$value]);
        }
        $this->assertSame($expected, call_user_func([$this->entity, 'get' . ucfirst($property)]));
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $name    = uniqid('name');
        $address = $this->getMock('Oro\Bundle\AddressBundle\Entity\Address');
        $account = $this->getMock('OroCRM\Bundle\AccountBundle\Entity\Account');
        $contact = $this->getMock('OroCRM\Bundle\ContactBundle\Entity\Contact');
        $channel = $this->getMock('OroCRM\Bundle\ChannelBundle\Entity\Channel');
        $owner   = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $date    = new \DateTime();

        return [
            'id'              => ['id', null, null],
            'name'            => ['name', $name, $name],
            'shippingAddress' => ['shippingAddress', $address, $address],
            'billingAddress'  => ['billingAddress', $address, $address],
            'account'         => ['account', $account, $account],
            'contact'         => ['contact', $contact, $contact],
            'channel'         => ['channel', $channel, $channel],
            'owner'           => ['owner', $owner, $owner],
            'createdAt'       => ['createdAt', $date, $date],
            'updatedAt'       => ['updatedAt', $date, $date],
        ];
    }

    public function testPrePersist()
    {
        $this->assertNull($this->entity->getCreatedAt());

        $this->entity->prePersist();

        $this->assertInstanceOf('DateTime', $this->entity->getCreatedAt());
        $this->assertLessThan(3, $this->entity->getCreatedAt()->diff(new \DateTime())->s);
    }

    public function testPreUpdate()
    {
        $this->assertNull($this->entity->getUpdatedAt());

        $this->entity->preUpdate();

        $this->assertInstanceOf('DateTime', $this->entity->getUpdatedAt());
        $this->assertLessThan(3, $this->entity->getUpdatedAt()->diff(new \DateTime())->s);
    }

    public function testLeadsInteraction()
    {
        $result = $this->entity->getLeads();
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $result);
        $this->assertCount(0, $result);

        $lead = $this->getMock('OroCRM\Bundle\SalesBundle\Entity\Lead');
        $this->entity->addLead($lead);
        $this->assertCount(1, $this->entity->getLeads());
        $this->assertTrue($this->entity->getLeads()->contains($lead));

        $this->entity->removeLead($lead);
        $result = $this->entity->getLeads();
        $this->assertCount(0, $result);

        $newCollection = new ArrayCollection();
        $this->entity->setLeads($newCollection);
        $this->assertNotSame($result, $this->entity->getLeads());
        $this->assertSame($newCollection, $this->entity->getLeads());
    }

    public function testOpportunitiesInteraction()
    {
        $result = $this->entity->getOpportunities();
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $result);
        $this->assertCount(0, $result);

        $opportunity = $this->getMock('OroCRM\Bundle\SalesBundle\Entity\Opportunity');
        $this->entity->addOpportunity($opportunity);
        $this->assertCount(1, $this->entity->getOpportunities());
        $this->assertTrue($this->entity->getOpportunities()->contains($opportunity));

        $this->entity->removeOpportunity($opportunity);
        $result = $this->entity->getLeads();
        $this->assertCount(0, $result);

        $newCollection = new ArrayCollection();
        $this->entity->setOpportunities($newCollection);
        $this->assertNotSame($result, $this->entity->getOpportunities());
        $this->assertSame($newCollection, $this->entity->getOpportunities());
    }

    public function testTaggableInterface()
    {
        $this->assertInstanceOf('Oro\Bundle\TagBundle\Entity\Taggable', $this->entity);
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->entity->getTags());

        $this->assertNull($this->entity->getTaggableId());

        $ref = new \ReflectionProperty(ClassUtils::getClass($this->entity), 'id');
        $ref->setAccessible(true);
        $ref->setValue($this->entity, self::TEST_ID);

        $this->assertSame(self::TEST_ID, $this->entity->getTaggableId());

        $newCollection = new ArrayCollection();
        $this->entity->setTags($newCollection);
        $this->assertSame($newCollection, $this->entity->getTags());
    }

    public function testToSting()
    {
        $this->entity->setName(self::TEST_NAME);
        $this->assertSame(self::TEST_NAME, (string)$this->entity);
    }
}

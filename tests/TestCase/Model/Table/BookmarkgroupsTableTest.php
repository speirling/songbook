<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookmarkgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookmarkgroupsTable Test Case
 */
class BookmarkgroupsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bookmarkgroups',
        'app.bookmarkurls',
        'app.bookmarkurls_bookmarkgroups'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Bookmarkgroups') ? [] : ['className' => 'App\Model\Table\BookmarkgroupsTable'];
        $this->Bookmarkgroups = TableRegistry::get('Bookmarkgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bookmarkgroups);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

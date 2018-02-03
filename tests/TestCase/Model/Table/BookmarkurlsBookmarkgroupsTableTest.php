<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookmarkurlsBookmarkgroupsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookmarkurlsBookmarkgroupsTable Test Case
 */
class BookmarkurlsBookmarkgroupsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bookmarkurls_bookmarkgroups',
        'app.bookmarkgroups',
        'app.bookmarkurls'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BookmarkurlsBookmarkgroups') ? [] : ['className' => 'App\Model\Table\BookmarkurlsBookmarkgroupsTable'];
        $this->BookmarkurlsBookmarkgroups = TableRegistry::get('BookmarkurlsBookmarkgroups', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookmarkurlsBookmarkgroups);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

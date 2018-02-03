<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookmarkurlsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookmarkurlsTable Test Case
 */
class BookmarkurlsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bookmarkurls',
        'app.bookmarkgroups',
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
        $config = TableRegistry::exists('Bookmarkurls') ? [] : ['className' => 'App\Model\Table\BookmarkurlsTable'];
        $this->Bookmarkurls = TableRegistry::get('Bookmarkurls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bookmarkurls);

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

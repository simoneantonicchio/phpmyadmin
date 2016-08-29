<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Test for PMA\libraries\Util class
 *
 * @package PhpMyAdmin-test
 */

require_once 'test/PMATestCase.php';

/**
 * Test for PMA\libraries\Util class
 *
 * @package PhpMyAdmin-test
 */
class UtilTest extends PMATestCase
{

    /**
     * Test for createGISData
     *
     * @return void
     */
    public function testCreateGISData()
    {
        $this->assertEquals(
            "abc",
            PMA\libraries\Util::createGISData("abc")
        );
        $this->assertEquals(
            "GeomFromText('POINT()',10)",
            PMA\libraries\Util::createGISData("'POINT()',10")
        );
    }

    /**
     * Test for getGISFunctions
     *
     * @return void
     */
    public function testGetGISFunctions()
    {
        $funcs = PMA\libraries\Util::getGISFunctions();
        $this->assertArrayHasKey(
            'Dimension',
            $funcs
        );
        $this->assertArrayHasKey(
            'GeometryType',
            $funcs
        );
        $this->assertArrayHasKey(
            'MBRDisjoint',
            $funcs
        );
    }

    /**
     * Test for Page Selector
     *
     * @return void
     */
    public function testPageSelector()
    {
        $this->assertContains(
            '<select class="pageselector ajax" name="pma" >',
            PMA\libraries\Util::pageselector("pma", 3)
        );
    }

    /**
     * Test for isForeignKeyCheck
     *
     * @return void
     */
    public function testIsForeignKeyCheck()
    {
        $GLOBALS['server'] = 1;

        $GLOBALS['cfg']['DefaultForeignKeyChecks'] = 'enable';
        $this->assertEquals(
            true,
            PMA\libraries\Util::isForeignKeyCheck()
        );

        $GLOBALS['cfg']['DefaultForeignKeyChecks'] = 'disable';
        $this->assertEquals(
            false,
            PMA\libraries\Util::isForeignKeyCheck()
        );

        $GLOBALS['cfg']['DefaultForeignKeyChecks'] = 'default';
        $this->assertEquals(
            true,
            PMA\libraries\Util::isForeignKeyCheck()
        );
    }

    /**
     * Test for getCharsetQueryPart
     *
     * @param string $collation Collation
     * @param string $expected  Expected Charset Query
     *
     * @return void
     * @test
     * @dataProvider charsetQueryData
     */
    public function testGenerateCharsetQueryPart($collation, $expected)
    {
        $this->assertEquals(
            $expected,
            PMA\libraries\Util::getCharsetQueryPart($collation)
        );
    }

    /**
     * Data Provider for testgetCharsetQueryPart
     *
     * @return array test data
     */
    public function charsetQueryData()
    {
        return array(
            array("a_b_c_d", " CHARSET=a COLLATE a_b_c_d"),
            array("a_", " CHARSET=a COLLATE a_"),
            array("a", " CHARSET=a"),
        );
    }

    /**
     * Test for isForeignKeySupported
     *
     * @return void
     */
    public function testIsForeignKeySupported()
    {
        $GLOBALS['server'] = 1;

        $this->assertTrue(
            PMA\libraries\Util::isForeignKeySupported('innodb')
        );
        $this->assertFalse(
            PMA\libraries\Util::isForeignKeySupported('myisam')
        );
        $this->assertTrue(
            PMA\libraries\Util::isForeignKeySupported('ndb')
        );
    }

    /**
     * Test for http request
     *
     * @group medium
     *
     * @return void
     */
    public function testHttpRequest()
    {
        $this->assertTrue(
            PMA\libraries\Util::httpRequest("https://www.phpmyadmin.net/test/data", "GET", 5, true)
        );
        $this->assertContains(
            "TEST DATA",
            PMA\libraries\Util::httpRequest("https://www.phpmyadmin.net/test/data","GET", 5)
        );
        $this->assertFalse(
            PMA\libraries\Util::httpRequest("https://www.phpmyadmin.net/test/nothing","GET", 5, true)
        );
        // Use rate limit API as it's not subject to rate limiting
        $this->assertContains(
            '"resources"',
            PMA\libraries\Util::httpRequest("https://api.github.com/rate_limit","GET", 5)
        );
    }
}

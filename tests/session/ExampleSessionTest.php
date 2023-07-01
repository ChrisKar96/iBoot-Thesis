<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

/**
 * @internal
 */
final class ExampleSessionTest extends \Tests\Support\SessionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testSessionSimple()
    {
        $this->session->set('logged_in', 123);

        $value = $this->session->get('logged_in');

        $this->assertSame(123, $value);
    }
}

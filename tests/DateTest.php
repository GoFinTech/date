<?php

use GoFinTech\Date\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function test__construct()
    {
        $date = new Date(2019, 12, 23);
        $this->assertEquals(2019, $date->year());
        $this->assertEquals(12, $date->month());
        $this->assertEquals(23, $date->day());
    }

    public function test_validation_1() {
        $this->expectException(\InvalidArgumentException::class);
        new Date(2019, 13, 1);
    }

    public function test_validation_2() {
        $this->expectException(\InvalidArgumentException::class);
        new Date(2019, 0, 1);
    }

    public function test_validation_3() {
        $this->expectException(\InvalidArgumentException::class);
        new Date(2019, 2, 29);
    }

    public function test_validation_4() {
        $date = new Date(2020, 2, 29);
        $this->assertEquals("2020-02-29", $date);
    }

    public function testLastDayOfMonth()
    {
        $this->assertEquals(31, Date::lastDayOfMonth(2019, 1));
        $this->assertEquals(28, Date::lastDayOfMonth(2019, 2));
        $this->assertEquals(30, Date::lastDayOfMonth(2019, 9));
        $this->assertEquals(29, Date::lastDayOfMonth(2020, 2));

        $date = new Date(2019, 3, 2);
        $this->assertEquals(31, $date->getLastDayOfMonth());
        $this->assertEquals("2019-03-31", $date->toLastOfMonth());
        $this->assertEquals("2019-03-01", $date->toFirstOfMonth());
    }

    public function testIsLeapYear()
    {
        $this->assertEquals(true, Date::isLeapYear(1996));
        $this->assertEquals(false, Date::isLeapYear(1997));
        $this->assertEquals(false, Date::isLeapYear(1998));
        $this->assertEquals(false, Date::isLeapYear(1999));
        $this->assertEquals(true, Date::isLeapYear(2000));
        $this->assertEquals(false, Date::isLeapYear(2100));

        $this->assertEquals(false, Date::create(2019)->getLeapYear());
        $this->assertEquals(true, Date::create(2020)->getLeapYear());
    }

    public function test__toString()
    {
        $this->assertEquals("2000-01-02", new Date(2000, 1, 2));
    }

    public function testCreate()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals((new DateTime())->format("Y-m-d"), Date::create());

        $this->assertEquals("2019-01-01", Date::create(2019));
        $this->assertEquals("2019-07-01", Date::create(2019, 7));
        $this->assertEquals("2019-07-04", Date::create(2019, 7, 4));

        $this->assertEquals("2019-12-23", Date::create("2019-12-23"));

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertEquals("2019-11-18", Date::create(new DateTime("2019-11-18T17:23")));

        $date = new Date(2019, 8, 14);
        $this->assertEquals($date, Date::create($date));
    }

    public function testImmutable()
    {
        $date = new Date(2019, 10, 20);
        $date->add(2, 'days');
        $date->add(2, 'months');
        $date->add(2, 'years');
        $this->assertEquals('2019-10-20', $date);
    }

    public function testAdd() {
        $date = new Date(2019, 1, 30);

        $this->assertEquals('2019-01-31', $date->add(1, 'days'));
        $this->assertEquals('2019-02-01', $date->add(2, 'days'));
        $this->assertEquals('2018-12-31', $date->add(-30, 'days'));

        $this->assertEquals('2019-02-28', $date->add(1, 'months'));
        $this->assertEquals('2018-12-30', $date->add(-1, 'months'));
        $this->assertEquals('2018-11-30', $date->add(-2, 'months'));

        $this->assertEquals('2019-12-30', $date->add(11, 'months'));
        $this->assertEquals('2020-01-30', $date->add(12, 'months'));
        $this->assertEquals('2020-02-29', $date->add(13, 'months'));
        $this->assertEquals('2020-12-30', $date->add(23, 'months'));
        $this->assertEquals('2021-01-30', $date->add(24, 'months'));
        $this->assertEquals('2021-02-28', $date->add(25, 'months'));
        $this->assertEquals('2018-01-30', $date->add(-12, 'months'));
        $this->assertEquals('2017-12-30', $date->add(-13, 'months'));
        $this->assertEquals('2017-11-30', $date->add(-14, 'months'));
        $this->assertEquals('2017-02-28', $date->add(-23, 'months'));
        $this->assertEquals('2017-01-30', $date->add(-24, 'months'));
        $this->assertEquals('2016-12-30', $date->add(-25, 'months'));
        $this->assertEquals('2016-11-30', $date->add(-26, 'months'));

        $this->assertEquals('2039-01-30', $date->add(20, 'years'));
        $this->assertEquals('1999-01-30', $date->add(-20, 'years'));

        $this->assertEquals('2019-02-28', Date::create('2020-02-29')->add(-1, 'years'));
    }

    public function testFormat() {
        $date = new Date(2019, 1, 2);

        $this->assertEquals('02.2019/01', $date->format('d.Y/m'));
    }

    public function testJsonSerialize() {
        $date = new Date(2019, 5, 17);
        $this->assertEquals('"2019-05-17"', json_encode($date));
    }

    public function testDiffInDays() {
        $base = new Date(2004, 3, 1);

        $this->assertEquals(0, $base->diffInDays(Date::create('2004-03-01')));
        $this->assertEquals(1, $base->diffInDays(Date::create('2004-03-02')));
        $this->assertEquals(365, $base->diffInDays(Date::create('2005-03-01')));
        $this->assertEquals(-1, $base->diffInDays(Date::create('2004-02-29')));
        $this->assertEquals(-2, $base->diffInDays(Date::create('2004-02-28')));
    }
}

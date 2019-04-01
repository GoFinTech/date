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

        $date = new Date(2019, 3, 1);
        $this->assertEquals(31, $date->getLastDayOfMonth());
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
}
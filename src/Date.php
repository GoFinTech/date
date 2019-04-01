<?php


namespace GoFinTech\Date;


/**
 * An immutable object representing a Gregorian date: year, month and day of month.
 *
 * @package GoFinTech\Date
 */
class Date
{
    public const YEARS = 'years';
    public const MONTHS = 'months';
    public const DAYS = 'days';

    /** @var int */
    private $year;
    /** @var int */
    private $month;
    /** @var int */
    private $day;

    /**
     * @return int year value of this date
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * @return int month value of this date
     */
    public function month(): int
    {
        return $this->month;
    }

    /**
     * @return int day value of this date
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * Constructs a date object.
     *
     * @param int $year 1000..9999
     * @param int $month 1..12
     * @param int $day 1..31
     */
    public function __construct(int $year, int $month, int $day)
    {
        if ($year < 1000 || $year > 9999)
            throw new \InvalidArgumentException("Date: year must be 1000..9999");

        if ($month < 1 || $month > 12)
            throw new \InvalidArgumentException("Date: month must be 1..12");

        if ($day < 1 || $day > 31)
            throw new \InvalidArgumentException("Date: day must be 1..31");

        if ($day > static::lastDayOfMonth($year, $month))
            throw new \InvalidArgumentException("Date: day is not valid for the specified month");

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * Constructs a date object in a variety of ways.
     *
     * create() - captures current system date
     * create(DateTimeInterface) - captures date from object implementing DateTimeInterface
     * create(Date) - returns the same instance as in parameter
     * create(2019, 3, 28) - specific date, month and day default to 1 if omitted
     * create(string) - passes a string to DateTime constructor and extracts date from result
     *
     * @param mixed $year
     * @param int $month
     * @param int $day
     * @return Date
     */
    public static function create($year = null, $month = 1, $day = 1): Date
    {
        if (func_num_args() == 0) {
            try {
                $date = new \DateTime();
            } catch (\Exception $e) {
                // This shouldn't happen
                throw new \RuntimeException("Date::create: DateTime default constructor failed");
            }
            return new Date(
                (int)$date->format('Y'),
                (int)$date->format('m'),
                (int)$date->format('d')
            );
        }

        if (is_int($year)) {
            return new Date($year, $month, $day);
        }

        if (func_num_args() != 1) {
            throw new \InvalidArgumentException("Date::create: year must be int when multiple arguments are passed");
        }

        if (is_string($year)) {
            try {
                $date = new \DateTime($year);
            } catch (\Exception $e) {
                throw new \InvalidArgumentException("Date::create: date string is invalid: {$e->getMessage()}");
            }
            return new Date(
                (int)$date->format('Y'),
                (int)$date->format('m'),
                (int)$date->format('d')
            );
        }

        if (!is_object($year)) {
            throw new \InvalidArgumentException("Date::create: unsupported parameter type " . gettype($year));
        }

        if ($year instanceof \DateTimeInterface) {
            return new Date(
                (int)$year->format('Y'),
                (int)$year->format('m'),
                (int)$year->format('d')
            );
        }

        if ($year instanceof Date) {
            return $year;
        }

        throw new \InvalidArgumentException("Date::create: unsupported parameter class " . get_class($year));
    }

    /**
     * Returns last day of month.
     *
     * @param int $year
     * @param int $month
     * @return int last day of specified month (e.g. 31)
     */
    public static function lastDayOfMonth(int $year, int $month): int
    {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                return 31;
            case 4:
            case 6:
            case 9:
            case 11:
                return 30;
            case 2:
                if (static::isLeapYear($year))
                    return 29;
                else
                    return 28;
            default:
                throw new \InvalidArgumentException("Date::lastDayOfMonth: month must be 1..12");
        }
    }

    /**
     * Returns last day of a month in this date.
     *
     * @return int last day of set month (e.g. 31)
     */
    public function getLastDayOfMonth(): int
    {
        return static::lastDayOfMonth($this->year, $this->month);
    }

    /**
     * Checks if specified year has a leap day.
     *
     * @param int $year
     * @return bool true if year is a leap year
     */
    public static function isLeapYear(int $year): bool
    {
        if ($year % 4 != 0)
            return false;

        if ($year % 100 != 0)
            return true;

        if ($year % 400 != 0)
            return false;

        return true;
    }

    /**
     * Checks if a year set in this date has a leap day.
     *
     * @return bool true if this date is set to a leap year
     */
    public function getLeapYear(): bool
    {
        return static::isLeapYear($this->year);
    }

    /**
     * @return string YYYY-MM-DD
     */
    public function __toString()
    {
        return sprintf('%d-%02d-%02d', $this->year, $this->month, $this->day);
    }

    /**
     * Returns a new instance with some date interval added/subtracted.
     *
     * When operations on months or years are performed the day
     * part is always adjusted so that it does not exceed
     * the last day of month without changing the month.
     * That is, Date::create(2019, 1, 31)->add(1, 'months') gives 2019-02-28
     *
     * @param int $count Positive or negative
     * @param string $what 'years', 'months', 'days'
     * @return Date
     */
    public function add(int $count, string $what): Date
    {
        // TODO
        throw new \LogicException("Not implemented");
    }
}
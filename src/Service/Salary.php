<?php
Namespace Mikko\Service;

class Salary
{
    /**
     * @var \DateTimeImmutable
     */
    public $dateTime;

    /**
     * Salary constructor.
     *
     * @param \DateTimeImmutable $dateTime
     */
    public function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * Check if the previous month has bonus.
     *
     * @return \DateTimeImmutable
     */
    public function bonus(): \DateTimeImmutable
    {
        $date = $this->newDay(15);

        return $this->isWeekday($date) ? $date : $date->modify('Wednesday next week');
    }

    /**
     * if the employee get his salary.
     *
     * @return \DateTimeImmutable
     */
    public function getSalary(): \DateTimeImmutable
    {
        $date = $this->dateTime->modify(
            $this->dateTime->format('Y-m-t')
        );

        return $this->isWeekday($date) ?
            $date :
            $date->modify('last friday');
    }

    /**
     * Checks if the date is a weekday.
     *
     * @param \DateTimeImmutable $dateTime
     *
     * @return bool
     */
    protected function isWeekday(\DateTimeImmutable $dateTime): bool
    {
        $day = (int) $dateTime->format('w');

        return  !($day === 6 || $day === 0);
    }

    /**
     * Set the date object to a new day.
     *
     * @param int $day
     *
     * @return \DateTimeImmutable
     */
    protected function newDay(int $day): \DateTimeImmutable
    {
        $dateTime = $this->dateTime;

        return $dateTime->setDate(
            $dateTime->format('Y'),
            $dateTime->format('n'),
            $day
        );
    }
}
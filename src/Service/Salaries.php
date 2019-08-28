<?php
namespace Mikko\Service;

class Salaries
{
    /**
     * @var \DateTimeImmutable
     */
    protected $dateTime;

    /**
     * @var \Mikko\Service\Salary[]
     */
    protected $salaries = [];

    /**
     * Salaries constructor.
     *
     * @param \DateTimeImmutable $dateTime
     */
    public function __construct(\DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     *
     *
     * @return \Mikko\Service\Salary[]
     */
    public function getSalaries(): array
    {
        if (empty($this->salaries)) {
            $range = range((int) $this->dateTime->format('n'),12);

            $this->salaries = array_map([$this, 'salaries'], $range);
        }

        return $this->salaries;
    }

    /**
     * The map function.
     *
     * @param int $month
     *
     * @return \Mikko\Service\Salary
     */
    private function salaries(int $month)
    {
        $format = $this->dateTime->format(sprintf('Y-%s-01', $month));

        return new Salary(
            $this->dateTime->modify($format)
        );
    }
}
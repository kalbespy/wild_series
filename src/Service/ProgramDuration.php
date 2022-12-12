<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public const DAY_IN_MINUTES = 24 * 60;
    public const HOUR_IN_MINUTES = 60;

    public function calculate(Program $program): array
    {
        $durationInMinutes = 0;
        foreach ($program->getSeasons() as $season) {
            foreach ($season->getEpisodes() as $episode) {
                $durationInMinutes += $episode->getDuration();
            }
        }
        $days = floor($durationInMinutes / self::DAY_IN_MINUTES);
        $durationInMinutes -= $days * self::DAY_IN_MINUTES;
        $hours = floor($durationInMinutes / self::HOUR_IN_MINUTES);
        $minutes = $durationInMinutes - ($hours * self::HOUR_IN_MINUTES);
        $duration = [$days, $hours, $minutes];

        return $duration;
    }
}

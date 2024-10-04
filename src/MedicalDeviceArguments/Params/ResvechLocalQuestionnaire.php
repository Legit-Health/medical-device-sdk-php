<?php

namespace LegitHealth\MedicalDevice\MedicalDeviceArguments\Params;

readonly class ResvechLocalQuestionnaire extends Questionnaire
{
    public function __construct(
        public int $woundDimensions,
        public int $tissues,
        public int $edges,
        public int $tissueInWoundBed,
        public int $exudate,
        public int $frequencyOfPain,
        public int $macerationAroundWound,
        public int $tunneling,
        public int $increasingPain,
        public int $erythemaAroundWound,
        public int $edemaAroundWound,
        public int $temperatureRise,
        public int $increasingExudate,
        public int $purulentExudate,
        public int $tissueFriableOrBleedsEasily,
        public int $stationaryWound,
        public int $biofilmCompatibleTissue,
        public int $odor,
        public int $hypergranulation,
        public int $increasingWound,
        public int $satelliteLesions,
        public int $tissuePaleness
    ) {
        $this->ensureIsInRange($woundDimensions, 0, 4, 'woundDimensions');
        $this->ensureIsInRange($tissues, 0, 4, 'tissues');
        $this->ensureIsInRange($edges, 0, 4, 'edges');
        $this->ensureIsInRange($tissueInWoundBed, 0, 4, 'tissueInWoundBed');
        $this->ensureIsInRange($exudate, 0, 4, 'exudate');
        $this->ensureIsInRange($frequencyOfPain, 0, 4, 'frequencyOfPain');
        $this->ensureIsInRange($macerationAroundWound, 0, 1, 'macerationAroundWound');
        $this->ensureIsInRange($tunneling, 0, 4, 'tunneling');
        $this->ensureIsInRange($increasingPain, 0, 4, 'increasingPain');
        $this->ensureIsInRange($erythemaAroundWound, 0, 4, 'erythemaAroundWound');
        $this->ensureIsInRange($edemaAroundWound, 0, 4, 'edemaAroundWound');
        $this->ensureIsInRange($temperatureRise, 0, 1, 'temperatureRise');
        $this->ensureIsInRange($increasingExudate, 0, 4, 'increasingExudate');
        $this->ensureIsInRange($purulentExudate, 0, 1, 'purulentExudate');
        $this->ensureIsInRange($tissueFriableOrBleedsEasily, 0, 4, 'tissueFriableOrBleedsEasily');
        $this->ensureIsInRange($stationaryWound, 0, 4, 'stationaryWound');
        $this->ensureIsInRange($biofilmCompatibleTissue, 0, 4, 'biofilmCompatibleTissue');
        $this->ensureIsInRange($odor, 0, 4, 'odor');
        $this->ensureIsInRange($hypergranulation, 0, 4, 'hypergranulation');
        $this->ensureIsInRange($increasingWound, 0, 4, 'increasingWound');
        $this->ensureIsInRange($satelliteLesions, 0, 4, 'satelliteLesions');
        $this->ensureIsInRange($tissuePaleness, 0, 4, 'tissuePaleness');
    }

    public static function getName(): string
    {
        return ScoringSystemCode::ResvechLocal->value;
    }

    public function toArray(): array
    {
        return [
            'questionnaire' => $this::getName(),
            'item' => [
                ['code' => 'woundDimensions', 'answer' => [['value' => $this->woundDimensions]]],
                ['code' => 'tissues', 'answer' => [['value' => $this->tissues]]],
                ['code' => 'edges', 'answer' => [['value' => $this->edges]]],
                ['code' => 'tissueInWoundBed', 'answer' => [['value' => $this->tissueInWoundBed]]],
                ['code' => 'exudate', 'answer' => [['value' => $this->exudate]]],
                ['code' => 'frequencyOfPain', 'answer' => [['value' => $this->frequencyOfPain]]],
                ['code' => 'macerationAroundWound', 'answer' => [['value' => $this->macerationAroundWound]]],
                ['code' => 'tunneling', 'answer' => [['value' => $this->tunneling]]],
                ['code' => 'increasingPain', 'answer' => [['value' => $this->increasingPain]]],
                ['code' => 'erythemaAroundWound', 'answer' => [['value' => $this->erythemaAroundWound]]],
                ['code' => 'edemaAroundWound', 'answer' => [['value' => $this->edemaAroundWound]]],
                ['code' => 'temperatureRise', 'answer' => [['value' => $this->temperatureRise]]],
                ['code' => 'increasingExudate', 'answer' => [['value' => $this->increasingExudate]]],
                ['code' => 'purulentExudate', 'answer' => [['value' => $this->purulentExudate]]],
                ['code' => 'tissueFriableOrBleedsEasily', 'answer' => [['value' => $this->tissueFriableOrBleedsEasily]]],
                ['code' => 'stationaryWound', 'answer' => [['value' => $this->stationaryWound]]],
                ['code' => 'biofilmCompatibleTissue', 'answer' => [['value' => $this->biofilmCompatibleTissue]]],
                ['code' => 'odor', 'answer' => [['value' => $this->odor]]],
                ['code' => 'hypergranulation', 'answer' => [['value' => $this->hypergranulation]]],
                ['code' => 'increasingWound', 'answer' => [['value' => $this->increasingWound]]],
                ['code' => 'satelliteLesions', 'answer' => [['value' => $this->satelliteLesions]]],
                ['code' => 'tissuePaleness', 'answer' => [['value' => $this->tissuePaleness]]],
            ]
        ];
    }
}

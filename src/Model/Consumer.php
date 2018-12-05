<?php

namespace IntecPhp\Model;

use IntecPhp\Entity\ConsumerEntity;

class Consumer
{
    const NORDESTE     = 'NE';
    const SUDESTE      = 'SE';
    const SUL          = 'S';
    const CENTRO_OESTE = 'CO';
    const NORTE        = 'N';



    private $consumerEntity;

    public function __construct(ConsumerEntity $consumerEntity)
    {
        $this->consumerEntity = $consumerEntity;
    }

    public function calculateRegionsComplaintRate()
    {
        $total_r  = $this->consumerEntity->countTotalRecords();
        $total = $total_r ? (int)$total_r['total'] : 0;
        $total_ne = $this->consumerEntity->countRegionRecords(self::NORDESTE);
        $ne = $total_ne ? (int)$total_ne['total'] : 0;
        $total_se = $this->consumerEntity->countRegionRecords(self::SUDESTE);
        $se = $total_se ? (int)$total_se['total'] : 0;
        $total_s  = $this->consumerEntity->countRegionRecords(self::SUL);
        $s = $total_s ? (int)$total_s['total'] : 0;
        $total_co = $this->consumerEntity->countRegionRecords(self::CENTRO_OESTE);
        $co = $total_co ? (int)$total_co['total'] : 0;
        $total_n  = $this->consumerEntity->countRegionRecords(self::NORTE);
        $n = $total_n ? (int)$total_n['total'] : 0;

        $regions = [
            self::NORDESTE     => round(($ne / $total) * 100, 2),
            self::SUDESTE      => round(($se / $total) * 100, 2),
            self::SUL          => round(($s / $total) * 100, 2),
            self::CENTRO_OESTE => round(($co / $total) * 100, 2),
            self::NORTE        => round(($n / $total) * 100, 2)
        ];
        asort($regions);

        return $regions;
    }
}

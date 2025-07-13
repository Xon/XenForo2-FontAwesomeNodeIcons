<?php

namespace SV\FontAwesomeNodeIcons\Listener;

use SV\FontAwesomeNodeIcons\XF\Entity\Node as ExtendedNodeEntity;
use SV\StandardLib\Helper;
use XF\Repository\Node as NodeRepository;
use XF\Service\Icon\UsageAnalyzerService;

abstract class IconUsage
{
    /** @noinspection PhpUnusedParameterInspection */
    public static function analyzerSteps(array &$steps, UsageAnalyzerService $usageAnalyzer): void
    {
        $steps['svNodeIcons'][] = function (?int $lastOffset, float $maxRunTime) use ($usageAnalyzer): ?int {
            /** @var array<int,ExtendedNodeEntity> $nodes */
            $nodes = Helper::repository(NodeRepository::class)
                           ->getFullNodeList()
                           ->toArray();

            foreach ($nodes as $id => $node)
            {
                $icon = $node->getFontAwesomeIcon();
                if ($icon !== '')
                {
                    $usageAnalyzer->recordIconsFromClasses('svNodeIcons', $id, $icon);
                }
            }

            return null;
        };
    }
}
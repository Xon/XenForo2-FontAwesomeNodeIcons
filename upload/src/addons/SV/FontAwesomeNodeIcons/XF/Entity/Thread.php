<?php

namespace SV\FontAwesomeNodeIcons\XF\Entity;

use SV\FontAwesomeNodeIcons\XF\Entity\Node as ExtendedNodeEntity;

/**
 * @extends \XF\Entity\Thread
 */
class Thread extends XFCP_Thread
{
    public function getFontAwesomeUnreadIcon(): string
    {
        $nodeIcon = null;
        $forum = $this->Forum;
        if ($forum !== null)
        {
            /** @var ExtendedNodeEntity|null $node */
            $node = $forum->Node;
            if ($node !== null)
            {
                $icon = $node->fa_node_icon;
                if ($icon !== null && $icon !== '')
                {
                    $nodeIcon = $node->getUnreadFontAwesomeIcon($icon);
                }
            }
        }

        return $nodeIcon ?? 'fas fa-arrow-circle-right';
    }
}
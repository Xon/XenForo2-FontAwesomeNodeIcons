<?php

namespace SV\FontAwesomeNodeIcons\XF\Entity;

use SV\FontAwesomeNodeIcons\XF\Entity\Node as ExtendedNodeEntity;

/**
 * @extends \XF\Entity\Thread
 */
class Thread extends XFCP_Thread
{
    public function getFontAwesomeUnreadIcon(?string $defaultIcon = null): ?string
    {
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
                    return $node->getUnreadFontAwesomeIcon($icon);
                }
            }
        }

        return $defaultIcon;
    }
}
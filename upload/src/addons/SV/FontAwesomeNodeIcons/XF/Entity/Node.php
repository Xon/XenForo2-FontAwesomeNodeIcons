<?php

declare(strict_types=1);

namespace SV\FontAwesomeNodeIcons\XF\Entity;

use XF\Entity\Forum as ForumEntity;
use XF\Mvc\Entity\Structure;
use function array_key_exists;
use function preg_match;
use function trim;

/**
 * @extends \XF\Entity\Node
 * @property string|null $fa_node_icon
 */
class Node extends XFCP_Node
{
    protected $svDefaultIconByType = [
        'Forum'       => 'fa-comments',
        'SearchForum' => 'fa-search',
        'Category'    => 'fa-comments',
        'LinkForum'   => 'fa-link',
        'Page'        => 'fa-file-alt',
    ];

    protected $svDefaultIconByTypeForAdmin = [
        'Category'    => 'fa-bars',
    ];

    protected function getFontAwesomeIconDefault(bool $forAdmin = false): string
    {
        $nodeTypeId = $this->node_type_id;
        if ($nodeTypeId === 'Forum')
        {
            $data = $this->Data;
            if ($data instanceof ForumEntity)
            {
                $typeHandler = $data->TypeHandler;
                if ($typeHandler !== null)
                {
                    $icon = $typeHandler->getTypeIconClass() ?? '';
                    if ($icon !== '')
                    {
                        return $icon;
                    }
                }
            }
        }

        if ($forAdmin && array_key_exists($nodeTypeId, $this->svDefaultIconByTypeForAdmin))
        {
            return $this->svDefaultIconByTypeForAdmin[$nodeTypeId];
        }

        return $this->svDefaultIconByType[$nodeTypeId] ?? '';
    }

    public function isUsingPerNodeIcon(): bool
    {
        return true;
    }

    public function getFontAwesomeIcon(?array $extra = [], bool $forAdmin = false): string
    {
        $icon = $this->isUsingPerNodeIcon() ? $this->fa_node_icon : null;
        if ($icon === null || $icon === '')
        {
            $icon = $this->getFontAwesomeIconDefault($forAdmin);
        }

        if ($extra['hasNew'] ?? false)
        {
            $icon = $this->getUnreadFontAwesomeIcon($icon);
        }

        return $icon;
    }

    public function getUnreadFontAwesomeIcon(string $icon): string
    {
        if (preg_match('#^(?:fal|far)\s+(.*)$#',$icon, $match))
        {
            $icon = $match[1];
        }

        return 'fas ' . $icon;
    }

    protected function _preSave()
    {
        if (!trim($this->fa_node_icon ?? ''))
        {
            $this->fa_node_icon = null;
        }

        parent::_preSave();
    }

    /**
     * @param Structure $structure
     * @return Structure
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);

        $structure->columns['fa_node_icon'] = [
            'type'  => self::STR, 'maxLength' => 100, 'nullable' => true, 'default' => null,
            'match' => '^[A-Za-z0-9_-]*$',
            'api'   => true,
        ];

        return $structure;
    }
}
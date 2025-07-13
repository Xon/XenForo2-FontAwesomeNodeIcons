<?php

namespace SV\FontAwesomeNodeIcons\Admin\Controller;

use XF\Entity\AbstractNode as AbstractNodeEntity;
use XF\Entity\Node as NodeEntity;
use XF\Mvc\FormAction;

trait NodeIconTrait
{
    protected function saveTypeData(FormAction $form, NodeEntity $node, AbstractNodeEntity $data)
    {
        /** @noinspection PhpMultipleClassDeclarationsInspection */
        parent::saveTypeData($form, $node, $data);

        $input = $this->filter([
            'node' => [
                'fa_node_icon' => 'str',
            ],
        ]);

        $form->setupEntityInput($node, $input['node']);
    }
}
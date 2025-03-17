<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class OfferTitlePlaceholder extends BaseBlock
{
    


    private string $view = 'email-templates-json-parser.blocks.offer-title-placeholder';
    public string $type = BlockTypes::OFFER_TITLE_PLACEHOLDER;
    

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
    public function parse()
    {
        return view($this->view, [
            'props' => $this->data['props'],
            'style' => $this->parseStyles($this->data['style']),
        ]);
    }
}

<?php

namespace App\Helpers\EmailTemplatesJsonParser\Blocks;

use App\Helpers\EmailTemplatesJsonParser\Helpers\BlockTypes;

class UserFooterPlaceholder extends BaseBlock
{
    


    private string $view = 'email-templates-json-parser.blocks.user-footer-placeholder';
    public string $type = BlockTypes::USER_FOOTER_PLACEHOLDER;
    

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

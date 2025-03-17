<?php

namespace App\Http\Controllers\Front;


use App\Helpers\EmailTemplatesJsonParser\EmailTemplateParser;
use App\Helpers\EmailTemplatesJsonParser\WebTemplateParser;
use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;


class EmailTemplatePreviewController extends Controller
{
  private string $pageTemplateView = 'admin.web-generator.index';
  private string $emailTemplateView = 'front.email-template-preview.show';

  public function index(Request $request)
  {


    $json2 = '{
  "root": {
    "type": "EmailLayout",
    "data": {
      "textColor": "#262626",
      "fontFamily": "MODERN_SANS",
      "canvasColor": "#FFFFFF",
      "childrenIds": [
        "block-1724915916163",
        "block-1724917520365"
      ],
      "backdropColor": "#F5F5F5"
    }
  },
  "block-1724915916163": {
    "type": "ColumnsContainer",
    "data": {
      "style": {
        "padding": {
          "top": 0,
          "bottom": 0,
          "right": 0,
          "left": 0
        }
      },
      "props": {
        "columnsCount": 3,
        "columnsGap": 16,
        "columns": [
          {
            "childrenIds": [
              "block-1724915919628",
              "block-1724915921387"
            ]
          },
          {
            "childrenIds": [
              "block-1724915924515",
              "block-1724915926268"
            ]
          },
          {
            "childrenIds": [
              "block-1724916790302"
            ]
          }
        ]
      }
    }
  },
  "block-1724915919628": {
    "type": "Text",
    "data": {
      "style": {
        "backgroundColor": "#7C3AED",
        "fontWeight": "normal",
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      },
      "props": {
        "text": "Lorem ipsum dolor"
      }
    }
  },
  "block-1724915921387": {
    "type": "Button",
    "data": {
      "style": {
        "backgroundColor": "#0284C7",
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      },
      "props": {
        "text": "Przycisk",
        "url": "https://www.usewaypoint.com"
      }
    }
  },
  "block-1724915924515": {
    "type": "Heading",
    "data": {
      "props": {
        "text": "Lorem ipsum dolor"
      },
      "style": {
        "backgroundColor": "#C026D3",
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      }
    }
  },
  "block-1724915926268": {
    "type": "Text",
    "data": {
      "style": {
        "backgroundColor": "#C026D3",
        "fontWeight": "normal",
        "padding": {
          "top": 16,
          "bottom": 16,
          "right": 24,
          "left": 24
        }
      },
      "props": {
        "text": "Lorem ipsum dolor"
      }
    }
  },
  "block-1724916790302": {
    "type": "Button",
    "data": {
      "props": {
        "text": "Przycisk",
        "url": "https://www.usewaypoint.com"
      },
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "left": 24,
          "right": 24
        }
      }
    }
  },
  "block-1724917520365": {
    "type": "Text",
    "data": {
      "props": {
        "text": "Lorem ipsum dolor"
      },
      "style": {
        "padding": {
          "top": 16,
          "bottom": 16,
          "left": 24,
          "right": 24
        },
        "fontWeight": "normal"
      }
    }
  }
}';

    $emailParser = new WebTemplateParser($json2);
    $emailParser->prepareBlocks();

    return view($this->pageTemplateView, [
      'html' => $emailParser->render()
    ]);
  }

  public function show(Request $request, $id)
  {

    $template = EmailTemplate::findOrFail($id);
    $meta = $template->meta;
    $templateType = $meta['template_type'];
    $parser = null;
    $view = null;
    $content = $template->content;


    if ($template->is_uploaded) {
      return view($this->emailTemplateView)->with('html', $content);
    }

    if (!$content) {
      abort(404);
    }

    switch ($templateType) {
      case TemplateTypes::EMAIL:
      case TemplateTypes::EMPTY:
      default:
        $parser = new EmailTemplateParser($content);
        $view = $this->emailTemplateView;
        break;
      case TemplateTypes::OFFER:
        $parser = new WebTemplateParser($content);
        $view = $this->pageTemplateView;
        break;
    }


    $parser->prepareBlocks();
    $html = $parser->render();
    $html = $this->replaceTitlePlaceholder($html, $this->getPlaceholderBlock('Tu pojawi się tytuł oferty'));
    $html = $this->replaceTextPlaceholder($html, $this->getPlaceholderBlock('Tu pojawi się tekst oferty'));
    $html = $this->replaceOfferPlaceholder($html, $this->getPlaceholderBlock('Tu pojawi się oferta'));
    $html = $this->replaceOfferAttachmentsPlaceholder($html, $this->getPlaceholderBlock('Tu pojawią się załączniki oferty'));
    $html = $this->replaceUserFooterPlaceholder($html, $this->getPlaceholderBlock('Tu pojawi się stopka użytkownika'));

    return view($view)->with('html', $html);
  }
  private function getPlaceholderBlock(string $placeholder)
  {
    return "<div style='padding: 16px; margin: 10px 0; background:#e5e5e5;'>$placeholder</div>";
  }

  private function replaceTitlePlaceholder(string $html, string $title)
  {
    return str_replace('%OFFER_TITLE_PLACEHOLDER%', $title, $html);
  }
  private function replaceTextPlaceholder(string $html, string $text)
  {
    return str_replace('%OFFER_TEXT_PLACEHOLDER%', $text, $html);
  }
  private function replaceOfferPlaceholder(string $html, string $text)
  {
    return str_replace('%OFFER_PLACEHOLDER%', $text, $html);
  }

  private function replaceOfferAttachmentsPlaceholder(string $html, string $attachments)
  {
    return str_replace('%OFFER_ATTACHMENTS_PLACEHOLDER%', $attachments, $html);
  }

  private function replaceUserFooterPlaceholder(string $html, string $signature)
  {
    return str_replace('%USER_FOOTER_PLACEHOLDER%', $signature, $html);
  }
}

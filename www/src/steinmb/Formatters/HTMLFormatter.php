<?php declare(strict_types=1);

namespace steinmb\steinmb\Formatters;

use steinmb\onewire\Temperature;

class HTMLFormatter
{

    public function unorderedList(Temperature $sensor): string
    {
        $content = '<div class="block">';
        $content .= '<h2 class="title">' . $sensor->entity->id() . '</h2>';
        $content .= '<ul>';
        $content .= "<li>{$sensor->entity->timeStamp()}</li>";
        $content .= "<li>{$sensor->temperature()}</li>";
        $content .= '</ul></div>';

        return $content;
    }

}

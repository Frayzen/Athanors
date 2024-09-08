<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('listGroup', [$this, 'listGroup'], ["is_safe"=>["html"]]),
        ];
    }

    public function listGroup($value, $title = '', $sub = '')
    {   $r = '
            <li class="list-group-item">
                <label for="disabledTextInput">'.$title.'</label>
                <input type="text" id="disabledTextInput" class="form-control" placeholder="'.$value.'">';
        if ($sub != '')
            $r.='<small id="emailHelp" class="form-text text-muted">'.$sub.'</small>';
        $r.='</li>';
        return $r;
    }
}
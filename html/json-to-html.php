<?php

function renderMarkup($Elements, $Module_Set, $Module_Publisher) {

  $NameSpace = url($Module_Set).'»by»'.txt($Module_Publisher, 'camelCase').'»»»';

  $Markup = '';

  for ($i = 0; $i < count($Elements); $i++) {

    if ((isset($Elements[$i]['element'])) && ($Elements[$i]['element'] === 'attributes')) {

      foreach ($Elements[$i]['attributes'] as $Attribute => $Value) {

        if ($Value === $Attribute) {

          $Markup .= ' '.$Attribute;
        }

        else {

          $Markup .= ' '.$Attribute.'="'.$Value.'"';
        }
      }
    }

    else {

      if (isset($Elements[$i]['plainText'])) {

        $Markup .= htmlspecialchars($Elements[$i]['plainText']);
      }
  
      elseif (isset($Elements[$i]['element'])) {

        $Markup .= '<'.$Elements[$i]['element'];

        if (isset($Elements[$i]['id'])) {
      
          $id = $NameSpace.$Elements[$i]['id'];
          $Markup .= ' id="'.$id.'"';
        }

        if (isset($Elements[$i]['classList'])) {
      
          $ClassList = $NameSpace.implode(' ', $Elements[$i]['classList']);
          $ClassList = str_replace(' ', ' '.$NameSpace, $ClassList);
          $Markup .= ' class="'.$ClassList.'"';
        }

        if (isset($Elements[$i]['attributes'])) {
        
          foreach ($Elements[$i]['attributes'] as $Attribute => $Value) {

            if ($Attribute === 'for') {$Value = $NameSpace.$Value;}
            
            if ($Value === $Attribute) {

              $Markup .= ' '.$Attribute;
            }

            else {

              $Markup .= ' '.$Attribute.'="'.$Value.'"';
            }
          }
        }

        if (isset($Elements[$i]['self-closing'])) {

          $Markup .= ' />'; continue;
        }
        
        else {
          
          $Markup .= '>';
          $Element_Children = $Elements[$i]['elementChildren'] ?? [];   
          $Markup .= renderMarkup($Element_Children, $Module_Set, $Module_Publisher);
          $Markup .= '</'.$Elements[$i]['element'].'>';
        }
      }

      else {

        $Markup .= '<!-- ⚠️ Ashiva Console: HTML Element Missing in '.txt($Module_Set).' by '.txt($Module_Publisher).' -->';
      }
    }
  }

  return $Markup;
}


function getMarkup($Module, $Context = 'page') {
  
  $Module_Markup = [];

  $Module_Set = url(str_replace('::', '°', $Module['Name']));

  if ($Context === 'element') {

    if (in_array($Module['Name'], ['SB_Body_Data'])) {

      $Module_Markup = $Module['Source'];
    }

    else {

      $Module_Markup = json_decode($Module['Source'], TRUE);
    }
  }

  elseif ($Context === 'page') {

    $Module_Markup[0]['element'] = 'div';
    $Module_Markup[0]['attributes']['data-ashiva-module'] = $Module_Set;
    $Module_Markup[0]['attributes']['data-ashiva-publisher'] = txt($Module['Publisher'], 'camelCase');
    $Module_Markup[0]['elementChildren'] = $Module['Source'];
  }

  $renderedMarkup = renderMarkup($Module_Markup, $Module_Set, $Module['Publisher']);
  // $renderedMarkup = preg_replace('/\>(?=\<(?:aside|div|\/div|form|\/form|h2|label|li|p|ul|\/ul))/', '>\n', $renderedMarkup);
  $renderedMarkup = preg_replace('/\>\<(aside|div|\/div|form|\/form|h2|label|li|p|ul|\/ul)/', '>\n<$1', $renderedMarkup);
  $renderedMarkup = preg_replace('/\<\/(div|h2|h3|li|p|ul)\>/', '</$1>\n', $renderedMarkup);
  $renderedMarkup = str_replace('\n\n', '\n', $renderedMarkup);
  $renderedMarkup = explode('\n', $renderedMarkup);
  $renderedMarkup = implode("\n", $renderedMarkup);

  ($Context === 'element') ?: $renderedMarkup = "\n".$renderedMarkup."\n";

  $newlinedElementModules = array('SB_Body_Data');
  if (in_array($Module['Name'], $newlinedElementModules)) {$renderedMarkup = str_replace("\" ", "\"\n", $renderedMarkup);}

  return $renderedMarkup;
}

?>

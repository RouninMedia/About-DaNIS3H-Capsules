<?php

function renderMarkup($Element, $Module_Set, $Module_Publisher) {

  $NameSpace = url($Module_Set).'»by»'.cml($Module_Publisher).'»»»';

  $Markup = '';

  for ($i = 0; $i < count($Element); $i++) {

    if ((isset($Element[$i]['element'])) && ($Element[$i]['element'] === 'attributes')) {

      foreach ($Element[$i]['attributes'] as $Attribute => $Value) {

        if ($Value === $Attribute) {

          $Markup .= ' '.$Attribute;
        }

        else {

          $Markup .= ' '.$Attribute.'="'.$Value.'"';
        }
      }
    }

    else {

      if (isset($Element[$i]['plainText'])) {

        $Markup .= htmlspecialchars($Element[$i]['plainText']);
      }
  
      elseif (isset($Element[$i]['element'])) {

        $Markup .= '<'.$Element[$i]['element'];

        if (isset($Element[$i]['id'])) {
      
          $id = $NameSpace.$Element[$i]['id'];
          $Markup .= ' id="'.$id.'"';
        }

        if (isset($Element[$i]['classList'])) {
      
          $ClassList = $NameSpace.implode(' ', $Element[$i]['classList']);
          $ClassList = str_replace(' ', ' '.$NameSpace, $ClassList);
          $Markup .= ' class="'.$ClassList.'"';
        }

        if (isset($Element[$i]['attributes'])) {
        
          foreach ($Element[$i]['attributes'] as $Attribute => $Value) {

            if ($Attribute === 'for') {$Value = $NameSpace.$Value;}
            
            if ($Value === $Attribute) {

              $Markup .= ' '.$Attribute;
            }

            else {

              $Markup .= ' '.$Attribute.'="'.$Value.'"';
            }
          }
        }

        if (isset($Element[$i]['self-closing'])) {

          $Markup .= ' />'; continue;
        }
        
        else {
          
          $Markup .= '>';        
          $Markup .= renderMarkup($Element[$i]['elementChildren'], $Module_Set, $Module_Publisher);
          $Markup .= '</'.$Element[$i]['element'].'>';
        }
      }

      else {

        $Markup .= '<!-- ⚠️ Ashiva Console: HTML Element Missing in '.txt($Module_Set).' by '.txt($Module_Publisher).' -->';
      }
    }
  }

  return $Markup;
}



function getMarkup($Module, $Context = 'page', $Chapter = NULL) {
  
  $Module_Markup = array();
  $Module_Set = url(str_replace('::', '°', $Module['Name']));

  if ($Context === 'element') {

    $Module_Markup = json_decode($Module['Content'], TRUE);
  }

  elseif ($Context === 'page') {

    $Module_Markup[0]['element'] = 'div';
    $Module_Markup[0]['attributes']['data-ashiva-module'] = $Module_Set;
    $Module_Markup[0]['attributes']['data-ashiva-publisher'] = cml($Module['Publisher']);

    $Markup = $Module['Content'];

    // NOT SURE WHAT IS GOING ON IMMEDIATELY BELOW?

    /*
    if (!is_null($Chapter)) {

      $i = 0;

      while ($i < count($Chapter)) {

        $Markup = $Markup[$Chapter[$i]];
        $i++;
      }
    }
    */
    
    // TEMPORARY REPLACEMENT FOR THE WHILE LOOP ABOVE
    if (!is_null($Chapter)) {$Markup = $Markup[$Chapter[0]];}

    $Module_Markup[0]['elementChildren'] = json_decode($Markup, TRUE);
  }

  $renderedMarkup = renderMarkup($Module_Markup, $Module_Set, $Module['Publisher']);
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

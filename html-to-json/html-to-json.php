<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$Markup = '
<div class="veil revealed"></div>
<form class="scotiaBeautyConsole --emailConfirm" data-state="enter" method="post" action="/assets/system/forms/email-confirmation/email-confirmation.php">
<h2 class="consoleHeading">Please Confirm Your Details. <strong>Thank you</strong>.</h2>

<p class="consoleParagraph">Your Details:</p>
<label for="nameGDPR">Name:<input type="text" name="nameGDPR" id="nameGDPR" required /></label>
<label for="companyGDPR">Company:<input type="text" name="companyGDPR" id="companyGDPR" required /></label>
<label for="emailGDPR">Email Address:<input type="email" name="emailGDPR" id="emailGDPR" required /></label>

<p class="email-permission"><input type="checkbox" name="email-confirmation" required /> I would like to receive regular email updates from <strong>Scotia Beauty</strong> with information on new nail and beauty products, changes to the catalogue or price list and special offers.*</p>

<input type="submit" id="emailConfirmationFormSubmit" value="Please Confirm Your Details" />
<p class="consoleParagraph">Thank you from <strong>Scotia Beauty</strong>!</p>
<small>*Please note that we take your privacy and data protection according to the <strong>GDPR</strong> <em>(General Data Protection Regulation (EU) 2016/679)</em> very seriously. <strong>Scotia Beauty</strong> does not and will not disclose your contact details to any third party and will only use them to email you the information as described above. You can unsubscribe from our email list at any time.</small>
</form>
';


function getElementName($Element) {

  $Element_Array = explode(' ', $Element);
  $ElementName = $Element_Array[0];
  $ElementName = preg_replace('/{|}/', '', $ElementName);

  return $ElementName;
}


function createElementObjectString($Element) {

  $ElementName = getElementName($Element);

  $ElementObjectString = $Element;
  $ElementObjectString = preg_replace('/\s*\/\s*\}/', '}', $ElementObjectString);
  $ElementObjectString = preg_replace('/{|}/', '', $ElementObjectString);
  $ElementObjectString = preg_replace('/'.$ElementName.'/', '', $ElementObjectString, 1);
  $ElementObjectString = trim($ElementObjectString);
  
  return $ElementObjectString;
}


function buildElementArray($ElementObjectString) {

  $ElementObjectString = str_replace(' ', '$ ', $ElementObjectString);
  $Element_Array = explode('$ ', $ElementObjectString);

  return $Element_Array;
}


function buildElementArray2($Element_Array) {

  // REBUILDS ELEMENT_ARRAY, ENABLING ATTRIBUTE VALUES (LIKE CLASS="myClass myClass2") TO CONTAIN SPACES

  $Element_Array_2 = array();

  for ($i = 0; $i < count($Element_Array); $i++) {

    if (($i > 0) && (substr($Element_Array[($i - 1)], -1, 1) !== '"') && (strpos($Element_Array[$i], '=') === FALSE)) {

      $Element_Array_2[(count($Element_Array_2) - 1)] .= ' '.$Element_Array[$i];
    }

    else {

      $Element_Array_2[] = $Element_Array[$i];
    }
  }

  return $Element_Array_2;
}


function addStandAloneAttributes($Element_Array_2) {

  // FINDS ALL STANDALONE ATTRIBUTES (like required), REMOVES THEM AND RE-ADDS THEM TO THE END AS required="required"

  $StandAloneAttributes = array();

  for ($i = 0; $i < count($Element_Array_2); $i++) {

    if (strpos($Element_Array_2[$i], '=') === FALSE) {

      $StandAloneAttributes = array_merge($StandAloneAttributes, explode(' ', $Element_Array_2[$i]));

      unset($Element_Array_2[$i]);
    }
  }

  for ($i = 0; $i < count($StandAloneAttributes); $i++) {

    $StandAloneAttributes[$i] = $StandAloneAttributes[$i].'="'.$StandAloneAttributes[$i].'"';
  }

  $Element_Array_2 = array_merge($Element_Array_2, $StandAloneAttributes);

  return $Element_Array_2;
}


function buildElementObject($Element_Array_3) {

  $Element_Object = array();

  for ($i = 0; $i < count($Element_Array_3); $i++) {

    $End_Of_Attribute_Name = strpos($Element_Array_3[$i], '=');
    $Attribute_Name = substr($Element_Array_3[$i], 0, $End_Of_Attribute_Name);

    $Start_Of_Attribute_Value = (strpos($Element_Array_3[$i], '"') + 1);
    $End_Of_Attribute_Value = strrpos($Element_Array_3[$i], '"');
    $Length_Of_Attribute_Value = ($End_Of_Attribute_Value - $Start_Of_Attribute_Value);
    $Attribute_Value = substr($Element_Array_3[$i], $Start_Of_Attribute_Value, $Length_Of_Attribute_Value);

    $Element_Object[$Attribute_Name] = $Attribute_Value;
  }

  if (isset($Element_Object['class'])) {

    $Element_Object['class'] = '["'.$Element_Object['class'].'"]';
    $Element_Object['class'] = str_replace(' ', '", "', $Element_Object['class']);
  }

  return $Element_Object;
}


// KEEP OPTIMISING MODULES

// EXPERIMENT WITH TURNING ALL MODULE OUTPUT INTO JSON



function createElementObject($Element) {

  $ElementObjectString = createElementObjectString($Element);

  if ($ElementObjectString !== '') {

    $Element_Array = buildElementArray($ElementObjectString);
    $Element_Array_2 = buildElementArray2($Element_Array);
    $Element_Array_3 = addStandAloneAttributes($Element_Array_2);
    $Element_Object = buildElementObject($Element_Array_3);

    return $Element_Object;
  }

  else return NULL;
}


function buildMarkupString($Markup) {

  $Markup_String = $Markup;

  $Markup_String = str_replace('<', '^{', $Markup_String);
  $Markup_String = str_replace('>', '}^', $Markup_String);
  $Markup_String = trim($Markup_String);
  $Markup_String = substr($Markup_String, 1, (strlen($Markup_String) - 2));
  $Markup_String = preg_replace("/\s{2,}/", ' ', $Markup_String);
  $Markup_String = preg_replace('/(\{\/[^\}]+\})\^ \^\{\//', '$1^{/', $Markup_String);

  return $Markup_String;
}


function buildMarkupArray($Markup_String) {

  $Markup_Array =  explode('^', $Markup_String);

  for ($i = 0; $i < count($Markup_Array); $i++) {

    $Markup_Array[$i] = str_replace('"', '" ', $Markup_Array[$i]);
    $Markup_Array[$i] = str_replace('=" ', '="', $Markup_Array[$i]);
    $Markup_Array[$i] = str_replace('"  ', '" ', $Markup_Array[$i]);

    if (preg_match('/^\s*$/', $Markup_Array[$i])) {

      unset($Markup_Array[$i]);
    }
  }

  $Markup_Array = array_values(array_filter($Markup_Array));

  return $Markup_Array;
}


function buildJSONString($Markup_Array) {

  $JSON_String = '[';

  for ($i = 0; $i < count($Markup_Array); $i++) {

    switch (TRUE) {

      case (substr($Markup_Array[$i], 0, 2) === '{/') :

        $JSON_String .= ']}';
        break;


      case (substr($Markup_Array[$i], 0, 1) !== '{') :

        $JSON_String .= ', {';
        $JSON_String .= '"plainText" : "'.$Markup_Array[$i].'"';
        $JSON_String .= '}';
        break;


      case (substr($Markup_Array[$i], 0, 1) === '{') :

        $JSON_String .= ', {';
        $JSON_String .= '"element" : "'.getElementName($Markup_Array[$i]).'", ';

        $ElementObject = createElementObject($Markup_Array[$i]);

        if ($ElementObject !== NULL) {

          if (isset($ElementObject['id'])) {
        
            $JSON_String .= '"id" : "'.$ElementObject['id'].'", ';
            unset($ElementObject['id']);
          }

          if (isset($ElementObject['class'])) {
        
            $JSON_String .= '"classList" : '.$ElementObject['class'].', ';
            unset($ElementObject['class']);
          }

          if (count($ElementObject) > 0) {

            $JSON_String .= '"attributes" : {';

            foreach ($ElementObject as $Attribute_Name => $Attribute_Value) {

              $JSON_String .= '"'.$Attribute_Name.'" : "'.$Attribute_Value.'", ';
            }

            $JSON_String .= '}, ';
          }
        }

        if (substr($Markup_Array[$i], -2, 2) === '/}') {

          $JSON_String .= '"self-closing" : true, ';
          $JSON_String .= '"elementChildren" : []}';
        }

        else {

          $JSON_String .= '"elementChildren" : [';
        }

        break;
    }
  }

  $JSON_String .= ']';
  $JSON_String = str_replace('[, {', '[{', $JSON_String);
  $JSON_String = str_replace(', }', '}', $JSON_String);

  return $JSON_String;
}



function serializeMarkup($Markup) {

  $Markup_String = buildMarkupString($Markup);
  $Markup_Array = buildMarkupArray($Markup_String);
  $JSON_String = buildJSONString($Markup_Array);

  return $JSON_String;
}



echo '<pre>'.serializeMarkup($Markup).'</pre>';

?>

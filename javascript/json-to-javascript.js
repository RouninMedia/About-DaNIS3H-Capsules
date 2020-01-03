  //*********************************//
 // DA3SH MODULES :: RENDER SCRIPTS //
//*********************************//

function renderScript(scriptJSON, moduleInfo = {name: 'ashivaModule', publisher: 'ashivaPublisher'}, codePanel = false) {

  // FUNCTION :: INDENT
  const indent = (indentLevel) => {

    const indentSpace = [];

    for (let i = 0; i < indentLevel; i++) {indentSpace.push('  ');}

    return indentSpace.join('');
  }


  // FUNCTION :: BUILD SCRIPT
  function buildScript(scriptArray, moduleInfo, indentLevel = 0) {
    
    let controlWord;
    const terminationMap = [];
    terminationMap[indentLevel] = false;

    let scriptString = '';

    for (let h = 0; h < scriptArray.length; h++) {

      if (scriptArray[h].hasOwnProperty('Control')) {

        switch (scriptArray[h]['Control']) {

          case ('Function') :

            let assigner = (scriptArray[h].hasOwnProperty('Assigner')) ? scriptArray[h]['Assigner'] : 'const';

            scriptString += "\n" + indent(indentLevel);

            if (scriptArray[h].hasOwnProperty('Function_Invoked') && (scriptArray[h]['Function_Invoked'] === true)) {

              if (scriptArray[h]['Assigned_Name'] !== '') {scriptString += assigner + ' ' + scriptArray[h]['Assigned_Name'] + ' = ';}
              scriptString += '(' + buildScript(scriptArray[h]['Control_Function'], moduleInfo, indentLevel) + ')();' + "\n";
            }

            else {
                
              if (scriptArray[h]['Assigned_Name'] !== '') {terminationMap[indentLevel] = true; scriptString += assigner + ' ' + scriptArray[h]['Assigned_Name'] + ' = ';}
              if ((scriptArray[h].hasOwnProperty('Function_Type') && (scriptArray[h]['Function_Type'] === 'Regular'))) {scriptString += 'function ';}
              if (scriptArray[h].hasOwnProperty('Declared_Name')) {scriptString += scriptArray[h]['Declared_Name'] + ' ';}
              scriptString += '(' + scriptArray[h]['Parameters'].join(', ') + ')';
              if ((!scriptArray[h].hasOwnProperty('Function_Type') || (scriptArray[h]['Function_Type'] === 'Arrow'))) {scriptString += ' =>';}
            }
          
          break;


          case ('Loop') :

            scriptString += "\n" + indent(indentLevel);

            switch (scriptArray[h]['Type']) {

              case ('For') :

                scriptString += 'for (' + scriptArray[h]['Header'] + ')';
                break;

              case ('While') :

                scriptString += 'while (';

                if (scriptArray[h]['Conditions'].length > 1) {
            
                  for (let i = 0; i < scriptArray[h]['Conditions'].length; i++) {
                
                    if (i > 0) {scriptString += ' ' + scriptArray[h]['Operator'] + ' ';}
                    scriptString += '(' + scriptArray[h]['Conditions'][i] + ')';
                  }
                }

                else {scriptString += scriptArray[h]['Conditions'][0];}
                scriptString += ')';
                break;
            }

            break;


          case ('If') :
          case ('Else_If') :

            controlWord = (scriptArray[h]['Control'] === 'If') ? 'if' : 'else if';
            scriptString += "\n" + indent(indentLevel) + controlWord + ' (';

            if (scriptArray[h]['Conditions'].length > 1) {
            
              for (let i = 0; i < scriptArray[h]['Conditions'].length; i++) {
                
                if (i > 0) {scriptString += ' ' + scriptArray[h]['Operator'] + ' ';}
                scriptString += '(' + scriptArray[h]['Conditions'][i] + ')';
              }
            }

            else {scriptString += scriptArray[h]['Conditions'][0];}
            scriptString += ')';

            break;


          case ('Else') :

            scriptString += "\n" + indent(indentLevel) + 'else';

            break;


          case ('Statements') :

            for (let i = 0; i < scriptArray[h]['Statements'].length; i++) {scriptString += indent(indentLevel) + scriptArray[h]['Statements'][i] + ';' + "\n";}

            break;


          case ('Event_Listener') :

            scriptString += indent(indentLevel) + scriptArray[h]['Event_Target'];
            scriptString += ((scriptArray[h]['Event_Target'] === 'window') && (scriptArray[h]['Event_Type'] === 'load')) ? '_' : '.';
            scriptString += 'addEventListener(\'' + scriptArray[h]['Event_Type'] + '\', ';
            if (scriptArray[h]['Event_Callback_Name'] !== '') {scriptString += scriptArray[h]['Event_Callback_Name'];}
            else {scriptString += buildScript(scriptArray[h]['Control_Function'], moduleInfo, indentLevel);}
            scriptString += ', ';
            scriptString += (scriptArray[h]['Event_useCapture'] === false) ? 'false' : 'true';
            scriptString += ');' + "\n";
  
            break;
  
  
          case ('Set_Timeout') :
          case ('Set_Interval') :
  
            scriptString += "\n";
            controlWord = (scriptArray[h]['Control'] === 'Set_Timeout') ? 'setTimeout' : 'setInterval';
            scriptString += indent(indentLevel) + controlWord + '(';
            if (scriptArray[h]['Callback_Name'] !== '') {scriptString += scriptArray[h]['Callback_Name'];}
            else {scriptString += buildScript(scriptArray[h]['Control_Function'], moduleInfo, indentLevel);}
            scriptString += ', ' + scriptArray[h]['Time'] + ');' + "\n";
  
            break;
  
  
          case ('Switch_Case') :
  
            scriptString += "\n" + indent(indentLevel) + 'switch (' + scriptArray[h]['Control_Expression'] + ')';
  
            if (scriptArray[h].hasOwnProperty('Cases')) {
  
              scriptString += ' {' + "\n\n";
              indentLevel++;
  
              for (let i = 0; i < scriptArray[h]['Cases'].length; i++) {
  
                scriptString += "\n\n" + indent(indentLevel) + 'case (';
  
                if (scriptArray[h]['Cases'][i]['Case'].length > 1) {
                
                  for (let j = 0; j < scriptArray[h]['Cases'][i]['Case'].length; j++) {
                    
                    if (j > 0) {scriptString += ' ' + scriptArray[h]['Cases'][i]['Operator'] + ' ';}
  
                    scriptString += '(' + scriptArray[h]['Cases'][i]['Case'][j] + ')';
                  }
                }
  
                else {
  
                  scriptString += scriptArray[h]['Cases'][i]['Case'][0];
                }
  
                scriptString += ') :' + "\n\n";
  
                indentLevel++;
                scriptString += buildScript(scriptArray[h]['Cases'][i]['Block'], moduleInfo, indentLevel);
                if (scriptArray[h]['Cases'][i]['Break'] === true) {scriptString += indent(indentLevel) + 'break;';}
                indentLevel--;
              }
  
              if ((scriptArray[h].hasOwnProperty('Default')) && (scriptArray[h]['Default'] !== '')) {
  
                scriptString += "\n\n" + indent(indentLevel) + 'default :' + "\n\n";
                indentLevel++;
                scriptString += indent(indentLevel) + scriptArray[h]['Default'] + ';';
                indentLevel--;
              }
  
              indentLevel--;
              scriptString += "\n" + indent(indentLevel) + '}' + "\n\n" ;
            }
  
            break;
            
  
          case ('Comment') :
  
            switch (scriptArray[h]['Comment_Type']) {
  
              case ('sameLine') : scriptString += "/// " + scriptArray[h]['Comment'][0] + "\n"; break;
              case ('singleLine') : scriptString += "\n\n" + indent(indentLevel) + "// " + scriptArray[h]['Comment'][0] + "\n\n"; break;
              case ('multiLine') : scriptString += "\n\n" + indent(indentLevel) + "/*\n\n" + indent(indentLevel) + implode("\n" + indent(indentLevel), scriptArray[h]['Comment']) + "\n\n" + indent(indentLevel) + "*/\n\n"; break;
            }
  
            break;
  
          default :
  
            scriptString += indent(indentLevel) + '// Ashiva Console: Unknown Javascript Control "' + scriptArray[h]['Control'] + '" in ' + txt(moduleInfo.name) + ' Module by ' + txt(moduleInfo.publisher);
        }
      }
  
      else {
  
        scriptString += "\n" + indent(indentLevel) + '// Ashiva Console: Javascript Control Missing in ' + txt(moduleInfo.name) + ' Module by ' + txt(moduleInfo.publisher) + "\n";
      }
  
      if (scriptArray[h].hasOwnProperty('Block')) {
  
        scriptString += ' {' + "\n\n";
        indentLevel++;
        scriptString += buildScript(scriptArray[h]['Block'], moduleInfo, indentLevel);
        indentLevel--;
        scriptString += "\n" + indent(indentLevel) + '}';
  
        if (terminationMap[indentLevel] === true) {
  
          scriptString += ';';
          terminationMap[indentLevel] = false;
        }
  
        scriptString += "\n\n";
      }
    }
  
    return scriptString;
  }
  
  
  // FUNCTION :: CREATE SCRIPT
  function createScript(scriptJSON, moduleInfo, codePanel) {
  
    let scriptArray = JSON.parse(scriptJSON);
    let scriptString = buildScript(scriptArray, moduleInfo);
  
    scriptString = scriptString.replace(/\;\s*\,/g, ",");
    scriptString = scriptString.replace(/\s*\n\s*\n(\s*\})/g, "\n$1");
    scriptString = scriptString.replace(/\n{3,}/g, "\n\n");
    scriptString = scriptString.replace(/\(\s*\n\s*\(/g, "((");
    scriptString = scriptString.replace(/\,\s*\n\s*\(/g, ", (");
    scriptString = scriptString.replace(/\}\s*\n{2}\,/g, "},");
    scriptString = scriptString.replace(/\n+\/\/\//g, " ///");
    scriptString = scriptString.replace(/\n{2}([\s]*\/\/\s{1})/g, "\n\n\n\n$1");
    scriptString = scriptString.replace(/^\s+([^\w])/g, "$1");
    scriptString = scriptString.replace(/\(\nfunction/g, "(function");
    scriptString = scriptString.replace(/\}\n\n\)/g, "})");

    if (codePanel !== true) {
    
      scriptString = scriptString.replace(/new[\s|_]Worker\(\'([^\']+)\'\,?\s?([^\)]*)\)/g, "new_Worker($2{workerName: '$1', ashivaModule: '" + url(moduleInfo.name) + "', ashivaPublisher: '" + url(moduleInfo.publisher) + "'})");
      scriptString = scriptString.replace(/new_Worker\(\{([^\}]+)\}\{([^\}]+)\}\)/g, "new_Worker({\$2, \$1})");
    }

    return scriptString;
  }
   
  return createScript(scriptJSON, moduleInfo, codePanel);
}

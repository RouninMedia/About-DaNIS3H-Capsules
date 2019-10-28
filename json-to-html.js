  //*************//
 // ESCAPE HTML //
//*************//

let escapeHtml = (text) => {
  
  const map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;', '\‘' : '&lsquo;', '\’' : '&rsquo;', '\“' : '&ldquo;', '\”' : '&rdquo;', '\«' : '&laquo;', '\»' : '&raquo;', '\‹' : '&lsaquo;', '\›' : '&rsaquo;'};

  let processedText = text.replace(/[&<>"']/g, (m) => map[m]);

  return processedText;
} 



//***************//
 // ATTACH EVENTS //
//***************//

const attachEvents = () => {

  let eventElements = document.querySelectorAll('[data-event]');

  eventElements.forEach(eventElement => {

    let eventObject = JSON.parse(eventElement.dataset.event.replace(/«|»/g, '"'));
    let eventAction = eventObject.eventAction;
    let eventActionData = (eventObject.eventActionData === Object(eventObject.eventActionData)) ? Object.values(eventObject.eventActionData) : [];
    let eventUseCapture = eventObject.eventUseCapture || false;

    eventElement.addEventListener(eventObject.eventListener, (e) => {eventActionData.unshift(e); eval(eventAction)(... eventActionData);}, eventUseCapture);
    eventElement.dataset.attachedEvent = eventElement.dataset.event;
    eventElement.removeAttribute('data-event');
  });
}

window.addEventListener('load', attachEvents, false);



  //***************//
 // RENDER MARKUP //
//***************//

const renderMarkup = (moduleMarkupJSON, moduleName, modulePublisher) => {

  let markup;
  let classList;
  let elementAttributesKeys;
  let elementAttributesValues;
  let nameSpace = url(moduleName) + '»by»' + cml(modulePublisher) + '»»»';

  const extractMarkup = (element, nameSpace) => {

    markup = '';

    for (let i = 0; i < element.length; i++) {

      if ((element[i].hasOwnProperty('element')) && (element[i]['element'] === 'attributes')) {

        elementAttributesKeys = Object.keys(element[i]['attributes']);
        elementAttributesValues = Object.values(element[i]['attributes']);

        for (let j = 0; j < elementAttributesKeys.length; j++) {

          if (elementAttributesValues[j] === elementAttributesKeys[j]) {

            markup += ' ' + elementAttributesKeys[j];
          }

          else {

            markup += ' ' + elementAttributesKeys[j] + '="' + elementAttributesValues[j] + '"';
          }
        }
      }

      else {

        if (element[i].hasOwnProperty('plainText')) {

          markup += escapeHtml(element[i]['plainText']);
        }
  
        else {

          markup += '<' + element[i]['element'];

          if (element[i].hasOwnProperty('id')) {

            id = nameSpace + element[i]['id'];
            markup += ' id="' + id + '"';
          }
    

          if (element[i].hasOwnProperty('classList')) {

            classList = nameSpace + element[i]['classList'].join(' ');
            classList = classList.replace(/ /g, ' ' + nameSpace);
            markup += ' class="' + classList + '"';
          }


          if (element[i].hasOwnProperty('attributes')) {

            elementAttributesKeys = Object.keys(element[i]['attributes']);
            elementAttributesValues = Object.values(element[i]['attributes']);

            for (let j = 0; j < elementAttributesKeys.length; j++) {

              if (elementAttributesKeys[j] === 'for') {elementAttributesValues[j] = nameSpace + elementAttributesValues[j];}

              if (elementAttributesValues[j] === elementAttributesKeys[j]) {

                markup += ' ' + elementAttributesKeys[j];
              }

              else {

                markup += ' ' + elementAttributesKeys[j] + '="' + elementAttributesValues[j] + '"';
              }
            }
          }

          if ((element[i].hasOwnProperty('self-closing')) && (element[i]['self-closing'] === true)) {

            markup += ' />'; continue;
          }

          else {

            markup += '>';        
            markup += extractMarkup(element[i]['elementChildren'], nameSpace);
            markup += '</' + element[i]['element'] + '>';
          }
        }
      }
    }

    return markup;
  }

  var moduleTemplate = document.createElement('template');
  const moduleMarkupObject = JSON.parse(moduleMarkupJSON);
  moduleTemplate.innerHTML = extractMarkup(moduleMarkupObject, nameSpace);
  document.body.appendChild(moduleTemplate.content);

  attachEvents();
}

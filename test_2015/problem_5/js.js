window.onload = function() {
    var buttonPrzycisk = document.getElementById('przycisk');

    buttonPrzycisk.onmouseover = function() {
        var czerwonyElements = document.getElementsByClassName('czerwony');

        for(var i = 0; i < czerwonyElements.length; i++) {
            var el = czerwonyElements[i],
                elTagName = el.tagName;

            if(elTagName === 'div') {
                el.style.backgroundColor = 'red';
            }
        }
    };

    buttonPrzycisk.onclick = function() {
        var pKomunikat = document.getElementById('komunikat'),
            textNode = document.createTextNode('START');

        while(pKomunikat.firstChild) {
            pKomunikat.removeChild(pKomunikat.firstChild);
        }

        pKomunikat.appendChild(textNode);
    };
};
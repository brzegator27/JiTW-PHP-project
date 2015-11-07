inicializeCSSList = function() {
    var body = document.body,
            bodyFirstChild = body.firstChild,
            CSSLinkTags = getCSSLinkTags(),
            stylesList = generateCSSList(CSSLinkTags);

    inicializeCSSStyles();
    body.insertBefore(stylesList, bodyFirstChild);
};

inicializeCSSStyles = function() {
    var activeStyleFromCookie = getStylesCookie();
    
    if(activeStyleFromCookie !== '') {
        setTimeout(function() {
            manageStylesChange(activeStyleFromCookie);
        }, 0);    
//        manageStylesChange(activeStyleFromCookie);
    }
};

getCSSLinkTags = function() {
    var head = document.head,
            linkTags = head.getElementsByTagName('link'),
            linkRelAttribute,
            linkQualifies;
            
    for(var i = 0; i < linkTags.lenght; i++) {
        linkRelAttribute = linkTags[i].getAttribute('rel');
        linkQualifies = linkRelAttribute.indexOf('style') !== -1;
        
        if(!linkQualifies) {
            linkTags.splice(i, 1);
            i--;
        }
    }
    
    return linkTags;
};

generateCSSList = function(links) {
    var newList = document.createElement('ul'),
            newListNode,
            newListNodeTitle,
            linkTitle;
    
    for(var i = 0; i < links.length; i++) {
        linkTitle = links[i].title;
        newListNode = document.createElement('li');
        newListNodeTitle = document.createTextNode(linkTitle);
        
        newListNode.onclick = (function(linkTitle) {
            return function() {
                manageStylesChange(linkTitle);
            };
        })(linkTitle);
        
        newListNode.appendChild(newListNodeTitle);
        newList.appendChild(newListNode);
    }
    
    return newList;
};

manageStylesChange = function(newActiveStyleTitle) {
    var links = getCSSLinkTags(),
            linkTitle;
    
    for(var i = 0; i < links.length; i++) {
        linkTitle = links[i].title;
        if(linkTitle === newActiveStyleTitle) {
            links[i].disabled = false;
        } else {
            links[i].disabled = true;
        }
    }
    setStylesCookie(newActiveStyleTitle);
};

setStylesCookie = function(newActiveStyleTitle) {
    document.cookie = 'styles=' + newActiveStyleTitle + ';path=/jitw-php-agh';
};

getStylesCookie = function() {
    return getCookie('styles');
};

getCookie = function(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
};

//document.body.onload = function() {
//    inicializeCSSStyles();
//};
//
//document.addEventListener("DOMContentLoaded", function(event) {
////    console.log("DOM fully loaded and parsed");
//    inicializeCSSStyles();
//});

inicializeCSSStyles();
var liveCommentModuleIsActive = false;

generateNewMessageDOMElement = function(commentData, timestamp) {
    var divElement = document.createElement('div'),
            splittedText = commentData.text.split('<br/>'),
            messageNode = document.createTextNode(commentData.username + ': '),
            textNode = null,
            linebreak = null;
    
    divElement.timestamp = timestamp;
    divElement.appendChild(messageNode);
    
    for(var i = 0; i < splittedText.length; i++) {
        textNode = document.createTextNode(splittedText[i]);
        divElement.appendChild(textNode);
//        linebreak = document.createElement('br');
//        divElement.appendChild(linebreak);
    }
    
    return divElement;
};

generateTimestamp = function(year, month, day, hours, minutes, seconds, uniqueNumber) {
    var uniqueNumberProper = uniqueNumber !== undefined ? uniqueNumber : '000',
            timestampString = '' + year + month + day + hours + minutes + seconds + uniqueNumberProper,
            timestampInteger = parseInt(timestampString);
    
    return timestampInteger;
};

getCurrentDateAndTimeData = function() {
    var date = new Date(),
        year = date.getYear() + 1900,
        month = date.getMonth() + 1 < 10 ? '0' + date.getMonth() + 1 : date.getMonth() + 1,
        day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
        hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours(),
        minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(),
        seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds();

    return {
        year: year,
        month: month,
        day: day,
        hours: hours,
        minutes: minutes,
        seconds: seconds
    };
};

generateCurrentTimestamp = function() {
    var currentDateAndTime = getCurrentDateAndTimeData();
    
    return generateTimestamp(
        currentDateAndTime.year,
        currentDateAndTime.month,
        currentDateAndTime.day,
        currentDateAndTime.hours,
        currentDateAndTime.minutes,
        currentDateAndTime.seconds
    );
};

manageLiveCommentState = function(checkbox) {
    var checkboxStatus = checkbox.checked,
            sendMessageButton = document.getElementById('live-comment-send-button');
    
    if(checkboxStatus) {
        updateLiveComments();
    }
    
    liveCommentModuleIsActive = checkboxStatus;
    sendMessageButton.disabled = !checkboxStatus;
};

getLiveCommentMessageData = function() {
    var usernameField = document.getElementById('live-comment-username'),
            textField = document.getElementById('live-comment-text');
    
    return {
        username: usernameField.value,
        text: textField.value
    };
};

sendLiveComment = function(timestamp) {
    var http = new XMLHttpRequest(),
            url = liveCommentUrlSend,
            messageData = getLiveCommentMessageData(),
            data = new FormData();
    
    data.append('username', messageData.username);
    data.append('text', messageData.text);
    data.append('blog_name', blogName);
    data.append('timestamp', timestamp);

    http.open('POST', url, true);

    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            console.log(http.responseText);
        }
    };
    
    http.send(data);
};

manageNewLiveComment = function(button) {
    var commentData = getLiveCommentMessageData(),
            newCommentDOMObj = generateNewMessageDOMElement(commentData, timestamp),
            timestamp = generateCurrentTimestamp();
    
    sendLiveComment(timestamp);
    insertNewComment(newCommentDOMObj, timestamp);
};

insertNewComment = function(commentObj, timestamp) {
    var liveCommentBox = document.getElementById('live-comment-box'),
            comments = liveCommentBox.childNodes;
    
    for(var i = 0; i < comments.length; i++) {
        if(parseInt(comments[i].timestamp) === timestamp && comments[i].nodeName === 'DIV') {
            break;
        }
        if(parseInt(comments[i].timestamp) > timestamp && comments[i].nodeName === 'DIV') {
            liveCommentBox.insertBefore(commentObj, comments[i]);
            break;
        }
    }
};

pullLiveCommentsFromServer = function() {
    var http = new XMLHttpRequest(),
            url = liveCommentUrlPull,
            data = new FormData();
    
    data.append('blog_name', blogName);
//    data.append('timestamp', timestamp);

    http.open('POST', url, true);

    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            var responseObj = JSON.parse(http.responseText),
                    responseData = responseObj.data,
                    newCommentDOMObj = null,
                    i = 0;
            
            while(i in responseData) {
                newCommentDOMObj = generateNewMessageDOMElement({
                    username: responseData[i].username,
                    text: responseData[i].text
                }, responseData[i].timestamp);
                
                insertNewComment(newCommentDOMObj, responseData[i].timestamp);
                i++;
            }
        }
    };
    
    http.send(data);
};

updateLiveComments = function() {
    pullLiveCommentsFromServer();
};


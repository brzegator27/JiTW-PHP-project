<form method="post" 
      action="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE . '/entry/add_entry'; ?>" 
      name="entry_form" 
      enctype="multipart/form-data" 
      id="entry-form"
      onsubmit="onFormSubmit()">
    Tytuł: <input type="text" name="entry_title" /><br/>
    Tekst: <textarea name="entry" cols="40" rows="5"></textarea><br/>
    Nazwa użytkownika: <input type="text" name="user_name" /><br/>
    Hasło: <input type="password" name="password" /><br/>
    Data wpisu: <input 
        type="text" 
        name="entry_date"
        onkeyup="onDateFieldUpdate()"
        onblur="onBlur()"/><br/>
    Godzina wpisu: <input 
        type="text" 
        name="entry_hour"
        onkeyup="onHourFieldUpdate()"
        onblur="onBlur()"/><br/>
    Plik : <input type="file" name="file_1" change="addFileField" id="first-input-file-field"><br/>
    <button type="button" onclick="addFileField(this)" id="new-file-button">Nowy plik</button><br/><br/>
    <input type="hidden" name="date" /><br/>
    <button type="reset" value="Wyczyść">Wyczyść</button><br/>
    <input type="submit" name="submit" value="Wyślij" />
</form>

<script src="../application/views/js/js.js"></script>
<script>
    var lastFileFieldIndex = 1,
        emptyFileInputs = 1;
    
    addFileField = function(event) {
        var newFileFieldButtonId = 'new-file-button',
                newFileFieldName = 'file_' + ++lastFileFieldIndex,
                form = document.getElementById('entry-form'),
                newFileFieldButton = document.getElementById(newFileFieldButtonId),
                fileTextNode = document.createTextNode('Plik : '),
                newFileField = document.createElement('input'),
                linebreak = document.createElement('br'),
                target = event.target,
                targetType = target ? target.type : null,
                targetValue = target ? target.value : null;
        
        if(targetType === 'file' && !targetValue) {
            var textLabelElement = target.previousSibling,
                brElement = target.nextSibling;
            
            if(emptyFileInputs > 1) {
                textLabelElement.remove();
                target.remove();
                brElement.remove();
                emptyFileInputs--;
            }
            
            return;
        }
        
        newFileField.setAttribute('type', 'file');
        newFileField.setAttribute('name', newFileFieldName);
        
        newFileField.addEventListener('change', addFileField);
        
        form.insertBefore(fileTextNode, newFileFieldButton);
        form.insertBefore(newFileField, newFileFieldButton);
        form.insertBefore(linebreak, newFileFieldButton);
        
        emptyFileInputs++;
    };
    
    var firstFileInput = document.getElementById("first-input-file-field");
    firstFileInput.addEventListener('change', addFileField);
    
    setCurrentDate = function() {
        var form = document.getElementById('entry-form'),
                entryDateField = form.entry_date,
                entryTimeField = form.entry_hour,
                dateData = getDateData();
    
        entryDateField.value = dateData.year + '-' + dateData.month + '-' + dateData.day;
        entryTimeField.value = dateData.hours + ':' + dateData.minutes;
    };
    
    inicializeForm = function() {
        setCurrentDate();
    };
    
    getDateData = function() {
//    RRRRMMDDGGmmSSUU gdzie: R - rok, M - miesiąc, D - dzień, G - godzina, m - minuta
        var date = new Date(),
            year = date.getYear() + 1900,
            month = date.getMonth() + 1 < 10 ? '' + date.getMonth() + 1 : date.getMonth() + 1,
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours(),
            minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
    
        return {
            year: year,
            month: month,
            day: day,
            hours: hours,
            minutes: minutes
        };
    };
    
    isLeapYear = function(year) {
        return ((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0);
    };
    
    checkDateFieldCorrectness = function() {
        var form = document.getElementById('entry-form'),
                entryDateField = form.entry_date,
                entryDateFieldValue = entryDateField.value,
                entryDateFieldValueLenght = entryDateFieldValue.length,
                entryDateFieldYearValue = parseInt(entryDateFieldValue.substr(0, 4)),
                entryDateFieldMonthValue = parseInt(entryDateFieldValue.substr(5, 2)),
                entryDateFieldDayValue = parseInt(entryDateFieldValue.substr(8, 2)),
                isleapYear = isLeapYear(entryDateFieldYearValue),
                isYearCorrect = entryDateFieldYearValue > 2000 && entryDateFieldYearValue < 9999,
                isMonthCorrect = entryDateFieldMonthValue >= 1 && entryDateFieldMonthValue <= 12,
                isDayCorrect = entryDateFieldDayValue >= 1 && entryDateFieldDayValue <= (isleapYear ? 29 : 28),
                isFieldValueLenghtCorrect = entryDateFieldValueLenght === 10,
                arePausesInPlace = entryDateFieldValue.substr(4, 1) === '-' && entryDateFieldValue.substr(7, 1) === '-';

        return isYearCorrect && isMonthCorrect && isDayCorrect && isFieldValueLenghtCorrect && arePausesInPlace;
    };
    
    checkHourFieldCorrectness = function() {
        var form = document.getElementById('entry-form'),
                entryTimeField = form.entry_hour,
                entryTimeFieldValue = entryTimeField.value,
                entryTimeFieldValueLenght = entryTimeFieldValue.length,
                entryTimeeFieldHoursValue = parseInt(entryTimeFieldValue.substr(0, 2)),
                entryTimeFieldMinutesValue = parseInt(entryTimeFieldValue.substr(3, 2)),
                areHoursCorrect = entryTimeeFieldHoursValue >= 0 && entryTimeeFieldHoursValue <= 24,
                areMinutesCorrect = entryTimeFieldMinutesValue >= 0 && entryTimeFieldMinutesValue <= (entryTimeeFieldHoursValue === 24 ? 0 : 59),
                isFieldValueLenghtCorrect = entryTimeFieldValueLenght === 5,
                isColonInPlace = entryTimeFieldValue.substr(2, 1) === ':';
        
        return areHoursCorrect && areMinutesCorrect && isFieldValueLenghtCorrect && isColonInPlace;
    };
    
    updateSubmitButton = function() {
        var form = document.getElementById('entry-form'),
                submitButton = form.submit,
                isDateFieldCorrect = checkDateFieldCorrectness(),
                isHourFieldCorrect = checkHourFieldCorrectness();
        
        submitButton.disabled = !isDateFieldCorrect || !isHourFieldCorrect;
    };
    
    onDateFieldUpdate = function() {
        updateSubmitButton();
    };
    
    onHourFieldUpdate = function() {
        updateSubmitButton();
    };
    
    onFormSubmit = function() {
        var form = document.getElementById('entry-form'),
            entryDateField = form.entry_date,
            entryDateFieldValue = entryDateField.value,
            entryDateFieldYearValue = entryDateFieldValue.substr(0, 4),
            entryDateFieldMonthValue = entryDateFieldValue.substr(5, 2),
            entryDateFieldDayValue = entryDateFieldValue.substr(8, 2),
            entryTimeField = form.entry_hour,
            entryTimeFieldValue = entryTimeField.value,
            entryTimeFieldHoursValue = entryTimeFieldValue.substr(0, 2),
            entryTimeFieldMinutesValue = entryTimeFieldValue.substr(3, 2),
            hiddenFullDateField = form.date;
    
        entryDateField.disabled = true;
        entryTimeField.disabled = true;
        hiddenFullDateField.value = '' + entryDateFieldYearValue + entryDateFieldMonthValue + entryDateFieldDayValue + entryTimeFieldHoursValue + entryTimeFieldMinutesValue;
    };
    
    onBlur = function() {
        var isDateFieldCorrect = checkDateFieldCorrectness(),
                isHourFieldCorrect = checkHourFieldCorrectness();
        
        if(!isDateFieldCorrect || !isHourFieldCorrect) {
            setCurrentDate();
        }
    };
    
    inicializeForm();
    
</script>
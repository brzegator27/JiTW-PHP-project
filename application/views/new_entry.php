<form method="post" 
      action="<?= Config::URL_BASE . '/entry/add_entry'; ?>" 
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
        onkeyup="onDateFieldUpdate()"/><br/>
    Godzina wpisu: <input 
        type="text" 
        name="entry_hour"
        onkeyup="onHourFieldUpdate()"/><br/>
    Plik 1: <input type="file" name="file_1"><br/>
    Plik 2: <input type="file" name="file_2"><br/>
    Plik 3: <input type="file" name="file_3"><br/>
    <input type="hidden" name="date" /><br/>
    <input type="submit" name="submit" value="Wyślij" />
</form>

<script src="../application/views/js/js.js"></script>
<script>
    inicializeForm = function() {
        var form = document.getElementById('entry-form'),
                entryDateField = form.entry_date,
                entryHourField = form.entry_hour,
                dateData = getDateData();
    
        entryDateField.value = dateData.year + '-' + dateData.month + '-' + dateData.day;
        entryHourField.value = dateData.hours + ':' + dateData.minutes;
    };
    
    getDateData = function() {
//    RRRRMMDDGGmmSSUU gdzie: R - rok, M - miesiąc, D - dzień, G - godzina, m - minuta
        var date = new Date(),
            year = date.getYear() + 1900,
            month = date.getMonth() + 1 < 10 ? '0' + date.getMonth() + 1 : date.getMonth() + 1,
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
                isDayCorrect = entryDateFieldDayValue >= 1 && entryDateFieldDayValue <= (isleapYear ? 28 : 29),
                isFieldValueLenghtCorrect = entryDateFieldValueLenght === 10;

        return isYearCorrect && isMonthCorrect && isDayCorrect && isFieldValueLenghtCorrect;
    };
    
    checkHourFieldCorrectness = function() {
        var form = document.getElementById('entry-form'),
                entryHourField = form.entry_hour,
                entryHourFieldValue = entryHourField.value,
                entryHourFieldValueLenght = entryHourFieldValue.length,
                entryHoureFieldHoursValue = parseInt(entryHourFieldValue.substr(0, 2)),
                entryHourFieldMinutesValue = parseInt(entryHourFieldValue.substr(3, 2)),
                areHoursCorrect = entryHoureFieldHoursValue >= 0 && entryHoureFieldHoursValue <= 24,
                areMinutesCorrect = entryHourFieldMinutesValue >= 0 && entryHourFieldMinutesValue <= 59,
                isFieldValueLenghtCorrect = entryHourFieldValueLenght === 5;
        
        return areHoursCorrect && areMinutesCorrect && isFieldValueLenghtCorrect;
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
            entryHourField = form.entry_hour,
            entryHourFieldValue = entryHourField.value,
            entryHourFieldHoursValue = entryHourFieldValue.substr(0, 2),
            entryHourFieldMinutesValue = entryHourFieldValue.substr(3, 2),
            hiddenFullDateField = form.date;
    
        entryDateField.disabled = true;
        entryHourField.disabled = true;
        hiddenFullDateField.value = '' + entryDateFieldYearValue + entryDateFieldMonthValue + entryDateFieldDayValue + entryHourFieldHoursValue + entryHourFieldMinutesValue;
    };
    
    inicializeForm();
    
</script>
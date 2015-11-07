inicialiasdasdzeForm = function() {
        var form = document.getElementById('entry-form'),
                entryDateField = form.entry_date,
                entryHourField = form.entry_hour,
                dateData = getDateData();
    
        entryDateField.value = dateData.year + '-' + dateData.month + '-' + dateData.day;
        entryHourField.value = dateData.hours + ':' + dateData.minutes;
    };
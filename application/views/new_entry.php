<form method="post" action="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE . '/entry/add_entry'; ?>" name="entry_form" enctype="multipart/form-data">
    Tytuł: <input type="text" name="entry_title" /><br/>
    Tekst: <textarea name="entry" cols="40" rows="5"></textarea><br/>
    Nazwa użytkownika: <input type="text" name="user_name" /><br/>
    Hasło: <input type="password" name="password" /><br/>
    Plik 1: <input type="file" name="file_1"><br/>
    Plik 2: <input type="file" name="file_2"><br/>
    Plik 3: <input type="file" name="file_3"><br/>
    <input type="hidden" name="date" /><br/>
    <button type="reset" value="Wyczyść">Wyczyść</button><br/>
    <input type="submit" name="submit" value="Wyślij" />
</form>

<script>
//    RRRRMMDDGGmmSSUU gdzie: R - rok, M - miesiąc, D - dzień, G - godzina, m - minuta
    var date = new Date(),
            year = date.getYear() + 1900,
            month = date.getMonth() + 1 < 10 ? '0' + date.getMonth() + 1 : date.getMonth() + 1,
            day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
            hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours(),
            minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes(),
            formattedDate = '' + year + month + day + hours + minutes;
    
    document.entry_form.date.value = formattedDate;
</script>
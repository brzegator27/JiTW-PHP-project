<form method="post" action="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE . '/comment/add_comment'; ?>">
    <select name="type">
        <option value="0">Negatywny</option>
        <option value="1">Pozytywny</option>
        <option value="2">Neutralny</option>
    </select><br/>
    Nickname: <input type="text" name="nickname" /><br/>
    Comment: <textarea name="content" cols="40" rows="5"></textarea><br/>
    <input type="hidden" name="blog_name" value="<?= $viewData['blog_name'] ?>"/><br/>
    <input type="hidden" name="entry_id" value="<?= $viewData['entry_id'] ?>"/><br/>
    <button type="reset" value="Wyczyść">Wyczyść</button><br/>
    <input type="submit" name="submit" value="Wyślij" />
</form>
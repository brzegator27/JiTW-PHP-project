<form method="post" action="<?= Config::URL_BASE . '/' . Config::INDEX_PAGE . '/blog/add_blog'; ?>">
    Nazwa bloga: <input type="text" name="blog_name" /><br/>
    Nazwa użytkownika: <input type="text" name="user_name" /><br/>
    Hasło: <input type="password" name="password" /><br/>
    Opis bloga: <textarea name="blog_description" cols="40" rows="5"></textarea><br/>
    <button type="reset" value="Wyczyść">Wyczyść</button><br/>
    <input type="submit" name="submit" value="Wyślij" />
</form>
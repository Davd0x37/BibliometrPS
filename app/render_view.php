<?php
function render_edit_user(string $action, string $method, array $values = null)
{
    ?>
    <form action="<?= $action ?>" method="<?= $method ?>" class="container">
        <div class="container__box fieldset__container">
            <p><label for="name">Imie:</label>
                <input type="text" name="name" id="name" value="<?= $values ? (array_key_exists('name', $values) ? $values['name'] : "") : "" ?>"></p>
            <p><label for="mail">Email:</label>
                <input type="email" name="mail" id="mail" value="<?= $values ? (array_key_exists('mail', $values) ? $values['mail'] : "") : "" ?>"></p>
            <p><label for="afiliacja">Afiliacja:</label>
                <input type="text" name="afiliacja" id="afiliacja" value="<?= $values ? (array_key_exists('afiliacja', $values) ? $values['afiliacja'] : "") : "" ?>"></p>
            <p><label for="password">Hasło</label>
                <input type="password" name="password" id="password" value="<?= $values ? (array_key_exists('password', $values) ? $values['password'] : "") : "" ?>"></p>
            <p><label for="role">Rola:</label>
                <select name="role" id="role">
                    <option value="user" <?= $values ? (array_key_exists('role', $values) ? ($values['role'] === "user" ? "selected" : "") : "") : "selected" ?>>Użytkownik</option>
                    <option value="admin" <?= $values ? (array_key_exists('role', $values) ? ($values['role'] === "admin" ? "selected" : "") : "") : "" ?>>Administrator</option>
                </select>
            </p>
            <input type="submit" class="button_action" value="Edytuj">
        </div>
    </form>
<?php
}
?>
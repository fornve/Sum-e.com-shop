<div class="post">
    <h2 class="title">Page not found!</h2>
    <p>Ooops... this page does not exist on this server. This is common 404 error.</p>

    <table>
        <tr><th>Key</th><th>Value</th></tr>
    {foreach from=$smarty.server key=key item=value}
        <tr><td>{$key}</td><td>{$value}</td></tr>
    {/foreach}
    </table>
</div>
<h2>Basket debug</h2>

<table>
{foreach from=$smarty.session.basket->items item=item key=item_key}
    {foreach from=$item item=variant key=variant_key}
    <tr>
        <th>{$item_key} - {$variant_key}</th>
        <td>{$variant.quantity}</td>
        <td>{$variant.item_value}</td>
    </tr>
    {/foreach}
{/foreach}
</table>

<a href="/Basket/Wipe">Wipe basket</a>
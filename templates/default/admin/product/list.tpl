<div class="post basket">
    <h2 class="title">Products list</h2>

    <table>

        <thead>
            <tr class="header">
                <th>ID</th>
                <th></th>
                <th>Name / Category</th>
                <th>Available / Sold</th>
                <th>Visits *</th>
                <th>Price</th>
                <th>Status</th>
                <th style="width: 60px;">Action</th>
            </tr>
        </thead>

        <tbody>
        {foreach from=$products item=product name=productloop}
            <tr class="item">
                <td>{$product->id}</td>
                <td>
                    {assign var=image value=$product->GetMainImage()}
                    {if $image->id}
                        <img style="padding: 0;" src="/Product/Image/24x24/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
                    {/if}
                </td>
                <td>
					<a href="/Product/View/{$product->id}">{$product->name}</a>
						<br />
					<span class="quiet">{$product->category}</span></td>
				<td>{$product->quantity+0} / {$product->sold+0}</td>
                <td>{$product->visited}</td>
                <td>{$smarty.const.CURRENCY_SIGN}{$product->price}</td>
                <td style="text-align: center;">
					{if $product->status}
						<a href="/ProductAdmin/ProductList/?disable={$product->id}" title="Enabled{if $product->quantity<1} - but stock quantity &lt; 1{/if}"><img src="http://sunforum.co.uk/resources/icons/mini/flag_{if $product->quantity}green{else}orange{/if}.gif" alt="Available" /></a>
					{else}
						<a href="/ProductAdmin/ProductList/?enable={$product->id}" title="Disabled"><img src="http://sunforum.co.uk/resources/icons/mini/flag_red.gif" alt="Not available" /></a>
					{/if}
				</td>
				<td>
					<a href="/ProductAdmin/Edit/{$product->id}" title="Edit"><img src="http://sunforum.co.uk/resources/icons/silk/application_edit.png" alt="Edit" /></a>
					<a href="/Product/View/{$product->id}" title="View"><img src="http://sunforum.co.uk/resources/icons/silk/application_go.png" alt="View" /></a>
					<a href="/ProductAdmin/Delete/{$product->id}" onclick="return confirm('Do you really want to delete product: {$product->name}?')" alt="Delete"><img src="http://sunforum.co.uk/resources/icons/silk/application_delete.png" title="Delete" /></a>
				</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <p><sup>*)</sup><span>To refresh <a href="/SettingsAdmin/FlushCache/">flush cache</a></span></p>
</div>

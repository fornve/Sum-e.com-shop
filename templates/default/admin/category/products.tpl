<div class="post">
    <h2 class="title">Products in {$category->name}</h2>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th></th>
                <th>Name / Category</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        {foreach from=$products item=product name=productloop}
            <tr>
                <td>{$product->id}</td>
                <td>
                    {assign var=image value=$product->GetMainImage()}
                    {if $image->id}
                        <img style="padding: 0;" src="/Product/Image/24x24/{$image->id}/{$image->GetFilename()}" title="{$image->title}" />
                    {/if}
                </td>
                <td>{$product->name}<br /><span class="quiet">{$product->category}</span></td>
                <td><a href="/ProductAdmin/Edit/{$product->id}">Edit</a><span> / </span><a href="/Product/View/{$product->id}">View</a> / </span><a href="/ProductAdmin/UnassignFromCategory/{$product->id}/{$category->id}">Unassign</a></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
	<p><a href="/CategoryAdmin/UnassignAllProducts/{$category->id}" onclick="return confirm('Do you really want to unassign all product from this category?');">Unassign all products from this category</a></p>
    <p><sup>*)</sup><span>To refresh <a href="/SettingsAdmin/FlushCache/">flush cache</a></span></p>
</div>
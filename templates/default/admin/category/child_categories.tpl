<div class="post">
    <h2 class="title">Categories in {$category->name}</h2>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
        {foreach from=$categories item=kid_category}
            <tr>
                <td>{$kid_category->id}</td>
                <td>{$kid_category->name}<br /><span class="quiet">{$product->category}</span></td>

                <td><a href="/CategoryAdmin/Edit/{$kid_category->id}">Edit</a><span> / </span><a href="/Category/Index/{$kid_category->id}">View</a></td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <p><sup>*)</sup><span>To refresh <a href="/SettingsAdmin/FlushCache/">flush cache</a></span></p>
</div>

<div class="post">
    <h2 class="title">{if $shipping}Shipping '{$shipping->name}' editing{else}New shipping method{/if}</h2>

    <form action="/ShippingAdmin/Edit/{$shipping->id}" method="post" enctype="multipart/form-data">

        <br />
        <label>Name:</label>
        <br />
        <input class="title" type="text" name="name" value="{$shipping->name}" maxlength="255" />

        <br />
        <label>Flat value [{$smarty.const.CURRENCY_SIGN}]</label>
        <br />
        <input class="text" type="text" name="flat_value" value="{$shipping->flat_value}" maxlength="10" />

        <br />
        <label>Limit price [{$smarty.const.CURRENCY_SIGN}]</label>
		<p class="quiet" style="margin-bottom: 0;">If order reaches this value, shipping is free</p>
        <input class="text" type="text" name="limit_price" value="{$shipping->limit_price}" maxlength="10" />

        <br />

		<div>
			<strong>Enabled: </strong>
			<label for="enabled_yes">Yes</label>
			<input id="enabled_yes" type="radio" name="enabled" value="1" {if $shipping->enabled}checked="checked"{/if} />
			&nbsp;&nbsp;
			<label for="enabled_no">No</label>
			<input id="enabled_no" type="radio" name="enabled" value="0" {if !$shipping->enabled}checked="checked"{/if} />
		</div>

        <br />
        <label>Description:</label>
        <br />
        <textarea cols="80" rows="10" name="description">{$shipping->description}</textarea>


        <input style="float: right; clear: both;" type="image" src="/resources/images/button_save.png" value="Save" />

		<div style="clear: both;"></div>
    </form>
</div>

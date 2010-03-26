<div class="post">
	<h2 class="title">Advanced search</h2>

	<div class="post_content">
		<form method="get" action="/Search/Results">

			<table class="datatable">
				<tr>
					<th>Searched text</th>
				</tr>

				<tr>
					<td>
						<div style="padding-left: 36px;">
							<input type="text" class="text" name="q" value="{$smarty.get.q}" maxlength="255" />
						</div>
					</td>
				</tr>

				<tr>
					<th>Results type</th>
				</tr>

				<tr>
					<td>
						<div style="padding-left: 36px;">
							<select name="type">
								<option value="">Products and categories</option>
								<option value="products" {if $smarty.get.type=='products'} selected="selected"{/if}>Products</option>
								<option value="categories" {if $smarty.get.type=='categories'} selected="selected"{/if}>Categories</option>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<th>Price range</th>
				</tr>

				<tr>
					<td>
						<div style="padding-left: 36px;">
							{foreach from=$price_ranges item=range name=price_range}
							<div>
								<input id="pricerange_{$smarty.foreach.price_range.index}" value="{$range.id}" type="checkbox" checked="checked" name="pricerange_{$smarty.foreach.price_range.index+1}" />
								<label for="pricerange_{$smarty.foreach.price_range.index}">{$range.name}</label>
							</div>
							{/foreach}
						</div>
					</td>
				</tr>

				<tr>
					<th>Category</th>
				</tr>

				<tr>
					<td>
						<div style="padding-left: 36px;">

							<ul style="list-style-type: none; madding-left: 0; margin-left: 0;">
							  {foreach from=$category_tree item=category}
								<li>
									<input type="checkbox" name="category_{$category->id}" value="{$category->id}" checked="checked" />
									<span onclick="$('#kid_{$category->id}').toggle('fast')">
										{if $category->kids}
											<img src="/resources/icons/silk/bullet_toggle_plus.png" alt="Expand/Collapse category">
										{else}
											<img src="/resources/icons/silk/bullet_white.png" />
										{/if}
										{$category->name}
									</span>


								{if $category->kids > 0}
									<ul id="kid_{$category->id}" class="category_tree_kid" {if $product}{if $product->InBranch($category->id)}style="display: block;"{/if}{/if}>
									{foreach from=$category->kids item=kid}
										<li>
											<input type="checkbox" name="category_{$kid->id}" value="{$kid->id}" checked="checked" />
											
											{assign var=kids2 value=$kid->LevelCollection($kid->id)}
											
											<span onclick="$('#kid2_{$kid->id}').toggle('fast')">
												{if $kids2}
													<img src="/resources/icons/silk/bullet_toggle_plus.png" alt="Expand/Collapse category">
												{else}
													<img src="/resources/icons/silk/bullet_white.png" />
												{/if}
												{$kid->name}
											</span>

											{if $kids2}
												<ul id="kid2_{$kid->id}" class="category_tree_kid" {if $product}{if $product->InBranch($kid->id)}style="display: block;"{/if}{/if}>
												
												{foreach from=$kids2 item=kid2}
													{assign var=kids3 value=$kid2->LevelCollection($kid2->id)}
													<li>
														<input type="checkbox" name="category_{$kid2->id}" value="{$kid2->id}" checked="checked" />
														
														<span onclick="$('#kid3_{$kid2->id}').toggle('fast')">
															{if $kids3}
																<img src="/resources/icons/silk/bullet_toggle_plus.png" alt="Expand/Collapse category">
															{else}
																<img src="/resources/icons/silk/bullet_white.png" />
															{/if}
															{$kid2->name}
														</span>
														
														{if $kids3}
															<ul id="kid3_{$kid2->id}" class="category_tree_kid" {if $product}{if $product->InBranch($kid2->id)}style="display: block;"{/if}{/if}>
															{foreach from=$kids3 item=kid3}
																{assign var=kids4 value=$kid3->LevelCollection($kid3->id)}
																<li>
																	<input type="checkbox" name="category_{$kid3->id}" value="{$kid3->id}" {if $product}{if $product->InCategory($kid3->id)}checked="checked"{/if}{/if} />
																	
																	<span onclick="$('#kid4_{$kid3->id}').toggle('fast')">
																		{if $kids4}
																			<img src="/resources/icons/silk/bullet_toggle_plus.png" alt="Expand/Collapse category">
																		{else}
																			<img src="/resources/icons/silk/bullet_white.png" />
																		{/if}
																		{$kid3->name}
																	</span>
																	
																	
																</li>
															{/foreach}
															</ul>
														{/if}
														
													</li>
												{/foreach}
												</ul>
											{/if}
											
										</li>
									{/foreach}
									</ul>
								{/if}
								</li>
							{/foreach}
							</ul>
						</div>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<input type="submit" value="Search!" />
					</td>
				</tr>

			</table>

		</form>
	</div>

</div>

{if !$smarty.session.basket}
	Your basket is empty.
{else}
	{assign var=basket value=$smarty.session.basket}
	{assign var=total value=$basket->GetTotals()}
	<a href="/Basket/View" title="View basket">You have {$total.quantity} item{if $total.quantity>1}s{/if}. 
	{$smarty.const.CURRENCY_SIGN}{$total.value*$vat_multiply|string_format:"%.2f"}
	</a>
{/if}

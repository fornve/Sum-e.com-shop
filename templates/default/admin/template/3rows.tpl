<script type="text/javascript">
	{literal}



	{/literal}
</script>

<div id="canvas" class="snap" style="border: 1px solid #808080; position: relative; width: 1020px; height: 600px; background: #eff url('/resources/images/960_grid_12_col.png') top left repeat-y;">
	 <div id="header" class="snap" style="border: 1px dashed #a0a0a0; width: 958px; height: 98px;">header</div>
	 <div id="row1" class="snap" style="border: 1px dashed #a0a0a0; width: 198px; height: 398px; float: left;">row1</div>
	 <div id="row2" class="snap" style="border: 1px dashed #a0a0a0; width: 558px; height: 398px; float: left;">row2</div>
	 <div id="logo" class="snap" style="border: 1px dashed #a0ffa0; width: 100px; height: 20px; position absolute; top: 10; left: 10;">logo</div>
	 <div id="row3" class="snap" style="border: 1px dashed #a0a0a0; width: 198px; height: 398px; float: left;">row3</div>
	 <div id="footer" class="snap" style="border: 1px dashed #a0a0a0; width: 958px; height: 98px; clear: both;">Footer</div>


</div>



<script type="text/javascript">
	{literal}

	$(function()
	{
		$('#header').draggable({ containment: '#canvas', snap: '.snap' }).resizable();
		$('#row1').draggable({ containmen: '#canvas', snap: '.snap' }).resizable();
		$('#row2').draggable({ containmen: '#canvas', snap: '.snap' }).resizable();
		$('#row3').draggable({ containmen: '#canvas', snap: '.snap' }).resizable();
		$('#footer').draggable({ containmen: '#canvas', snap: '.snap' }).resizable();
	});

	{/literal}
</script>

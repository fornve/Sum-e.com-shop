<div class="post">

	<h2 class="title">Contact us</h2>

	<div class="post_content">
		<form action="/Contactus/Send/" method="POST">

			<div>
				<label for="from" style="display: block; float: left; width: 150px;">From (email)</label>
				<input type="text" style="width: 380px;" name="from" id="from" value="{$smarty.post.from}" maxlength="255" class="textinput" />
			</div>

			<div>
				<label for="subject" style="display: block; float: left; width: 150px;">Subject</label>
				<input style="width: 400px;" type="text" name="subject" id="subject" value="{$smarty.post.subject}" maxlength="255" class="textinput" />
			</div>

			<div>
				<label for="message">Message</label>
				<br />
				<textarea name="message" style="height: 200px; width: 600px;">{$smarty.post.message}</textarea>
			</div>

			<div>
				<input type="submit" value="Send" />
			</div>

		</form>
	</div>
</div>

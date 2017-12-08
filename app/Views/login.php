<form class="form-horizontal" method="post" action="/main/login">
	<div class="form-group">
		<label for="username" class="cols-sm-2 control-label">Username</label>
		<div class="cols-sm-10">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
				<input type="text" class="form-control" name="username" id="username"  placeholder="Enter your Username"/>
			</div>
		</div>
	</div>

	<div class="form-group">
		<label for="password" class="cols-sm-2 control-label">Password</label>
		<div class="cols-sm-10">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
				<input type="password" class="form-control" name="password" id="password"  placeholder="Enter your Password"/>
			</div>
		</div>
	</div>

	<div class="form-group ">
		<button type="submit" class="btn btn-primary btn-lg btn-block login-button">Login</button>
	</div>
	<input type="hidden" name="csrf" value="<?= csrf_token() ?>" />
</form>
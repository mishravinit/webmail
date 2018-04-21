<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-cloak="">

	<div ng-if="userLoggedIn" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="app-btns col-xs-12 col-sm-4 col-md-3 col-lg-3">
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="compose()">Compose</button>
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="inbox()">Inbox</button>
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="sent()">Sent</button>
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="drafts()">Drafts</button>
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="trash()">Inbox Trash</button>
			<button class="button_black col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="logout()">Log out</button>
		</div>
		<div class="app-main col-xs-12 col-sm-8 col-md-9 col-lg-9">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="currentMain == 'compose'">
				<?php $this->load->view('compose_mail')?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
				<h2 ng-if="currentMain == 'inbox'">Inbox</h2>
				<h2 ng-if="currentMain == 'sent'">Sent</h2>
				<h2 ng-if="currentMain == 'drafts'">Drafts</h2>
				<h2 ng-if="currentMain == 'trash'">Inbox Trash</h2>
				<div ng-if="currentMain != 'compose'"> <?php $this->load->view('mail_list')?></div>
			</div>
		</div>
	</div>
	<div ng-if="!userLoggedIn" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		Username : <input type="text" ng-model = "username" />
		Password : <input type="password" ng-model = "password" />
		<button ng-click="loginUser(username, password)">Login</button>
	</div>
</div>
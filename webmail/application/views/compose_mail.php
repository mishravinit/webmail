<div class="compose_mail_body col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-if="!mailSent">
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">To</label>
	<!-- <input name="to" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" type="text" ng-model="toUsers" ng-blur="saveDraft()" /> -->
	<select class="select-emails" multiple ng-model="cmusers" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-blur="saveDraft(cmusers, mailSubject, mailBody, mailAttachments, 'draft')">
	    <option ng-repeat="user in cmuseroptions" value="{{user.ID}}">{{user.email}}</option>
	</select>
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Subject</label>
	<input name="to" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" type="text" ng-model="mailSubject" ng-blur="saveDraft(cmusers, mailSubject, mailBody, mailAttachments, 'draft')"/>
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Body</label>
	<textarea name="body" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"  ng-model="mailBody" ng-blur="saveDraft(cmusers, mailSubject, mailBody, mailAttachments, 'draft')"></textarea>
	<label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">Attachments</label>
	<input type="file" ng-files="getTheFiles($files)" ng-model="mailAttachments"/>
	<button ng-click="saveDraft(cmusers, mailSubject, mailBody, mailAttachments, 'sent')">Send</button>
	<button ng-click="saveDraft(cmusers, mailSubject, mailBody, mailAttachments, 'draft')">Save</button>
	<div ng-if="senderror">Cant send without "To"</div>
</div>
<div ng-if="mailSent">
	Mails Sent successfully 
</div>

<div ng-if="!showingMailThread && !mailDeleted" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<table style="width:100%;">
		<tr>
			<th>Sender</th>
			<th>Subject</th>
			<th>Time</th>
			<th>Read Status</th>
		</tr>
	  <tr ng-repeat="mail in maillist" ng-click="fetchMailThread(mail.thread_id, currentMain)">
	     <td>{{mail.sender_name}}</td>
	     <td>{{mail.subject}}</td>
	     <td>{{mail.timestamp}}</td>
	     <td ng-if="mail.read_status == 0" >Unread</td>
	     <td ng-if="mail.read_status == 1" >Read</td>
	  </tr>
	</table>
</div>

<div ng-if="showingMailThread && !mailDeleted" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div ng-repeat="mail in mailThread" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom:10px;">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			Sender: {{mail.sender_name}}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			Date: {{mail.timestamp}}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			Subject: {{mail.subject}}
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
			Body:<span style="white-space:pre-wrap;">{{mail.body}}</span>
		</div>
		
		<div ng-if="mail.attachments && mail.attached.length" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
			<div ng-repeat="attachment in mail.attached">
				Attachment: <span style="white-space:pre-wrap;">{{attachment}}</span>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<button ng-click="forwardMail(mail)">Forward</button>
			<button ng-click="replyToMail(mail)">Reply</button>
			<button ng-click="deleteMail(mail,currentMain)">Delete</button>
			<button ng-if="currentMain == 'drafts'" ng-click="editDraft(mail)">Edit</button>
		</div>
		<hr>
	</div>
	
</div>

<div ng-if="mailDeleted">
	Mail Deleted Successfully 
</div>
var defApp = angular.module('defApp',  ['ui.select2', 'ngCookies']);

defApp.directive('ngFiles', ['$parse', function ($parse) {

    function fn_link(scope, element, attrs) {
        var onChange = $parse(attrs.ngFiles);
        element.on('change', function (event) {
            onChange(scope, { $files: event.target.files });
        });
    };

    return {
        link: fn_link
    }
} ]);

defApp.controller('defCtrl', function($scope, $http, $cookies) {
	
	
	$scope.restUrlBase = "http://localhost/webmailapi/";
	$scope.userLoggedIn = $cookies.get("userLoggedIn");
	$scope.currentMain = 'inbox';
	$scope.username="";
	$scope.currentUserID = $cookies.get("currentUserID");
	$scope.showingMailThread = false;
	$scope.maillist = null;
	$scope.mailliststart=0;
	$scope.cmuseroptions = null;
	$scope.cmusers = [];
	$scope.cmmailtype = 1;
	$scope.cmtid = null;
	$scope.frmid = null;
	$scope.rpmid = null;
	$scope.mailSubject = '';
	$scope.mailBody = '';
	$scope.mailAttachments = [];
	$scope.cmMailID = 0;
	$scope.mailSent = false;
	$scope.replytomail = '';
	$scope.senderror = false;
	$scope.savingDraft == false;
	$scope.mailDeleted = false;
	$scope.replyToDefault = false;
	
	$scope.resetComposeMail = function(){
		$scope.cmusers = [];
		$scope.cmmailtype = 1;
		$scope.mailSubject = '';
		$scope.mailBody = '';
		$scope.mailAttachments = [];
		$scope.cmMailID = 0;
		$scope.maillist = null;
		$scope.mailliststart=0;
		$scope.mailSent = false;
		$scope.mailThread = null;
		$scope.replytomail = '';
		$scope.cmtid = null;
		$scope.frmid = null;
		$scope.rpmid = null;
		$scope.senderror = false;
		$scope.savingDraft == false;
		$scope.mailDeleted = false;
		$scope.replyToDefault = false;
	}
	
	$scope.logout = function(){
		$cookies.remove("myFavorite",{path:'/'});
		$cookies.remove("myFavorite",{path:'/'});
		$scope.userLoggedIn = false;
		$scope.currentUserID = 0;
	}
	
	$scope.loginUser = function(username, password){
		
		$http({
	        url: $scope.restUrlBase +'auth', 
	        method: "GET",
	        params: {
	        	u: username,
	        	p: password
	        },
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
	    }).then(function successCallback(response) {
	    		//console.log(response);
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   if(response.data.success){
					   $scope.userLoggedIn = true;
					   $scope.currentUserID = response.data.uid;
					   $cookies.put('userLoggedIn', true,{path:'/'});
					   $cookies.put('currentUserID', response.data.uid,{path:'/'});
				   }
			   }		   
			   
		}, function errorCallback(response) {
		});
	}
	
	$scope.fetchcmuseroptions = function(){
		
		$http({
	        url: $scope.restUrlBase +'users', 
	        method: "GET",
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
	    }).then(function successCallback(response) {
	    		//console.log(response);
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   
				   $scope.cmuseroptions = response.data;
				   $(".select-emails").select2();
			   }		   
			   
		}, function errorCallback(response) {
		});
	}
	
	$scope.compose = function(){
		$scope.currentMain = "compose";
		$scope.resetComposeMail();
		$scope.fetchcmuseroptions();
	}
	
	$scope.fetchMailList = function(type){
		
		$http({
	        url: $scope.restUrlBase +'mails/query/'+type, 
	        method: "GET",
	        params: {
	        	uid: $scope.currentUserID,
	        	ms:$scope.mailliststart
	        },
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
	    }).then(function successCallback(response) {
	    		//console.log(response);
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   if(response.data.success && response.data.mails){
					   $scope.showingMailThread = false;
					   $scope.maillist = response.data.mails;
				   }
			   }		   
			   
		}, function errorCallback(response) {
		});
	}
	
	$scope.fetchMailThread = function(thread_id, mailtype){
		
		$scope.showingMailThread = true;
		$http({
	        url: $scope.restUrlBase +'mails/query/thread', 
	        method: "GET",
	        params: {
	        	tid: thread_id,
	        	mt: mailtype,
	        	uid: $scope.currentUserID
	        },
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
	    }).then(function successCallback(response) {
	    		//console.log(response);
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   if(response.data.success && response.data.mailthread){
					   $scope.mailThread = response.data.mailthread;
				   }
			   }		   
			   
		}, function errorCallback(response) {
		});
	}
	
	$scope.inbox = function(){
		$scope.currentMain = "inbox";
		$scope.resetComposeMail();
		$scope.fetchMailList("inbox");
	}
	
	$scope.drafts = function(){
		$scope.currentMain = "drafts";
		$scope.resetComposeMail();
		$scope.fetchMailList("drafts");
	}
	
	$scope.trash = function(){
		$scope.currentMain = "trash";
		$scope.resetComposeMail();
		$scope.fetchMailList("trash");
		
	}
	
	$scope.sent = function(){
		$scope.currentMain = "sent";
		$scope.resetComposeMail();
		$scope.fetchMailList("sent");
	}
	
	$scope.saveDraft = function(cmusers, mailSubject, mailBody, mailAttachments,sendstatus){
		//$scope.cmusers = cmusers;
		if(sendstatus == 'sent' && (!$scope.cmusers)){
			$scope.senderror = true;
			return;
		}
		
		if(sendstatus == 'sent' && $scope.savingDraft == true){
			alert("Saving Draft..Wait for a while before sending");
			return;
		}
		
		if(sendstatus == 'draft' && $scope.savingDraft == true){
			return;
		}
		
		$scope.cmusers = JSON.stringify(cmusers);
		
		$scope.mailSubject = mailSubject;
		$scope.mailBody = mailBody;
		$scope.mailAttachments = mailAttachments;
		var method = null;
		if($scope.cmMailID == 0) method = "POST";
		else method = "PUT";

		$http({
	        url: $scope.restUrlBase +'mails/mail/'+$scope.cmMailID, 
	        method: method,
	        params: {
	        	cmusers: $scope.cmusers,
	        	ms:$scope.mailSubject,
	        	mb:$scope.mailBody,
	        	ma:$scope.mailAttachments,
	        	si:$scope.currentUserID,
	        	cmmt:$scope.cmmailtype,
	        	ss:sendstatus,
	        	tid:$scope.cmtid,
	        	frmid:$scope.frmid,
	        	rpmid:$scope.rpmid
	        },
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
	    }).then(function successCallback(response) {
	    	
	    	 //console.log(response);
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   //console.log(response.data);
				   if(response.data.success){
					   if(sendstatus == 'sent'){
						   $scope.resetComposeMail();
						   $scope.mailSent = true;
						   $scope.cmMailID = 0;
					   }else{
						   $scope.cmMailID = response.data.mail_id;
					   }
				   }
				  				   
			   }
			   
			   $scope.savingDraft == false;
			   
		}, function errorCallback(response) {
			$scope.savingDraft == false;
		});
	}
	
	$scope.forwardMail = function(mail){
		$scope.compose();
		$scope.mailSubject = "FWD: "+mail.subject;
		$scope.mailBody = mail.sender_name+ " wrote on "+mail.timestamp+" : " +mail.body;
		$scope.mailAttachments = mail.attachments;
		$scope.cmmailtype = 2;
		$scope.cmtid = mail.thread_id;
		$scope.frmid = mail.ID;
	}
	
	$scope.replyToMail = function(mail){
		$scope.compose();
		$scope.mailSubject = "RE: "+mail.subject;
		$scope.mailBody = mail.sender_name+ " wrote on "+mail.timestamp+" : " +mail.body;
		$scope.cmusers = [mail.sender_id];
		//$scope.mailAttachments.push(mail.attachments);
		$scope.cmmailtype = 3;
		$scope.cmtid = mail.thread_id,
		$scope.rpmid = mail.ID;
		$scope.replyToDefault = true;
	}
	
	
	$scope.editDraft = function(mail){
		$scope.compose();
		$scope.mailSubject = mail.subject;
		$scope.mailBody = mail.body;
		//$scope.mailAttachments.push(mail.attachments);
		$scope.cmmailtype = mail.type;
		$scope.cmtid = mail.thread_id,
		$scope.cmMailID = mail.ID;
	}
	
	$scope.deleteMail = function(mail, type){
		
		$http({
	        url: $scope.restUrlBase +'mails/mail/'+mail.ID, 
	        method: "DELETE",
	        params: {
	        	mt:type,
	        	ui:$scope.currentUserID,
	        },
	        headers: {
	        	'X-Requested-With' :'XMLHttpRequest'
	 		},
		    }).then(function successCallback(response) {
		    	 //console.log(response);
				   if(!response || !response.data) {
					   
				   }
				   
				   if(response && response.data ) {
					   //console.log(response.data);
					   if(response.data.success){
						   $scope.resetComposeMail();
						   $scope.mailDeleted = true;
					   }
					  				   
				   }		   
				   
			}, function errorCallback(response) {
		});
	}
	
	$scope.mailAttachments = function(){
		
	}
	
	var formdata = new FormData();
    $scope.getTheFiles = function ($files) {
        angular.forEach($files, function (value, key) {
            formdata.append(key, value);
        });
        
        if($scope.cmMailID){
			$scope.uploadFiles();
		}else{
			alert("Save Mail Before Attachment");
		}
        
    };

    // NOW UPLOAD THE FILES.
    $scope.uploadFiles = function () {

        var request = {
            method: 'POST',
            url: $scope.restUrlBase +'files/upload/',
            data: formdata,
            params: {
	        	mi:$scope.cmMailID
	        },
            transformRequest: angular.identity,
            headers: {
                'Content-Type': undefined
            }
        };

        // SEND THE FILES.
        $http(request)
        .then(function successCallback(response) {
			   if(!response || !response.data) {
			   }
			   
			   if(response && response.data ) {
				   if(response.data.success){
				   }
				  				   
			   }		   
			   
		}, function errorCallback(response) {
	});
    }
	
	
	$scope.$watch('selected', function(nowSelected){
	    // reset to nothing, could use `splice` to preserve non-angular references
	    $scope.selectedValues = [];
	    if( ! nowSelected ){
	        // sometimes selected is null or undefined
	        return;
	    }

	    // here's the magic
	    angular.forEach(nowSelected, function(val){
	        $scope.cmusers.push( val.id.toString() );
	    });
	    
	    
	});
	
});

$(document).ready(function() {
	$(".select-emails").select2();
});

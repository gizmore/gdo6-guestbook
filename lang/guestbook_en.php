<?php
return array(
'link_guestbook' => 'Guestbook',
'gdo_guestbook' => 'Guestbook',
'gbm_guestbook' => 'Guestbook',
'link_your_guestbook' => 'Your guestbook',
'gdo_guestbookmessage' => 'Guestbook Entry',

# Config
'cfg_gb_ipp' => 'Messages per page',
'cfg_gb_allow_url' => 'Allow a website link for each entry?',
'cfg_gb_allow_email' => 'Allow an email field for each entry?',
'cfg_gb_allow_guest_view' => 'Allow guests to view the guestbook?',
'cfg_gb_allow_guest_sign' => 'Allow guests to sign the guestbook?',
'cfg_gb_allow_level' => 'Allow setting a min level for signing?',
'cfg_gb_allow_user_gb' => 'Allow users to create a guestbook?',
'cfg_gb_user_gb_level' => 'Required userlevel for an own guestbook',
'cfg_gb_guest_captcha' => 'Force captcha for guests?',
'cfg_gb_member_captcha' => 'Force captcha for members?',
'cfg_gb_left_bar' => 'Show site guestbook in the left sidebar?',
'cfg_gb_right_bar' => 'Show user guestbook in the right sidebar?',
    
# Settings
'link_create_guestbook' => 'Create a guestbook',
'link_edit_guestbook' => 'Edit your guestbook',
'cfg_user_guestbook' => 'Your guestbook Id',
    
# View
'list_view_guestbook' => '%s entries in the guestbook',
'link_sign_guestbook' => 'Sign the guestbook',
'link_edit_guestbook' => 'Edit the guestbook',
'link_gb_approval_list' => 'Moderate entries',
'err_no_guestbook' => 'The guestbook does not exist.',

# Crud
'div_gb_appearance' => 'Appearance',
'guestbook_default_title' => 'Guestbook',
'guestbook_default_descr' => 'Guestbook for %s',
'div_gb_signing' => 'Signing',
'gb_locked' => 'Is signing disabled?',
'gb_moderated' => 'Shall entries be moderated?',
'gb_notify_mail' => 'Enable Email notification?',
'div_gb_permissions' => 'Permissions',
'gb_guest_view' => 'Allow guests to view the guestbook?',
'gb_guest_sign' => 'Allow guests to sign the guestbook?',
'div_gb_metadata' => 'Metadata',
'gb_allow_email' => 'Allow users to post an email?',
'gb_allow_url' => 'Allow users to post a website?',

# Sign
'ft_guestbook_sign' => 'Sign the guestbook',
'err_guestbook_sign' => 'You are not allowed to sign the guestbook.',
'err_guestbook_locked' => 'The guestbook is currently disabled for writing messages.',
'err_no_guests' => 'Guests are not allowed to sign the guestbook.',
'msg_gb_moderation' => 'Your created entry has to be approved before it is shown.',
'msg_gb_signed' => 'Thank you for your guestbook entry. It is now shown.',

# Approvelist
'list_approvelist' => '%s entries to approve',
    
# Approve
'err_already_approved' => 'This guestbook entry has been approved already.',
'msg_gbm_approved' => 'The guestbook message has been made visible.',

# Delete
'err_gbmsg_not_found' => 'The guestbook entry could not been found.',
'msg_gbmsg_deleted' => 'The guestbook entry has been marked as deleted.',
    
# Admin
'link_approvals' => 'Awaiting approval',
'link_site_guestbook' => 'Configure site guestbook',
'link_configure_gb_module' => 'Configure module',
'gbm_approved' => 'Approved at',

# Mail Approve
'mail_subj_gb_moderate' => '%s Guestbook Entry',
'mail_body_gb_moderate' => '
Hello %s,
    
There has been made a guestbook entry on %s.
    
Email: %s
Website: %s
Message:
================================
%s
================================
You can approve this message with a single click.
%s

You can delete this message with a single click.
%s
================================
    
Kind Regards,
The %2$s Team',
    
# Mail Notify
'mail_subj_notify_gb' => '%s Guestbook Entry',
'mail_body_notify_gb' => '
Hello %s,

There has been made a guestbook entry on %s.

Email: %s
Website: %s
Message:
================================
%s
================================
This entry has been automatically approved.

You can delete this message with a single click.
%s
================================

Kind Regards,
The %2$s Team',
    
);

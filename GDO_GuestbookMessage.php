<?php
namespace GDO\Guestbook;

use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\DB\GDT_Object;
use GDO\DB\GDT_CreatedBy;
use GDO\DB\GDT_CreatedAt;
use GDO\User\GDO_User;
use GDO\UI\GDT_Message;
use GDO\Date\GDT_DateTime;
use GDO\Profile\GDT_User;
use GDO\Mail\GDT_Email;
use GDO\Net\GDT_Url;
use GDO\Core\GDT_Template;
use GDO\DB\GDT_DeletedBy;
use GDO\DB\GDT_DeletedAt;
use GDO\DB\GDT_Checkbox;

/**
 * Guestbook messages.
 * Not cached.
 * Approval optional.
 * Email and Website optional.
 * @author gizmore
 * @version 6.10
 * @since 6.09
 */
final class GDO_GuestbookMessage extends GDO
{
    ###########
    ### GDO ###
    ###########
    public function gdoCached() { return false; }
    public function gdoColumns()
    {
        return array(
            GDT_AutoInc::make('gbm_id'),
            GDT_Object::make('gbm_guestbook')->notNull()->editable(false)->table(GDO_Guestbook::table())->hidden(),
            GDT_Message::make('gbm_message')->notNull(),
            GDT_Email::make('gbm_email'),
            GDT_Checkbox::make('gbm_email_public')->notNull()->initial('0')->hidden(),
            GDT_Url::make('gbm_website')->reachable()->noFollow(),
            GDT_CreatedBy::make('gbm_user')->editable(false),
            GDT_CreatedAt::make('gbm_created'),
            GDT_User::make('gbm_approver')->editable(false)->label('gbm_approver')->hidden(),
            GDT_DateTime::make('gbm_approved')->editable(false)->hidden(),
            GDT_DeletedBy::make('gbm_deletor'),
            GDT_DeletedAt::make('gbm_deleted'),
        );
    }
    
    ##############
    ### Getter ###
    ##############
    /**
     * @return GDO_User
     */
    public function getUser() { return $this->getValue('gbm_user'); }
    public function getUserID() { return $this->getVar('gbm_user'); }
    /**
     * @return GDO_Guestbook
     */
    public function getGuestbook() { return $this->getValue('gbm_guestbook'); }
    public function getGuestbookID() { return $this->getVar('gbm_guestbook'); }
    public function isApproved() { return $this->getVar('gbm_approved') !== null; }
    public function isDeleted() { return $this->getVar('gbm_deleted') !== null; }
    public function isEMailPublic() { return $this->getValue('gbm_email_public'); }
    
    ##############
    ### Render ###
    ##############
    public function displayMessage() { return $this->gdoColumn('gbm_message')->renderCell(); }
    public function displayEmail() { return $this->gdoColumn('gbm_email')->renderCell(); }
    public function displayWebsite() { return $this->getValue('gbm_website') ? $this->gdoColumn('gbm_website')->renderCell() : ''; }
    public function renderList() { return GDT_Template::php('Guestbook', 'list/message.php', ['gdo' => $this]); }
    
    ############
    ### HREF ###
    ############
    public function hrefEdit() { return href('Guestbook', 'Edit', "&id={$this->getID()}"); }
    public function hrefDelete() { return href('Guestbook', 'Delete', "&id={$this->getID()}&token={$this->gdoHashcode()}"); }
    public function hrefApprove() { return href('Guestbook', 'Approve', "&id={$this->getID()}&token={$this->gdoHashcode()}"); }
    
    ##################
    ### Permission ###
    ##################
    public function canDelete(GDO_User $user=null)
    {
        if ($this->isDeleted())
        {
            return false;
        }
        $user = $user ? $user : GDO_User::current();
        return $user->isStaff() || ($user->getID() === $this->getUserID());
    }

    public function canApprove(GDO_User $user=null)
    {
        if ($this->isApproved())
        {
            return false;
        }
        $user = $user ? $user : GDO_User::current();
        return $user->isStaff();
    }

    public function canSeeMail(GDO_User $user)
    {
        if ($user->isStaff())
        {
            return true;
        }
        return $this->isEMailPublic();
    }
    
}

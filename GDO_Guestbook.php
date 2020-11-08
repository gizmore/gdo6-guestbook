<?php
namespace GDO\Guestbook;

use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\User\GDT_User;
use GDO\UI\GDT_Title;
use GDO\UI\GDT_Message;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_Checkbox;
use GDO\User\GDO_User;
use GDO\User\GDT_Level;
use GDO\Core\GDT_Error;

/**
 * A Guestbook.
 * Mutliple guestbooks are possible. (one per user)
 * 
 * @author gizmore
 * @version 6.10
 * @since 3.00
 */
final class GDO_Guestbook extends GDO
{
    ###############
    ### Factory ###
    ###############
    public static function forSite() { return self::getById('1'); }
    public static function forUser(GDO_User $user) { return self::forUserID($user->getID()); }
    public static function forUserID($userid) { return self::getBy('gb_uid', $userid); }
    
    ###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
		    GDT_AutoInc::make('gb_id'),
		    GDT_User::make('gb_uid')->editable(false),
		    GDT_Title::make('gb_title')->notNull(),
		    GDT_Message::make('gb_descr')->label('description'),
		    GDT_CreatedAt::make('gb_date'),
		    GDT_Level::make('gb_level')->label('guestbook_level'),
		    GDT_Checkbox::make('gb_unlocked')->initial('1'),
		    GDT_Checkbox::make('gb_moderated')->initial('0'),
		    GDT_Checkbox::make('gb_notify_mail')->initial('1'),
		    GDT_Checkbox::make('gb_guest_view')->initial('1'),
		    GDT_Checkbox::make('gb_guest_sign')->initial('1'),
		    GDT_Checkbox::make('gb_allow_url')->initial('0'),
		    GDT_Checkbox::make('gb_allow_email')->initial('1'),
		);
	}
	
	##################
	### Convinient ###
	##################
	/**
	 * @return GDO_User
	 */
	public function getUser() { return $this->getValue('gb_uid'); }
	public function getUserID() { return $this->getVar('gb_uid'); }
	public function getTitle() { return $this->getVar('gb_title'); }
	public function getDescr() { return $this->getVar('gb_descr'); }
	public function getDate() { return $this->getVar('gb_date'); }
	# Options
	public function getLevel() { return $this->getValue('gb_level'); }
	public function isLocked() { return !$this->getValue('gb_unlocked'); }
	public function isModerated() { return $this->getValue('gb_moderated'); }
	public function isGuestViewable() { return $this->getValue('gb_guest_view'); }
	public function isGuestWriteable() { return $this->getValue('gb_guest_sign'); }
	public function isURLAllowed() { return $this->getValue('gb_allow_url'); }
	public function isEMailAllowed() { return $this->getValue('gb_allow_email'); }
	public function isEMailOnSign() { return $this->getValue('gb_notify_mail'); }
	
	##############
	### Render ###
	##############
	public function displayTitle() { return html($this->getTitle()); }
	public function displayDescription() { return $this->getValue('gb_descr'); }
	
	#############
	### HREFs ###
	#############
	public function href_gb_edit() { return href('Guestbook', 'Crud', '&id='.$this->getID()); }
	public function href_gb_view() { return href('Guestbook', 'View', '&id='.$this->getID()); }
	public function href_gb_sign() { return href('Guestbook', 'Sign', '&id='.$this->getID()); }
	public function href_gb_approval() { return href('Guestbook', 'ApproveList', '&id='.$this->getID()); }
	
	##############
	### Notify ###
	##############
	/**
	 * Get all users that want to be notified. Staff and owner.
	 * @return \GDO\User\GDO_User[]
	 */
	public function getNotifyUsers()
	{
	    $users = GDO_User::staff();
	    if ($user = $this->getUser())
	    {
    	    if (!in_array($user, $users, true))
    	    {
    	        $users[] = $user;
    	    }
	    }
	    return $users;
	}
	
	##################
	### Permission ###
	##################
	public function canCreate(GDO_User $user)
	{
	    return $user->isAuthenticated() && ($user->getLevel() >= Module_Guestbook::instance()->cfgLevel());
	}
	
	public function canModerate(GDO_User $user)
	{
	    return $user->isStaff() || ($user->getID() === $this->getUserID());
	}
	
	public function canView(GDO_User $user)
	{
	    if ($user->isMember())
	    {
	        return true;
	    }
	    elseif (Module_Guestbook::instance()->cfgAllowGuestView())
	    {
	        if ($this->isGuestViewable())
	        {
	            return true;
	        }
	    }
	    return false;
	}
	
	public function canSign(GDO_User $user, &$errorResponse=null)
	{
	    $mod = Module_Guestbook::instance();
	    
	    if ($this->isLocked())
	    {
	        $errorResponse = GDT_Error::responseWith('err_guestbook_locked');
	        return false;
	    }
	    
	    if (!$user->isMember())
	    {
	        if ( (!$mod->cfgAllowGuestSign()) || (!$this->isGuestWriteable()) )
	        {
	            $errorResponse = GDT_Error::responseWith('err_no_guests');
	            return false;
	        }
	    }
	    
	    if ($mod->cfgAllowgLevel())
	    {
	        if ($this->getLevel() > $user->getLevel())
	        {
	            $errorResponse = GDT_Error::responseWith('err_level_too_low', [$this->getLevel(), $user->getLevel()]);
	            return false;
	        }
	    }
	    
	    return true; 
	}
	
}

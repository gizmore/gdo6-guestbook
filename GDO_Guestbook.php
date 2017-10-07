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
/**
 * A Guestbook.
 * Mutliple guestbooks are possible.
 * @author gizmore
 */
final class GDO_Guestbook extends GDO
{
    ###############
    ### Factory ###
    ###############
    public static function forSite() { return self::getById('1'); }
    public static function forUser(GDO_User $user) { return self::forUserID($user->getID()); }
    public static function forUserID($userid) { return self::getBy('', $userid); }
    
    ###########
	### GDO ###
	###########
	public function gdoColumns()
	{
		return array(
		    GDT_AutoInc::make('gb_id'),
		    GDT_User::make('gb_uid'),
		    GDT_Title::make('gb_title')->notNull(),
		    GDT_Message::make('gb_descr'),
		    GDT_CreatedAt::make('gb_date'),
		    GDT_Checkbox::make('gb_locked')->initial('0'),
		    GDT_Checkbox::make('gb_moderated')->initial('0'),
		    GDT_Checkbox::make('gb_guestview')->initial('1'),
		    GDT_Checkbox::make('gb_guestsign')->initial('1'),
		    GDT_Checkbox::make('gb_allow_url')->initial('1'),
		    GDT_Checkbox::make('gb_allow_email')->initial('1'),
		    GDT_Checkbox::make('gb_notify_mail')->initial('1'),
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
	public function isLocked() { return $this->getValue('gb_locked'); }
	public function isModerated() { return $this->getValue('gb_moderated'); }
	public function isGuestViewable() { return $this->getValue(self::ALLOW_GUEST_VIEW); }
	public function isGuestWriteable() { return $this->getValue(self::ALLOW_GUEST_SIGN); }
	public function isURLAllowed() { return $this->getValue(self::ALLOW_WEBSITE); }
	public function isEMailAllowed() { return $this->getValue(self::ALLOW_EMAIL); }
	public function isEMailOnSign() { return $this->getValue(self::EMAIL_ON_ENTRY); }
	
	#############
	### HREFs ###
	#############
	public function href_gb_edit() { return href('Guestbook', 'EditGB', '&id='.$this->getID()); }
	public function href_gb_view() { return href('Guestbook', 'Show', '&id='.$this->getID()); }
	public function href_gb_sign() { return href('Guestbook', 'Sign', '&id='.$this->getID()); }
	
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
	    return $user->isMember() || $this->isGuestViewable();
	}
	
	public function canSign(GDO_User $user)
	{
	    return $this->isLocked() ? false : ($user->isMember() || $this->isGuestWriteable()); 
	}
}

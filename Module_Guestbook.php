<?php
namespace GDO\Guestbook;
use GDO\Core\GDO_Module;
use GDO\User\GDT_Username;
use GDO\DB\GDT_UInt;
use GDO\DB\GDT_Checkbox;
use GDO\User\GDT_Level;
/**
 * Creates one global site guestbook.
 * Optionally allow users to create their own guestbook.
 * @author gizmore
 * @since 2.00
 * @version 6.05
 */
final class Module_Guestbook extends GDO_Module
{
	##############
	### Module ###
	##############
    public $module_priority = 45;
	public function getClasses() { return ['GDO\Guestbook\GDO_Guestbook', 'GDO\Guestbook\GDO_Entry']; }
	public function onInstall() { return Install::onInstall(); }
	public function onLoadLanguage() { return $this->loadLanguage('lang/guestbook'); }

	##############
	### Config ###
	##############
	public function getConfig()
	{
	    return array(
	        GDT_UInt::make('gb_ipp')->initial(10)->max(100),
	        GDT_Checkbox::make('gb_allow_url')->initial('1'),
	        GDT_Checkbox::make('gb_allow_email')->initial('1'),
	        GDT_Checkbox::make('gb_allow_guest')->initial('1'),
	        GDT_Checkbox::make('gb_captcha')->initial('1'),
	        GDT_UInt::make('gb_max_ulen')->initial(GDT_Username::LENGTH)->max(64),
	        GDT_UInt::make('gb_max_msglen')->initial('1024')->max(4096),
	        GDT_UInt::make('gb_max_titlelen')->initial('96')->max(255),
	        GDT_UInt::make('gb_max_descrlen')->initial('255')->max(1024),
	        GDT_Level::make('gb_level')->initial('0'),
        );
	}
	public function cfgItemsPerPage() { return $this->getModuleVarInt('gb_ipp', 10); }
	public function cfgAllowURL() { return $this->getModuleVarBool('gb_allow_url', '1'); }
	public function cfgAllowEMail() { return $this->getModuleVarBool('gb_allow_email', '1'); }
	public function cfgAllowGuest() { return $this->getModuleVarBool('gb_allow_guest', '1'); }
	public function cfgGuestCaptcha() { return $this->getModuleVarBool('gb_captcha', '1'); }
	public function cfgMaxUsernameLen() { return $this->getModuleVarInt('gb_max_ulen', GDT_Username::LENGTH); }
	public function cfgMaxMessageLen() { return $this->getModuleVarInt('gb_max_msglen', 1024); }
	public function cfgMaxTitleLen() { return $this->getModuleVarInt('gb_max_titlelen', 63); }
	public function cfgMaxDescrLen() { return $this->getModuleVarInt('gb_max_descrlen', 255); }
	public function cfgLevel() { return $this->getModuleVarInt('gb_level', 0); }
}

<?php
namespace GDO\Guestbook;
use GDO\Core\GDO_Module;
use GDO\User\GDT_Username;
use GDO\DB\GDT_UInt;
use GDO\DB\GDT_Checkbox;
use GDO\User\GDT_Level;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
/**
 * Creates one global site guestbook.
 * Optionally allow users to create their own guestbook.
 * @author gizmore
 * @since 3.00
 * @version 6.10
 */
final class Module_Guestbook extends GDO_Module
{
	##############
	### Module ###
	##############
    public $module_priority = 45;
	public function getClasses() { return ['GDO\Guestbook\GDO_Guestbook']; }
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
	        GDT_Checkbox::make('gb_allow_website')->initial('1'),
	        GDT_Checkbox::make('gb_allow_guest')->initial('1'),
	        GDT_Checkbox::make('gb_allow_user_gb')->initial('0'),
	        GDT_Checkbox::make('gb_captcha')->initial('1'),
	        GDT_UInt::make('gb_max_msglen')->initial('1024')->max(4096),
	        GDT_UInt::make('gb_max_titlelen')->initial('96')->max(255),
	        GDT_UInt::make('gb_max_descrlen')->initial('255')->max(1024),
	        GDT_Level::make('gb_level')->initial('0'),
	        GDT_Checkbox::make('gb_left_bar')->initial('1'),
        );
	}
	public function cfgItemsPerPage() { return $this->getConfigValue('gb_ipp'); }
	public function cfgAllowURL() { return $this->getConfigValue('gb_allow_url'); }
	public function cfgAllowEMail() { return $this->getConfigValue('gb_allow_email'); }
	public function cfgAllowUserGB() { return $this->getConfigValue('gb_allow_user_gb'); }
	public function cfgAllowWebsite() { return $this->getConfigValue('gb_allow_website'); }
	public function cfgAllowGuest() { return $this->getConfigValue('gb_allow_guest'); }
	public function cfgGuestCaptcha() { return $this->getConfigValue('gb_captcha'); }
	public function cfgMaxMessageLen() { return $this->getConfigValue('gb_max_msglen'); }
	public function cfgMaxTitleLen() { return $this->getConfigValue('gb_max_titlelen'); }
	public function cfgMaxDescrLen() { return $this->getConfigValue('gb_max_descrlen'); }
	public function cfgLevel() { return $this->getConfigValue('gb_level'); }
	public function cfgLeftBar() { return $this->getConfigValue('gb_left_bar'); }
	
	############
	### Hook ###
	############
	public function hookLeftBar(GDT_Bar $bar)
	{
	    if ($this->cfgLeftBar())
	    {
    	    $bar->addField(GDT_Link::make('link_guestbook')->href(href('Guestbook', 'View', '&id=1')));
	    }
	}
	
}

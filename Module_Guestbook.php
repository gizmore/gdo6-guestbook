<?php
namespace GDO\Guestbook;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_UInt;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Bar;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\DB\GDT_Object;
use GDO\User\GDO_UserSetting;
use GDO\User\GDT_Level;
use GDO\Core\GDT_Response;
use GDO\UI\GDT_Card;

/**
 * Creates one global site guestbook.
 * Optionally allow users to create their own guestbook.
 * 
 * @author gizmore
 * @version 6.10
 * @since 3.00
 * 
 * @see GDO_Guestbook
 * @see GDO_GuestbookMessage
 */
final class Module_Guestbook extends GDO_Module
{
    ######################
    ### Site Guestbook ###
    ######################
    /**
     * @return GDO_Guestbook
     */
    public function getSiteGuestbook()
    {
        if ($this->isInstalled())
        {
            return GDO_Guestbook::getById('1');
        }
    }
    
	##############
	### Module ###
	##############
    public $module_priority = 45;
    
	public function onInstall() { return Install::onInstall(); }
	public function onLoadLanguage() { return $this->loadLanguage('lang/guestbook'); }
	
	public function getDependencies() { return ['Admin']; }

	public function getClasses()
	{
	    return array(
	        GDO_Guestbook::class,
	        GDO_GuestbookMessage::class,
	    );
	}
	
	#############
	### Admin ###
	#############
	public function adminBar()
	{
	    $bar = GDT_Bar::make()->horizontal();
	    
	    $linkApprovals = GDT_Link::make('link_approvals')->href(href('Guestbook', 'ApproveList'));
	    $bar->addField($linkApprovals);
	    
	    $linkSiteConfig = GDT_Link::make('link_site_guestbook')->href(href('Guestbook', 'Crud', '&id=1'));
	    $bar->addField($linkSiteConfig);

	    $linkConfig = GDT_Link::make('link_configure_gb_module')->href(href('Admin', 'Configure', '&module=Guestbook'));
	    $bar->addField($linkConfig);
	    
	    return GDT_Response::makeWith($bar);
	}
	
	public function guestbookViewBar(GDO_Guestbook $gb=null)
	{
	    if (!$gb)
	    {
	        return $this->adminBar();
	    }
	    $user = GDO_User::current();
	    $bar = GDT_Bar::make()->horizontal();
	    $linkSign = GDT_Link::make('link_sign_guestbook')->enabled($gb->canSign($user))->href($gb->href_gb_sign());
	    $bar->addField($linkSign);
	    $linkEdit = GDT_Link::make('link_edit_guestbook')->enabled($gb->canModerate($user))->href($gb->href_gb_edit());
	    $bar->addField($linkEdit);
	    $linkApproval = GDT_Link::make('link_gb_approval_list')->enabled($gb->canModerate($user))->href($gb->href_gb_approval());
	    $bar->addField($linkApproval);
	    return GDT_Response::makeWith($bar);
	}
	
	
	##############
	### Config ###
	##############
	public function href_administrate_module() { return href('Guestbook', 'Admin'); }

	public function getConfig()
	{
	    return array(
	        GDT_UInt::make('gb_ipp')->initial(10)->max(100),
	        GDT_Checkbox::make('gb_allow_guest_view')->initial('1'),
	        GDT_Checkbox::make('gb_allow_guest_sign')->initial('1'),
	        GDT_Checkbox::make('gb_allow_url')->initial('1'),
	        GDT_Checkbox::make('gb_allow_email')->initial('1'),
	        GDT_Checkbox::make('gb_allow_level')->initial('1'),
	        GDT_Checkbox::make('gb_allow_user_gb')->initial('0'),
	        GDT_Level::make('gb_user_gb_level'),
	        GDT_Checkbox::make('gb_guest_captcha')->initial('1'),
	        GDT_Checkbox::make('gb_member_captcha')->initial('0'),
	        GDT_Checkbox::make('gb_left_bar')->initial('1'),
	        GDT_Checkbox::make('gb_right_bar')->initial('1'),
	    );
	}
	public function cfgItemsPerPage() { return $this->getConfigValue('gb_ipp'); }
	public function cfgAllowGuestView() { return $this->getConfigValue('gb_allow_guest_view'); }
	public function cfgAllowGuestSign() { return $this->getConfigValue('gb_allow_guest_sign'); }
	public function cfgAllowURL() { return $this->getConfigValue('gb_allow_url'); }
	public function cfgAllowEMail() { return $this->getConfigValue('gb_allow_email'); }
	public function cfgAllowgLevel() { return $this->getConfigValue('gb_allow_level'); }
	public function cfgAllowUserGB() { return $this->getConfigValue('gb_allow_user_gb'); }
	public function cfgLevel() { return $this->getConfigValue('gb_user_gb_level'); }
	public function cfgGuestCaptcha() { return $this->getConfigValue('gb_guest_captcha'); }
	public function cfgMemberCaptcha() { return $this->getConfigValue('gb_member_captcha'); }
	public function cfgLeftBar() { return $this->getConfigValue('gb_left_bar'); }
	public function cfgRightBar() { return $this->getConfigValue('gb_right_bar'); }
	public function cfgCaptcha()
	{
	    $user = GDO_User::current();
	    return $user->isMember() ? $this->cfgMemberCaptcha() : $this->cfgGuestCaptcha();
	}
	
	################
	### Settings ###
	################
	/**
	 * @param GDO_User $user
	 * @return GDO_Guestbook
	 */
	public function getUserGuestbook(GDO_User $user=null)
	{
	    $user = $user ? $user : GDO_User::current();
	    return GDO_UserSetting::userGetValue($user, 'user_guestbook');
	}
	
	public function getUserConfig()
	{
	    $config = [];
	    if ($this->cfgAllowUserGB())
	    {
	        $config[] = GDT_Object::make('user_guestbook')->table(GDO_Guestbook::table());
	    }
	    return $config;
	}
	
	public function getUserSettings()
	{
	    $config = [];
	    if ($this->cfgAllowUserGB())
	    {
    	    if ($gb = $this->getUserGuestbook())
    	    {
    	        $config[] = GDT_Link::make('link_edit_guestbook')->href(href('Guestbook', 'Crud', '&id=' . $gb->getID()));
    	    }
    	    else
    	    {
    	        $config[] = GDT_Link::make('link_create_guestbook')->href(href('Guestbook', 'Crud'));
    	    }
	    }
	    return $config;
	}
	
	############
	### Hook ###
	############
	public function hookLeftBar(GDT_Bar $bar)
	{
	    if ($this->cfgLeftBar())
	    {
	        if ($gb = $this->getSiteGuestbook())
	        {
	            if ($gb->canView(GDO_User::current()))
	            {
	                $bar->addField(GDT_Link::make('link_guestbook')->href(href('Guestbook', 'View', '&id=1')));
	            }
	        }
	    }
	}
	
	public function hookRightBar(GDT_Bar $bar)
	{
	    if ($this->cfgRightBar())
	    {
	        if ($gb = $this->getUserGuestbook())
	        {
                $bar->addField(GDT_Link::make('link_your_guestbook')->href(href('Guestbook', 'View', '&id=' . $gb->getID())));
	        }
	    }
	}
	
	public function hookProfileCard(GDO_User $user, GDT_Card $card)
	{
	    if ($gb = GDO_Guestbook::forUser($user))
	    {
	        $linkGuestbook = GDT_Link::make('link_guestbook')->href($gb->href_gb_view())->icon('book');
	        $card->actions()->addField($linkGuestbook);
	    }
	}
	
}
